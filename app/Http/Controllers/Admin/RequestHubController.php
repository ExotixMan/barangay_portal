<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RequestHubController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();

        return view('admin.admin_request_hub', [
            'canResidency' => $user?->hasAnyPermission(['view_residency']) ?? false,
            'canIndigency' => $user?->hasAnyPermission(['view_indigency']) ?? false,
            'canClearance' => $user?->hasAnyPermission(['view_clearance']) ?? false,
        ]);
    }
}
