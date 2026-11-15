<?php

namespace Tests\Feature;

use App\Models\AdminRole;
use App\Models\AdminUser;
use App\Models\BarangayClearance;
use App\Models\BlotterReport;
use App\Models\IndigencyApplication;
use App\Models\Residency;
use App\Models\Residents;
use App\Services\AdminRecordExport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminRecordExportTest extends TestCase
{
    use RefreshDatabase;

    private function signIn(): void
    {
        $role = AdminRole::create(['name' => 'super_admin']);
        $this->actingAs(AdminUser::create([
            'first_name' => 'Test', 'last_name' => 'Exporter', 'username' => 'exporter', 'email' => 'exporter@example.test',
            'password' => bcrypt('Password123!'), 'role_id' => $role->id, 'status' => 'active',
        ]), 'admin');
    }

    private function record(string $source, string $number)
    {
        if ($source === 'residents') {
            return Residents::factory()->create(['contact' => str_starts_with($number, '+') ? '9123456789' : $number]);
        }
        $common = [
            'reference_number' => fake()->unique()->bothify('TEST-########'), 'first_name' => 'Maria', 'last_name' => 'Peña',
            'birthdate' => '1990-01-01', 'gender' => 'Female', 'address' => "25 Camia Street, Hulong Duhat\nMalabon",
            'contact_number' => $number, 'email' => 'maria@example.test', 'purpose' => 'employment',
            'valid_id_path' => 'private/valid_id/test.pdf', 'status' => 'processing',
        ];

        return match ($source) {
            'residency' => Residency::create($common + ['government_id' => 'private/valid_id/test.pdf', 'civil_status' => 'Single', 'birth_place' => 'Malabon', 'years_residing' => '5-10', 'residency_type' => 'owner', 'household_members' => 3]),
            'clearance' => BarangayClearance::create($common + ['fee' => '50.25']),
            'indigency' => IndigencyApplication::create($common + ['monthly_income' => 'below 5k', 'household_members' => 3]),
            'blotter' => BlotterReport::create([
                'reference_number' => $common['reference_number'], 'report_type' => 'dispute', 'incident_date' => '2026-01-01',
                'incident_time' => '10:00', 'location' => 'Camia Street', 'description' => 'Test report',
                'complainant_name' => 'Maria Peña', 'complainant_contact' => $number, 'complainant_email' => 'maria@example.test',
                'complainant_address' => $common['address'], 'respondent_contact' => '9223456789', 'confidentiality' => 'medium', 'status' => 'processing',
            ]),
        };
    }

    public static function exports(): array
    {
        $cases = [];
        foreach (['residents', 'residency', 'clearance', 'indigency', 'blotter'] as $source) {
            foreach (['xlsx', 'csv'] as $format) {
                $cases[$source.' '.$format] = [$source, $format];
            }
        }

        return $cases;
    }

    #[DataProvider('exports')]
    public function test_every_export_uses_common_columns_and_preserves_phone_numbers(string $source, string $format): void
    {
        $this->signIn();
        $selected = $this->record($source, '+63 912-345-6789');
        $this->record($source, '09223456789');
        $this->record($source, '09323456789')->delete();
        $response = $this->post(route('admin.'.$source.'.export'), ['ids' => [$selected->id], 'export_format' => $format]);
        $response->assertOk()->assertDownload();
        $content = $response->streamedContent();
        $rows = $format === 'xlsx' ? $this->xlsxRows($content) : $this->csvRows($content);
        $this->assertSame(AdminRecordExport::COMMON_COLUMNS, array_slice($rows[0], 0, 9));
        $this->assertCount(2, $rows);
        $this->assertSame((string) $selected->id, $rows[1][0]);
        $this->assertSame('09123456789', $rows[1][3]);
        if ($source === 'blotter') {
            $this->assertSame('09223456789', $rows[1][array_search('Respondent Contact', $rows[0], true)]);
        }
        if ($source === 'clearance') {
            $this->assertSame('50.25', $rows[1][array_search('Fee', $rows[0], true)]);
        }
        $all = $this->post(route('admin.'.$source.'.export'), ['export_format' => $format])->assertOk();
        $allRows = $format === 'xlsx' ? $this->xlsxRows($all->streamedContent()) : $this->csvRows($all->streamedContent());
        $this->assertCount(3, $allRows, 'All-record exports exclude archived records.');
        $this->assertSame(['09123456789', '09223456789'], array_column(array_slice($allRows, 1), 3));
    }

    private function csvRows(string $content): array
    {
        $file = fopen('php://temp', 'w+b');
        fwrite($file, substr($content, 3)); // UTF-8 BOM
        rewind($file);
        $rows = [];
        while (($row = fgetcsv($file, null, ',', '"', '')) !== false) {
            $rows[] = $row;
        }
        fclose($file);

        return $rows;
    }

    private function xlsxRows(string $content): array
    {
        $path = tempnam(sys_get_temp_dir(), 'export_test_');
        file_put_contents($path, $content);
        $zip = new \ZipArchive;
        try {
            $this->assertTrue($zip->open($path));
            $xml = simplexml_load_string($zip->getFromName('xl/worksheets/sheet1.xml'));
            $xml->registerXPathNamespace('s', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $this->assertSame([], $xml->xpath('//s:f'), 'User-supplied data must not become Excel formulas.');
            $rows = [];
            foreach ($xml->xpath('//s:sheetData/s:row') as $row) {
                $values = [];
                foreach ($row->c as $cell) {
                    $this->assertSame('inlineStr', (string) $cell['t']);
                    $values[] = (string) $cell->is->t;
                }
                $rows[] = $values;
            }

            return $rows;
        } finally {
            $zip->close();
            unlink($path);
        }
    }

    public static function numbers(): array
    {
        return [
            ['09123456789', '09123456789'], ['0912 345 6789', '09123456789'],
            ['+639123456789', '09123456789'], ['639123456789', '09123456789'],
            ['9123456789', '09123456789'], ['00639123456789', '09123456789'],
            [null, ''], ['8-281-1373', '8-281-1373'], ['09123', '09123'], ['09invalid123456789', '09invalid123456789'],
        ];
    }

    #[DataProvider('numbers')]
    public function test_mobile_formatting_does_not_invent_numbers(mixed $input, string $expected): void
    {
        $this->assertSame($expected, app(AdminRecordExport::class)->mobileNumber($input));
    }

    public function test_untrusted_cell_values_stay_text_and_default_download_is_excel(): void
    {
        $this->signIn();
        $record = Residents::factory()->create(['firstname' => '=1+1', 'lastname' => 'Person', 'contact' => '9123456789']);
        $xlsx = $this->post(route('admin.residents.export'), ['ids' => [$record->id]])
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $rows = $this->xlsxRows($xlsx->streamedContent());
        $this->assertSame('=1+1 Person', $rows[1][2]);
        $csv = $this->post(route('admin.residents.export'), ['export_format' => 'csv']);
        $this->assertSame("'=1+1 Person", $this->csvRows($csv->streamedContent())[1][2]);
    }

    public function test_large_exports_cross_batch_boundaries_without_duplicate_rows(): void
    {
        $this->signIn();
        $first = Residents::factory()->create();
        $attributes = $first->getAttributes();
        unset($attributes['id']);
        $rows = [];
        for ($i = 1; $i <= 500; $i++) {
            $rows[] = array_replace($attributes, ['username' => 'export_'.$i, 'email' => 'export_'.$i.'@example.test', 'contact' => '09'.str_pad($i, 9, '0', STR_PAD_LEFT)]);
        }
        foreach (array_chunk($rows, 100) as $batch) {
            Residents::insert($batch);
        }
        $response = $this->post(route('admin.residents.export'), ['export_format' => 'csv'])->assertOk();
        $rows = $this->csvRows($response->streamedContent());
        $this->assertCount(502, $rows);
        $this->assertCount(501, array_unique(array_column(array_slice($rows, 1), 0)));
    }

    public function test_export_validation_and_permissions_still_apply(): void
    {
        $this->signIn();
        $this->postJson(route('admin.residents.export'), ['export_format' => 'pdf'])->assertUnprocessable()->assertJsonValidationErrors('export_format');
        $this->postJson(route('admin.residents.export'), ['ids' => [999999]])->assertUnprocessable()->assertJsonValidationErrors('ids.0');
        $actor = auth('admin')->user();
        $actor->update(['role_id' => AdminRole::create(['name' => 'no_export'])->id]);
        $this->actingAs($actor->fresh(), 'admin')->postJson(route('admin.residents.export'))->assertForbidden();
    }
}
