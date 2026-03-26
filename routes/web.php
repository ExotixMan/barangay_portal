<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentsController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ResidencyController;
use App\Http\Controllers\Admin\IndigencyController;
use App\Http\Controllers\Admin\ClearanceController;
use App\Http\Controllers\Admin\IncidentReportController;
use App\Http\Controllers\Admin\WitnessController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncement;
use App\Http\Controllers\Admin\AdminLoginController;

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminPermissionController;

use App\Http\Controllers\Admin\RequestStatusController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\AIncidentReportsController;
use App\Http\Controllers\Admin\AResidentsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\BackupSettingsController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BarangayClearanceController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\IndigencyApplicationController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ResidencyApplicationController;
use App\Http\Controllers\EventProjectController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\TrackRequestController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use Illuminate\Support\Facades\Auth;

// AI Chatbot
Route::prefix('chatbot')->name('chatbot.')->group(function () {
    Route::get('/',          [ChatbotController::class, 'index'])->name('index');
    Route::post('/start',    [ChatbotController::class, 'startConversation'])->name('start');
    Route::post('/message',  [ChatbotController::class, 'sendMessage'])->name('message');
    Route::get('/ai-status', [ChatbotController::class, 'aiStatus'])->name('ai.status');
    Route::post('/test-ai',  [ChatbotController::class, 'testAI'])->name('test.ai');
});

// /* ─── Debug (REMOVE in production!) ──────────────────────────── */
// if (app()->environment('local', 'development')) {
//     Route::get('/chatbot/debug', fn() => view('chatbot.debug'))->name('chatbot.debug');
// }

// Language switch (public)

Route::get('/switch-language', function (Request $request) {
    $lang = $request->query('lang');
    if (in_array($lang, ['en', 'tl'])) {
        Session::put('locale', $lang);
    }
    return redirect()->back();
})->name('switch.language');

// Public routes

Route::get('/', [IndexController::class,'index'])->name('barangay_system.index');

// About pages (public)
Route::get('/history', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.history');
})->name('history');

Route::get('/mission&vision', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.mission_vision');
})->name('mission_vision');

Route::get('/barangay-map', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.map');
})->name('map');

Route::get('/barangay-officials', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.officials');
})->name('officials');

// Community pages (public)
Route::get('/announcements', [AnnouncementController::class,'index'])->name('announcements');
Route::get('/announcements/{slug}', [AnnouncementController::class,'show'])->name('announcements.show');
Route::get('/events-project', [EventProjectController::class, 'index'])->name('events_project');

// Other public pages
Route::get('/contacts', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.contacts');
})->name('contacts');

// Track Request Routes
Route::match(['get', 'post'], '/track-request', [TrackRequestController::class, 'index'])->name('track_request');
Route::post('/track-request/search', [TrackRequestController::class, 'search'])->name('track.request.search');
Route::get('/track-request/print/{reference}', [TrackRequestController::class, 'print'])->name('track.request.print');

// Authentication routes (public)
Route::get('/login', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.login');
})->name('login');

Route::get('/login', [ResidentsController::class, 'showLogin'])->name('login');

Route::post('/login', [ResidentsController::class, 'login_res'])->name('login.res');

Route::get('/register', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.register');
})->name('register');

Route::post('/register', [ResidentsController::class, 'register_res'])->name('register.res');

// Forgot / Reset Password (public)
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('resident.password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('resident.password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('resident.password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('resident.password.update');

// OTP (public)
Route::get('/verify-otp', function () {
    return view('barangay_system.verify_otp');
})->name('otp.form');

Route::post('/verify-otp', [ResidentsController::class, 'verifyOtp'])->name('otp.verify');

// Email verification (requires auth)
Route::get('/email/verify', function (Request $request) {
    return view('barangay_system.verify_email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login')->with('success', 'Email verified successfully!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Verification link resent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



// Authenticated resident routes

Route::middleware('auth')->group(function () {
    Route::get('/logout', [ResidentsController::class, 'destroy'])->name('logout.res')->middleware('throttle:5,1');;

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.info');
    Route::post('/profile/account', [ProfileController::class, 'updateAccount'])->name('profile.account');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
    Route::post('/profile/id', [ProfileController::class, 'updateId'])->name('profile.id');

    Route::middleware('verified')->group(function () {
    // Services pages
    Route::get('/services', [RequestsController::class, 'service'])->name('services');

    // Indigency applications
    Route::get('/indigency', function () {
        
        session()->forget([
            'submitted_application',
            'reference_number'
        ]);
    
        return view('barangay_system.certificate_indigency'); 
         
    })->name('indigency');
    Route::get('/indigency/form', [IndigencyApplicationController::class, 'index'])->name('indigency.form');
    Route::post('/indigency/store', [IndigencyApplicationController::class, 'store'])->name('indigency.store');

    // Residency applications
    Route::get('/residency', function () {
         
        session()->forget([
            'submitted_application',
            'reference_number'
        ]);
    
        return view('barangay_system.certificate_residency'); 
    
    })->name('residency');
    Route::get('/residency/form', [ResidencyApplicationController::class, 'index'])->name('residency.form');
    Route::post('/residency/store', [ResidencyApplicationController::class, 'store'])->name('residency.store');

    // Clearance applications
    Route::get('/clearance', function () { 

        session()->forget([
            'submitted_application',
            'reference_number'
        ]);

        return view('barangay_system.clearance'); 
    })->name('clearance');
    Route::get('/clearance/form', [BarangayClearanceController::class, 'index'])->name('clearance.form');
    Route::post('/clearance/store', [BarangayClearanceController::class, 'store'])->name('clearance.store');

    // Incident/Blotter reports
    Route::get('/incident', function () {
        
        session()->forget([
            'submitted_application',
            'reference_number'
        ]);

        return view('barangay_system.incident'); 
    })->name('incident');
    Route::get('/incident/form', [BlotterController::class, 'index'])->name('incident.form');
    Route::post('/incident/store', [BlotterController::class, 'store'])->name('incident.store');
    Route::get('/incidentreport/{reference}', [BlotterController::class, 'success'])->name('incident_success');

    // Success page
    Route::get('/receipt/{service}/{reference}', function($service, $reference_number) {
        $route = session('route');
        $applicant_name = session('applicant_name');
        $date_submitted = session('date_submitted');
        $status = session('status');
        $amount = session('amount');
        return view('barangay_system.success_form.successform', compact('service', 'reference_number', 'route', 'applicant_name', 'date_submitted', 'status', 'amount'));
    })->name('success');
    });
});


// ADMIN ROUTES

// Admin login routes (public)
Route::prefix(env('ADMIN_PATH'))->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

Route::middleware(['admin.auth'])->prefix(env('ADMIN_PATH'))->name('admin.')->group(function () {
    
    // Dashboard - requires view_dashboard permission
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:view_dashboard')
        ->name('dashboard.index');
    
    Route::get('/forecast', [DashboardController::class, 'forecastRequests'])
        ->middleware('permission:view_forecast|view_dashboard')
        ->name('dashboard.forecast');

    Route::get('/dashboard/live-stats', [DashboardController::class, 'liveStats'])
        ->middleware('permission:view_dashboard')
        ->name('dashboard.live_stats');

    Route::post('/dashboard/refresh-forecast', [DashboardController::class, 'refreshForecast'])
        ->middleware('permission:view_forecast')
        ->name('dashboard.refresh_forecast');

    // CHATBOT
    Route::prefix('/chatbot')->name('chatbot.')->group(function () {
        Route::get('/',                         [ChatbotController::class, 'adminIndex'])->name('index');
        Route::get('/stats',                    [ChatbotController::class, 'getStats'])->name('stats');
        Route::post('/knowledge',               [ChatbotController::class, 'storeKnowledge'])->name('knowledge.store');
        Route::put('/knowledge/{id}',           [ChatbotController::class, 'updateKnowledge'])->name('knowledge.update');
        Route::delete('/knowledge/{id}',        [ChatbotController::class, 'deleteKnowledge'])->name('knowledge.delete');
        Route::patch('/unmatched/{id}/resolve', [ChatbotController::class, 'resolveUnmatched'])->name('unmatched.resolve');
    });

    // ==================== RESIDENTS MODULE ====================
    Route::prefix('residents')->name('residents.')->group(function () {
        Route::get('/', [ResidentController::class, 'index'])->middleware('permission:view_residents')->name('index');
        Route::post('/store', [ResidentController::class, 'store'])->middleware('permission:create_residents')->name('store');
        Route::post('/bulk-delete', [ResidentController::class, 'bulkDelete'])->middleware('permission:bulk_delete_residents')->name('bulkDelete');
        Route::post('/export', [ResidentController::class, 'export'])->middleware('permission:export_residents')->name('export');
        Route::put('/{resident}', [ResidentController::class, 'update'])->middleware('permission:update_residents')->name('update');
        Route::delete('/{resident}', [ResidentController::class, 'destroy'])->middleware('permission:delete_residents')->name('destroy');
        Route::post('/{id}/restore', [ResidentController::class, 'restore'])->middleware('permission:restore_residents')->name('restore');
        Route::post('/{id}/verify-id', [ResidentController::class, 'verifyValidId'])->middleware('permission:update_residents')->name('verifyId');
    });

    // ==================== RESIDENCY MODULE ====================
    Route::prefix('residency')->name('residency.')->group(function () {
        Route::get('/', [ResidencyController::class, 'index'])->middleware('permission:view_residency')->name('index');
        Route::post('/store', [ResidencyController::class, 'store'])->middleware('permission:create_residency')->name('store');
        Route::post('/bulk-delete', [ResidencyController::class, 'bulkDelete'])->middleware('permission:delete_residency')->name('bulkDelete');
        Route::post('/export', [ResidencyController::class, 'export'])->middleware('permission:export_residency')->name('export');
        Route::put('/{application}', [ResidencyController::class, 'update'])->middleware('permission:update_residency')->name('update');
        Route::post('/{id}/status', [ResidencyController::class, 'updateStatus'])->middleware('permission:approve_residency')->name('status');
        Route::delete('/{id}', [ResidencyController::class, 'destroy'])->middleware('permission:delete_residency')->name('destroy');
        Route::get('/generate-document', [ResidencyController::class, 'generate'])->middleware('permission:generate_residency_document')->name('document');
        Route::get('/generate-document-residency-only', [DocumentController::class, 'generateResidencyOnly'])->middleware('permission:generate_residency_document')->name('document.residency_only');
    });

    Route::get('/documents/preview-file/{file}', [DocumentController::class, 'previewGeneratedPdf'])
        ->middleware('permission:generate_indigency_document|generate_clearance_document|generate_residency_document')
        ->where('file', '[A-Za-z0-9._-]+')
        ->name('documents.preview_file');

    // ==================== INDIGENCY MODULE ====================
    Route::prefix('indigency')->name('indigency.')->group(function () {
        Route::get('/', [IndigencyController::class, 'index'])->middleware('permission:view_indigency')->name('index');
        Route::post('/store', [IndigencyController::class, 'store'])->middleware('permission:create_indigency')->name('store');
        Route::post('/bulk-delete', [IndigencyController::class, 'bulkDelete'])->middleware('permission:delete_indigency')->name('bulkDelete');
        Route::post('/export', [IndigencyController::class, 'export'])->middleware('permission:export_indigency')->name('export');
        Route::put('/{application}', [IndigencyController::class, 'update'])->middleware('permission:update_indigency')->name('update');
        Route::post('/{id}/status', [IndigencyController::class, 'updateStatus'])->middleware('permission:approve_indigency')->name('status');
        Route::delete('/{id}', [IndigencyController::class, 'destroy'])->middleware('permission:delete_indigency')->name('destroy');
        // Route::get('/generate-document', [IndigencyController::class, 'generate'])->middleware('permission:generate_indigency_document')->name('document');
        Route::get('/generate-document-indigency-only', [DocumentController::class, 'generateIndigencyOnly'])->middleware('permission:generate_indigency_document')->name('document.indigency_only');
    });

    // ==================== CLEARANCE MODULE ====================
    Route::prefix('barangay-clearance')->name('clearance.')->group(function () {
        Route::get('/', [ClearanceController::class, 'index'])->middleware('permission:view_clearance')->name('index');
        Route::post('/store', [ClearanceController::class, 'store'])->middleware('permission:create_clearance')->name('store');
        Route::post('/bulk-delete', [ClearanceController::class, 'bulkDelete'])->middleware('permission:delete_clearance')->name('bulkDelete');
        Route::post('/export', [ClearanceController::class, 'export'])->middleware('permission:export_clearance')->name('export');
        Route::put('/{application}', [ClearanceController::class, 'update'])->middleware('permission:update_clearance')->name('update');
        Route::post('/{id}/status', [ClearanceController::class, 'updateStatus'])->middleware('permission:approve_clearance')->name('status');
        Route::delete('/{id}', [ClearanceController::class, 'destroy'])->middleware('permission:delete_clearance')->name('destroy');
        Route::get('/generate-document', [ClearanceController::class, 'generate'])->middleware('permission:generate_clearance_document')->name('document');
        Route::get('/generate-document-clearance-only', [DocumentController::class, 'generateClearanceOnly'])->middleware('permission:generate_clearance_document')->name('document.clearance_only');
    });

    // ==================== BLOTTER/INCIDENT MODULE ====================
    Route::prefix('blotter')->name('blotter.')->group(function () {
        Route::get('/', [IncidentReportController::class, 'index'])->middleware('permission:view_blotter')->name('index');
        Route::get('/{id}', [IncidentReportController::class, 'show'])->middleware('permission:view_blotter')->name('show');
        Route::post('/store', [IncidentReportController::class, 'store'])->middleware('permission:create_blotter')->name('store');
        Route::post('/bulk-delete', [IncidentReportController::class, 'bulkDelete'])->middleware('permission:delete_blotter')->name('bulkDelete');
        Route::post('/export', [IncidentReportController::class, 'export'])->middleware('permission:export_blotter')->name('export');
        Route::put('/{application}', [IncidentReportController::class, 'update'])->middleware('permission:update_blotter')->name('update');
        Route::post('/{id}/approve', [IncidentReportController::class, 'approve'])->middleware('permission:approve_blotter')->name('approve');
        Route::post('/{id}/reject', [IncidentReportController::class, 'reject'])->middleware('permission:reject_blotter')->name('reject');
        Route::delete('/{id}', [IncidentReportController::class, 'destroy'])->middleware('permission:delete_blotter')->name('destroy');
        Route::post('/{id}/restore', [IncidentReportController::class, 'restore'])->middleware('permission:restore_blotter')->name('restore');
    });

    // Witness routes (part of blotter module)
    Route::post('/blotter/{id}/witness', [WitnessController::class, 'store'])
        ->middleware('permission:add_witness')
        ->name('witness.store');
    
    Route::delete('/witness/{id}', [WitnessController::class, 'destroy'])
        ->middleware('permission:delete_witness')
        ->name('witness.destroy');

    // ==================== ANNOUNCEMENTS MODULE ====================
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [AdminAnnouncement::class, 'index'])->middleware('permission:view_announcements')->name('index');
        Route::get('/create', [AdminAnnouncement::class, 'create'])->middleware('permission:create_announcements')->name('create');
        Route::post('/store', [AdminAnnouncement::class, 'store'])->middleware('permission:create_announcements')->name('store');
        Route::get('/{id}/edit', [AdminAnnouncement::class, 'edit'])->middleware('permission:update_announcements')->name('edit');
        Route::put('/{id}', [AdminAnnouncement::class, 'update'])->middleware('permission:update_announcements')->name('update');
        Route::delete('/{id}', [AdminAnnouncement::class, 'destroy'])->middleware('permission:delete_announcements')->name('destroy');
        Route::post('/bulk-delete', [AdminAnnouncement::class, 'bulkDelete'])->middleware('permission:delete_announcements')->name('bulkDelete');
        Route::post('/{id}/toggle-feature', [AdminAnnouncement::class, 'toggleFeature'])->middleware('permission:feature_announcements')->name('toggle-feature');
    });

    // ==================== EVENTS MODULE ====================
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->middleware('permission:view_events')->name('index');
        Route::post('/store', [EventController::class, 'store'])->middleware('permission:create_events')->name('store');
        Route::put('/{id}', [EventController::class, 'update'])->middleware('permission:update_events')->name('update');
        Route::delete('/{id}', [EventController::class, 'destroy'])->middleware('permission:delete_events')->name('destroy');
        Route::post('/bulk-delete', [EventController::class, 'bulkDelete'])->middleware('permission:delete_events')->name('bulkDelete');
        Route::post('/{id}/duplicate', [EventController::class, 'duplicate'])->middleware('permission:duplicate_events')->name('duplicate');
        Route::get('/{id}', [EventController::class, 'show'])->middleware('permission:view_events')->name('show');
    });

    // ==================== PROJECTS MODULE ====================
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->middleware('permission:view_projects')->name('index');
        Route::post('/store', [ProjectController::class, 'store'])->middleware('permission:create_projects')->name('store');
        Route::put('/{id}', [ProjectController::class, 'update'])->middleware('permission:update_projects')->name('update');
        Route::delete('/{id}', [ProjectController::class, 'destroy'])->middleware('permission:delete_projects')->name('destroy');
        Route::post('/bulk-delete', [ProjectController::class, 'bulkDelete'])->middleware('permission:delete_projects')->name('bulkDelete');
        Route::get('/{id}', [ProjectController::class, 'show'])->middleware('permission:view_projects')->name('show');
        Route::patch('/{id}/progress', [ProjectController::class, 'updateProgress'])->middleware('permission:update_project_progress')->name('progress');
    });

    // ==================== NOTIFICATIONS MODULE ====================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/send-email', [NotificationController::class, 'sendEmail'])->middleware('permission:send_email')->name('sendEmail');
        Route::post('/send-sms', [NotificationController::class, 'sendSMS'])->middleware('permission:send_sms')->name('sendSMS');
    });

    // // ==================== REQUEST STATUS MODULE ====================
    // Route::prefix('requests')->name('request.')->group(function () {
    //     Route::get('/', [RequestStatusController::class, 'index'])->middleware('permission:view_requests')->name('index');
    //     Route::get('/{request_id}', [RequestStatusController::class, 'show'])->middleware('permission:view_requests')->name('view');
    //     Route::get('/{request_id}/edit', [RequestStatusController::class, 'edit'])->middleware('permission:update_requests')->name('edit');
    //     Route::put('/{request_id}', [RequestStatusController::class, 'update'])->middleware('permission:update_requests')->name('update');
    //     Route::delete('/{request_id}', [RequestStatusController::class, 'destroy'])->middleware('permission:delete_requests')->name('delete');
    //     Route::post('/send-email', [RequestStatusController::class, 'sendEmail'])->middleware('permission:send_request_email')->name('sendEmail');
    //     Route::post('/send-sms', [RequestStatusController::class, 'sendSMS'])->middleware('permission:send_request_sms')->name('sendSMS');
    // });

    // ==================== ADMIN USER MANAGEMENT ====================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->middleware('permission:view_users')->name('index');
        Route::post('/', [AdminUserController::class, 'store'])->middleware('permission:create_users')->name('store');
        Route::put('/{id}', [AdminUserController::class, 'update'])->middleware('permission:update_users')->name('update');
        Route::delete('/{id}', [AdminUserController::class, 'destroy'])->middleware('permission:delete_users')->name('destroy');
        Route::post('/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->middleware('permission:reset_user_password')->name('reset-password');
        // Route::post('/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->middleware('permission:reset_user_password')->name('reset-password');
        Route::post('/{id}/permissions', [AdminUserController::class, 'updatePermissions'])->middleware('permission:manage_user_permissions')->name('permissions');
        Route::post('/bulk-actions', [AdminUserController::class, 'bulkAction'])->middleware('permission:bulk_delete_users')->name('bulk-action');
        Route::get('/audit-logs/export-csv', [AdminUserController::class, 'exportAuditLogsCsv'])->middleware('permission:view_users')->name('audit-logs.export-csv');
    });

    // ==================== ROLE MANAGEMENT ====================
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [AdminRoleController::class, 'index'])->middleware('permission:view_roles')->name('index');
        Route::post('/', [AdminRoleController::class, 'store'])->middleware('permission:create_roles')->name('store');
        Route::put('/{id}', [AdminRoleController::class, 'update'])->middleware('permission:update_roles')->name('update');
        Route::delete('/{id}', [AdminRoleController::class, 'destroy'])->middleware('permission:delete_roles')->name('destroy');
        Route::get('/{id}/permissions', [AdminRoleController::class, 'getPermissions'])->middleware('permission:view_roles')->name('permissions');
        Route::post('/{id}/permissions', [AdminRoleController::class, 'updatePermissions'])->middleware('permission:manage_role_permissions')->name('permissions.update');
        
        // Role member management
        Route::post('/{role}/members', [AdminRoleController::class, 'addMember'])->middleware('permission:manage_role_permissions')->name('members.add');
        Route::delete('/{role}/members/{user}', [AdminRoleController::class, 'removeMember'])->middleware('permission:manage_role_permissions')->name('members.remove');
    });

    // ==================== PERMISSION MANAGEMENT ====================
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [AdminPermissionController::class, 'index'])->middleware('permission:view_permissions')->name('index');
        Route::post('/role/{roleId}', [AdminPermissionController::class, 'updateRolePermissions'])->middleware('permission:update_permissions')->name('update-role');
        Route::post('/reset-defaults', [AdminPermissionController::class, 'resetToDefault'])->middleware('permission:reset_permission_defaults')->name('reset-defaults');
    });

    // ==================== BACKUP SETTINGS ====================
    Route::prefix('settings/backup')->name('backup.')->middleware('permission:view_users')->group(function () {
        Route::get('/', [BackupSettingsController::class, 'index'])->name('index');
        Route::post('/create', [BackupSettingsController::class, 'store'])->name('store');
        Route::post('/restore', [BackupSettingsController::class, 'restore'])->name('restore');
        Route::get('/download/{file}', [BackupSettingsController::class, 'download'])
            ->where('file', '[A-Za-z0-9._-]+')
            ->name('download');
        Route::delete('/{file}', [BackupSettingsController::class, 'destroy'])
            ->where('file', '[A-Za-z0-9._-]+')
            ->name('destroy');
    });
});
use Illuminate\Support\Facades\Http;

Route::get('/debug-semaphore', function () {
    $apiKey = env('SMS_API_KEY');

    // Test with sender name
    $payload = [
        'apikey' => $apiKey,
        'number' => '639994086683',
        'message' => 'This message is from hulong duhat',
        'sendername' => 'HULONGDUHAT',
    ];

    try {
        $response = Http::asForm()->post('https://semaphore.co/api/v4/messages', $payload);

        $result = [
            'payload' => $payload,
            'status_code' => $response->status(),
            'headers' => $response->headers(),
            'body_raw' => $response->body(),
            'body_json' => $response->json(),
            'successful' => $response->successful(),
            'failed' => $response->failed(),
            'client_error' => $response->clientError(),
            'server_error' => $response->serverError(),
        ];

        dd($result);
    } catch (\Exception $e) {
        dd([
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

Route::get('/chatbot/debug',    fn() => view('chatbot.debug'))->name('chatbot.debug');
Route::get('/chatbot/ai-test',  fn() => view('chatbot.ai_test'))->name('chatbot.ai_test');