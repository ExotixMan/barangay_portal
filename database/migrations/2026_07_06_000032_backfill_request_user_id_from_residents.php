<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
    {
        
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

    public function down(): void
    {
        
    }
};
