<?php

namespace App\Services;

use App\Models\AdminUser;
use App\Models\BarangayClearance;
use App\Models\BlotterReport;
use App\Models\IndigencyApplication;
use App\Models\ReportFile;
use App\Models\RequestRecord;
use App\Models\ResidencyApplication;
use App\Models\Residents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Resolve the record before granting access to confidential content. */
class PrivateRecordAccess
{
    public const FILE_OWNERS = [
        [Residents::class, 'view_residents', ['valid_id', 'valid_id_back', 'valid_id_selfie', 'profile_photo', 'pwd_id_front', 'pwd_id_back', 'student_proof']],
        [ResidencyApplication::class, 'view_residency', ['government_id', 'government_id_back', 'primary_proof']],
        [IndigencyApplication::class, 'view_indigency', ['valid_id_path', 'valid_id_back_path', 'primary_proof']],
        [BarangayClearance::class, 'view_clearance', ['valid_id_path', 'valid_id_back_path', 'primary_proof']],
    ];

    public const MODULES = [
        'residency' => [ResidencyApplication::class, 'view_residency'],
        'indigency' => [IndigencyApplication::class, 'view_indigency'],
        'clearance' => [BarangayClearance::class, 'view_clearance'],
        'incident' => [BlotterReport::class, 'view_blotter'],
    ];

    public function authorizeRequest(string $type, int $id): Model
    {
        abort_unless(isset(self::MODULES[$type]), 404);
        [$model, $permission] = self::MODULES[$type];
        $this->requirePermission($permission);

        return $model::withTrashed()->findOrFail($id);
    }

    public function authorizeFile(string $path): void
    {
        foreach (self::FILE_OWNERS as [$model, $permission, $fields]) {
            if (! $this->admin()?->hasPermission($permission)) {
                continue;
            }
            if ($model::withTrashed()->where(function ($query) use ($fields, $path) {
                foreach ($fields as $field) {
                    $query->orWhere($field, $path);
                }
            })->exists()) {
                return;
            }
        }

        if ($this->admin()?->hasPermission('view_blotter') && ReportFile::where('file_path', $path)
            ->whereIn('blotter_report_id', BlotterReport::withTrashed()->select('id'))->exists()) {
            return;
        }

        foreach (RequestRecord::where(fn ($q) => $q->where('valid_id_path', $path)->orWhere('proof_of_residency_path', $path))->get() as $record) {
            $permission = self::MODULES[$record->request_type][1] ?? null;
            if ($permission && $this->admin()?->hasPermission($permission)) {
                return;
            }
        }

        abort(403);
    }

    public function legacyPaths(): \Generator
    {
        $sources = array_map(fn ($owner) => [$owner[0], $owner[2]], self::FILE_OWNERS);
        $sources[] = [ReportFile::class, ['file_path']];
        $sources[] = [RequestRecord::class, ['valid_id_path', 'proof_of_residency_path']];
        foreach ($sources as [$model, $fields]) {
            $query = $model::query();
            if (in_array(SoftDeletes::class, class_uses_recursive($model), true)) {
                $query->withTrashed();
            }
            foreach ($query->select($fields)->cursor() as $record) {
                foreach ($fields as $field) {
                    $path = $record->{$field};
                    if (is_string($path) && preg_match('#^uploads/(valid_ids?|evidences|proof_of_residency|profile_photos)/#', $path) && ! str_contains($path, '..')) {
                        yield $path;
                    }
                }
            }
        }
    }

    public function authorizeGeneratedPdf(string $filename): void
    {
        abort_unless(preg_match('/^(residency|indigency|clearance|certificate)_(.+)\.pdf$/D', $filename, $matches), 404);
        [, $type, $reference] = $matches;
        if ($type === 'certificate') {
            $record = RequestRecord::findOrFail($reference);
            $type = $record->request_type;
            abort_unless(isset(self::MODULES[$type]), 403);
        } else {
            [$model] = self::MODULES[$type];
            $record = $model::withTrashed()->where('reference_number', $reference)
                ->when(ctype_digit($reference), fn ($q) => $q->orWhereKey((int) $reference))->firstOrFail();
        }
        $this->requirePermission('generate_'.$type.'_document');
    }

    private function requirePermission(string $permission): void
    {
        abort_unless($this->admin()?->hasPermission($permission), 403);
    }

    private function admin(): ?AdminUser
    {
        return auth('admin')->user();
    }
}
