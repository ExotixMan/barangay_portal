<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill clearance requests
        DB::table('barangay_clearances')
            ->whereNull('user_id')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $resident = DB::table('residents')
                        ->where(function ($query) use ($row) {
                            if (!empty($row->contact_number)) {
                                $query->where('contact', $row->contact_number)
                                    ->orWhere('email', $row->email);
                            } else {
                                $query->where('email', $row->email);
                            }
                        })
                        ->first();

                    if ($resident) {
                        DB::table('barangay_clearances')
                            ->where('id', $row->id)
                            ->update(['user_id' => $resident->id]);
                    }
                }
            });

        // Backfill residency requests
        DB::table('residency_applications')
            ->whereNull('user_id')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $resident = DB::table('residents')
                        ->where(function ($query) use ($row) {
                            if (!empty($row->contact_number)) {
                                $query->where('contact', $row->contact_number)
                                    ->orWhere('email', $row->email);
                            } else {
                                $query->where('email', $row->email);
                            }
                        })
                        ->first();

                    if ($resident) {
                        DB::table('residency_applications')
                            ->where('id', $row->id)
                            ->update(['user_id' => $resident->id]);
                    }
                }
            });

        // Backfill indigency requests
        DB::table('indigency_applications')
            ->whereNull('user_id')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $resident = DB::table('residents')
                        ->where(function ($query) use ($row) {
                            if (!empty($row->contact_number)) {
                                $query->where('contact', $row->contact_number)
                                    ->orWhere('email', $row->email);
                            } else {
                                $query->where('email', $row->email);
                            }
                        })
                        ->first();

                    if ($resident) {
                        DB::table('indigency_applications')
                            ->where('id', $row->id)
                            ->update(['user_id' => $resident->id]);
                    }
                }
            });

        // Backfill blotter/incident reports
        DB::table('blotter_reports')
            ->whereNull('user_id')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $resident = DB::table('residents')
                        ->where(function ($query) use ($row) {
                            if (!empty($row->complainant_contact)) {
                                $query->where('contact', $row->complainant_contact)
                                    ->orWhere('email', $row->complainant_email);
                            } else {
                                $query->where('email', $row->complainant_email);
                            }
                        })
                        ->first();

                    if ($resident) {
                        DB::table('blotter_reports')
                            ->where('id', $row->id)
                            ->update(['user_id' => $resident->id]);
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left blank: do not null out backfilled user_id values.
    }
};
