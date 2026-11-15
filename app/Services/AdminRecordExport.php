<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use App\Models\BarangayClearance;
use App\Models\BlotterReport;
use App\Models\IndigencyApplication;
use App\Models\Residency;
use App\Models\Residents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use OpenSpout\Common\Entity\Cell\StringCell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\AutoFilter;
use OpenSpout\Writer\XLSX\Entity\SheetView;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminRecordExport
{
    private const SOURCES = [
        'residents' => [Residents::class, 'residents', 'Residents'],
        'residency' => [Residency::class, 'residency_applications', 'Residency'],
        'clearance' => [BarangayClearance::class, 'barangay_clearances', 'Clearance'],
        'indigency' => [IndigencyApplication::class, 'indigency_applications', 'Indigency'],
        'blotter' => [BlotterReport::class, 'blotter_reports', 'Incident Reports'],
    ];

    public const COMMON_COLUMNS = ['ID', 'Reference Number', 'Full Name', 'Contact Number', 'Email', 'Address', 'Status', 'Created At', 'Updated At'];

    public function download(Request $request, string $source): StreamedResponse
    {
        [$model, $table, $module] = self::SOURCES[$source];
        $request->validate([
            'ids' => ['nullable', 'array'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:'.$table.',id'],
            'export_format' => ['nullable', 'in:xlsx,csv'],
        ]);
        $format = $request->input('export_format') ?: 'xlsx';
        $query = $model::query()->when($request->input('ids'), fn (Builder $query, array $ids) => $query->whereIn('id', $ids));
        $count = (clone $query)->count();
        if ($source === 'blotter') {
            $query->withCount(['witnesses', 'files']);
        }
        $headings = array_merge(self::COMMON_COLUMNS, array_keys($this->details(new $model, $source)));
        AdminActivityLog::create([
            'user_id' => auth('admin')->id(), 'action' => 'EXPORT', 'module' => $module,
            'details' => ['ids' => $request->input('ids') ?: 'all', 'count' => $count, 'file_format' => strtoupper($format)],
            'ip_address' => $request->ip(), 'user_agent' => $request->userAgent(),
        ]);

        return response()->streamDownload(function () use ($query, $source, $headings, $format, $module) {
            if ($format === 'csv') {
                $file = fopen('php://output', 'wb');
                try {
                    fwrite($file, "\xEF\xBB\xBF");
                    fputcsv($file, $headings, ',', '"', '');
                    foreach ($query->lazyById(500) as $record) {
                        fputcsv($file, array_map($this->csvText(...), $this->values($record, $source)), ',', '"', '');
                    }
                } finally {
                    fclose($file);
                }

                return;
            }

            // Build outside the public directory. OpenSpout writes rows in
            // batches without holding the entire spreadsheet in PHP memory.
            $directory = storage_path('app/private/exports');
            File::ensureDirectoryExists($directory, 0700);
            $path = tempnam($directory, 'export_');
            $options = new Options;
            $options->setTempFolder($directory);
            $options->setColumnWidthForRange(22, 1, count($headings));
            $options->setColumnWidth(32, 3, 5);
            $options->setColumnWidth(45, 6);
            $writer = new Writer($options);
            try {
                $writer->openToFile($path);
                $sheet = $writer->getCurrentSheet();
                $sheet->setName($module)->setSheetView((new SheetView)->setFreezeRow(2));
                $style = (new Style)->setFontBold()->setFontColor('FFFFFF')->setBackgroundColor('B4232D');
                $writer->addRow(Row::fromValues($headings, $style));
                $rows = 1;
                foreach ($query->lazyById(500) as $record) {
                    // Explicit string cells preserve 09 and prevent formula
                    // execution for resident-supplied names and addresses.
                    $writer->addRow(new Row(array_map(fn ($value) => new StringCell($value, null), $this->values($record, $source))));
                    $rows++;
                }
                $sheet->setAutoFilter(new AutoFilter(0, 1, count($headings) - 1, $rows));
                $writer->close();
                readfile($path);
            } finally {
                try {
                    $writer->close();
                } finally {
                    @unlink($path);
                }
            }
        }, $table.'_'.now()->format('Y-m-d_His').'.'.$format, [
            'Content-Type' => $format === 'xlsx' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'text/csv; charset=UTF-8',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function mobileNumber(mixed $value): string
    {
        $original = trim((string) $value);
        if ($original === '' || ! preg_match('/^[+\d\s().-]+$/u', $original)) {
            return $original;
        }
        $digits = preg_replace('/\D/u', '', $original);
        if (preg_match('/^09\d{9}$/', $digits)) {
            return $digits;
        }
        if (preg_match('/^9\d{9}$/', $digits)) {
            return '0'.$digits;
        }
        if (preg_match('/^(?:63|0063)(9\d{9})$/', $digits, $match)) {
            return '0'.$match[1];
        }

        // Keep missing, landline, or malformed historical values intact.
        return $original;
    }

    private function values(Model $record, string $source): array
    {
        $resident = $source === 'residents';
        $incident = $source === 'blotter';
        $fullName = $incident ? $record->complainant_name : collect($resident
            ? [$record->firstname, $record->middlename, $record->lastname, $record->suffix]
            : [$record->first_name, $record->middle_name, $record->last_name, $record->suffix])
            ->filter(fn ($part) => filled($part))->map(fn ($part) => trim($part))->implode(' ');
        $status = $resident ? 'active' : $record->status;
        $values = [
            $record->id, $resident ? '' : $record->reference_number, $fullName,
            $this->mobileNumber($incident ? $record->complainant_contact : ($resident ? $record->contact : $record->contact_number)),
            $incident ? $record->complainant_email : $record->email,
            $incident ? $record->complainant_address : $record->address,
            match ($status) {
                'ready_pickup' => 'Ready for pickup', default => ucfirst(str_replace('_', ' ', (string) $status))
            },
            $this->date($record->created_at, true), $this->date($record->updated_at, true),
            ...array_values($this->details($record, $source)),
        ];

        return array_map(fn ($value) => (string) ($value ?? ''), $values);
    }

    private function details(Model $r, string $source): array
    {
        if ($source === 'residents') {
            return ['Username' => $r->username, 'Birthdate' => $this->date($r->birthdate)];
        }
        if ($source === 'blotter') {
            return [
                'Report Type' => $r->report_type_label, 'Incident Date' => $this->date($r->incident_date),
                'Incident Time' => $r->incident_time, 'Location' => $r->location, 'Description' => $r->description,
                'Immediate Action' => $r->immediate_action, 'Respondent Name' => $r->respondent_name,
                'Respondent Contact' => $this->mobileNumber($r->respondent_contact), 'Respondent Address' => $r->respondent_address,
                'Respondent Description' => $r->respondent_description,
                'Confidentiality' => match ($r->confidentiality) {
                    'low' => 'Public Report', 'medium' => 'Confidential Report', 'high' => 'Anonymous Report', default => $r->confidentiality
                },
                'Additional Info' => $r->additional_info, 'Deleted At' => $this->date($r->deleted_at, true),
                'Witnesses Count' => $r->witnesses_count, 'Files Count' => $r->files_count,
            ];
        }
        $details = ['First Name' => $r->first_name, 'Middle Name' => $r->middle_name, 'Last Name' => $r->last_name,
            'Suffix' => $r->suffix, 'Birthdate' => $this->date($r->birthdate), 'Gender' => $r->gender];
        if ($source === 'residency') {
            $details += ['Civil Status' => $r->civil_status, 'Birth Place' => $r->birth_place,
                'Years Residing' => match ($r->years_residing) {
                    'less1' => 'Less than 1 year', '20+' => '20+ years', default => $r->years_residing ? $r->years_residing.' years' : ''
                },
                'Residency Type' => $r->residency_type, 'Household Members' => $r->household_members];
        }
        if ($source === 'indigency') {
            $details += ['Monthly Income' => $r->monthly_income_label, 'Household Members' => $r->household_members];
        }
        $details += ['Purpose' => $r->purpose, 'Purpose Other' => $r->purpose_other];
        if ($source === 'clearance') {
            $details['Fee'] = $r->fee === null ? 'Depending on purpose' : number_format((float) $r->fee, 2, '.', '');
        }

        return $details;
    }

    private function date(mixed $value, bool $time = false): string
    {
        return $value ? Carbon::parse($value)->format($time ? 'Y-m-d H:i:s' : 'Y-m-d') : '';
    }

    private function csvText(string $value): string
    {
        return preg_match('/^[\s\x00-\x20]*[=+@-]/u', $value) ? "'".$value : $value;
    }
}
