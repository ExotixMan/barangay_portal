<?php

namespace Tests\Feature;

use App\Services\ForecastService;
use App\Services\SupabaseStorageService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Process;
use Tests\TestCase;

class ForecastServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::setDefaultDriver('array');
        Cache::forget('analytics:refresh-attempt');
    }

    protected function tearDown(): void
    {
        \Illuminate\Support\Carbon::setTestNow();
        parent::tearDown();
    }

    private function data(): array
    {
        $series = [];
        foreach ([7, 30, 90] as $days) {
            $series[$days.'_days'] = array_fill_keys(array_map(fn ($i) => now()->addDays($i)->format('M d, Y'), range(0, $days - 1)), 0);
        }

        return ['forecast_metadata' => ['schema_version' => 2, 'generated_at' => now()->toIso8601String(), 'forecast_start_date' => now()->toDateString()],
            'applications' => array_fill_keys(['barangay_clearances', 'indigency_applications', 'residency_applications'], ['daily_forecast' => $series]),
            'request_forecast' => ['daily_forecast' => $series]];
    }

    public function test_previous_day_forecast_is_stale_even_if_generated_recently(): void
    {
        \Illuminate\Support\Carbon::setTestNow('2026-11-15 00:05:00');
        $service = new ForecastService($this->mock(SupabaseStorageService::class));
        $data = $this->data();
        $this->assertFalse($service->isStale($data));
        $data['forecast_metadata']['generated_at'] = now()->subMinutes(10)->toIso8601String();
        $data['forecast_metadata']['forecast_start_date'] = '2026-11-14';
        $this->assertTrue($service->isStale($data));
    }

    public function test_failed_process_keeps_previous_forecast(): void
    {
        $data = $this->data();
        $storage = $this->mock(SupabaseStorageService::class, function ($m) use ($data) {
            $m->shouldReceive('privateObjectBytes')->andReturn(json_encode($data));
            $m->shouldNotReceive('storePrivateBytes');
        });
        Process::fake(['*' => Process::result(errorOutput: 'Python is missing', exitCode: 1)]);
        $service = new ForecastService($storage);
        try {
            $service->refresh();
            $this->fail('Expected process failure');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('Python is missing', $e->getMessage());
        }
        $this->assertSame($data, $service->data());
    }

    public function test_successful_process_persists_valid_output_and_cleans_temporary_file(): void
    {
        $data = $this->data();
        $path = null;
        Process::fake(function ($process) use ($data, &$path) {
            $path = $process->environment['FORECAST_OUTPUT_PATH'];
            file_put_contents($path, json_encode($data));

            return Process::result(output: 'Forecast saved successfully.');
        });
        $storage = $this->mock(SupabaseStorageService::class, fn ($m) => $m->shouldReceive('storePrivateBytes')->once()->with('analytics/forecast.json', json_encode($data), 'application/json', true)->andReturn('private/analytics/forecast.json'));
        $this->assertSame($data, (new ForecastService($storage))->refresh());
        $this->assertFileDoesNotExist($path);
    }

    public function test_invalid_output_never_overwrites_persisted_forecast(): void
    {
        Process::fake(function ($process) {
            file_put_contents($process->environment['FORECAST_OUTPUT_PATH'], '{}');

            return Process::result();
        });
        $storage = $this->mock(SupabaseStorageService::class, fn ($m) => $m->shouldNotReceive('storePrivateBytes'));
        $this->expectException(\RuntimeException::class);
        (new ForecastService($storage))->refresh();
    }

    public function test_concurrent_refresh_is_not_reported_as_success(): void
    {
        $lock = Cache::lock('analytics:forecast-refresh', 420);
        $lock->get();
        try {
            $this->expectExceptionCode(409);
            (new ForecastService($this->mock(SupabaseStorageService::class)))->refresh();
        } finally {
            $lock->release();
        }
    }

    public function test_storage_outage_does_not_crash_page_load(): void
    {
        $storage = $this->mock(SupabaseStorageService::class, fn ($m) => $m->shouldReceive('privateObjectBytes')->andThrow(new \RuntimeException('Storage unavailable')));
        $this->assertNull((new ForecastService($storage))->data(false));
    }

    public function test_automatic_failures_are_not_retried_on_every_poll(): void
    {
        $storage = $this->mock(SupabaseStorageService::class, fn ($m) => $m->shouldReceive('privateObjectBytes')->andReturn(null));
        Process::fake(['*' => Process::result(exitCode: 1)]);
        $service = new ForecastService($storage);
        $this->assertNull($service->data(true));
        $this->assertNull($service->data(true));
        Process::assertRanTimes(fn () => true, 1);
    }

    public function test_incomplete_persisted_payload_is_unavailable_instead_of_fake_zeroes(): void
    {
        $storage = $this->mock(SupabaseStorageService::class, fn ($m) => $m->shouldReceive('privateObjectBytes')->andReturn('{}'));
        $this->assertNull((new ForecastService($storage))->data(false));
    }
}
