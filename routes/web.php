<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncement;
use App\Http\Controllers\Admin\BackupSettingsController;
use App\Http\Controllers\Admin\ClearanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\IncidentReportController;
use App\Http\Controllers\Admin\IndigencyController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RequestHubController;
use App\Http\Controllers\Admin\ResidencyController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\Admin\WitnessController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BarangayClearanceController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\EventProjectController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\IndigencyApplicationController;
use App\Http\Controllers\Internal\MaintenanceController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\ResidencyApplicationController;
use App\Http\Controllers\ResidentsController;
use App\Http\Controllers\SecureFileController;
use App\Http\Controllers\TrackRequestController;
use App\Models\AdminUser;
use App\Models\Residents;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::post('/internal/maintenance/run', [MaintenanceController::class, 'run'])
    ->middleware('throttle:6,1')
    ->name('internal.maintenance.run');

Route::prefix('chatbot')->name('chatbot.')->group(function () {
    Route::get('/', [ChatbotController::class, 'index'])->name('index');
    Route::post('/start', [ChatbotController::class, 'startConversation'])->middleware('throttle:10,1,chat-start')->name('start');
    Route::post('/message', [ChatbotController::class, 'sendMessage'])->middleware('throttle:30,1,chat-message')->name('message');
});

Route::get('/switch-language', function (Request $request) {
    $lang = $request->query('lang');
    if (in_array($lang, ['en', 'tl'])) {
        Session::put('locale', $lang);
    }

    return redirect()->back();
})->name('switch.language');

Route::get('/', [IndexController::class, 'index'])->name('barangay_system.index');

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

    $officialRoleNames = [
        'barangay_captain',
        'barangay_kagawad',
        'barangay_secretary',
        'barangay_treasurer',
    ];

    $officialUsers = AdminUser::query()
        ->active()
        ->with('role:id,name,display_name')
        ->get()
        ->filter(function ($user) use ($officialRoleNames) {
            return in_array(optional($user->role)->name, $officialRoleNames, true);
        })
        ->values();

    $captain = $officialUsers->first(function ($user) {
        return optional($user->role)->name === 'barangay_captain';
    });

    $kagawads = $officialUsers
        ->filter(function ($user) {
            return optional($user->role)->name === 'barangay_kagawad';
        })
        ->values();

    $secretary = $officialUsers->first(function ($user) {
        return optional($user->role)->name === 'barangay_secretary';
    });

    $treasurer = $officialUsers->first(function ($user) {
        return optional($user->role)->name === 'barangay_treasurer';
    });

    return view('barangay_system.officials', [
        'captain' => $captain,
        'kagawads' => $kagawads,
        'secretary' => $secretary,
        'treasurer' => $treasurer,
        'officialCounts' => [
            'total' => $officialUsers->count(),
            'kagawads' => $kagawads->count(),
            'appointed' => collect([$secretary, $treasurer])->filter()->count(),
        ],
    ]);
})->name('officials');

Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements');
Route::post('/announcements/{slug}/track-view', [AdminAnnouncement::class, 'trackView'])->name('announcements.track-view');
Route::get('/announcements/{slug}', [AnnouncementController::class, 'show'])->name('announcements.show');
Route::get('/events-project', [EventProjectController::class, 'index'])->name('events_project');

Route::get('/contacts', function () {
    App::setLocale(Session::get('locale', config('app.locale')));

    return view('barangay_system.contacts');
})->name('contacts');

Route::match(['get', 'post'], '/track-request', [TrackRequestController::class, 'index'])->name('track_request');
Route::post('/track-request/search', [TrackRequestController::class, 'search'])->name('track.request.search');
Route::get('/track-request/print/{reference}', [TrackRequestController::class, 'print'])->name('track.request.print');

Route::get('/login', [ResidentsController::class, 'showLogin'])->name('login');

Route::post('/login', [ResidentsController::class, 'login_res'])->middleware('throttle:8,1')->name('login.res');
Route::get('/valid-id/resubmit', [ResidentsController::class, 'showValidIdResubmission'])->name('valid-id.resubmit.form');
Route::post('/valid-id/resubmit', [ResidentsController::class, 'resubmitValidId'])->middleware('throttle:5,1')->name('valid-id.resubmit');

Route::get('/register', function () {
    App::setLocale(Session::get('locale', config('app.locale')));

    return view('barangay_system.register');
})->name('register');

Route::post('/register', [ResidentsController::class, 'register_res'])->middleware('throttle:5,10')->name('register.res');
Route::get('/register/check-username', [ResidentsController::class, 'checkUsernameAvailability'])->name('register.check-username');
Route::get('/register/check-availability', [ResidentsController::class, 'checkAccountAvailability'])->name('register.check-availability');

Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('resident.password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->middleware('throttle:5,1')->name('resident.password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('resident.password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('resident.password.update');

Route::get('/verify-otp', function (Request $request) {
    $accountType = $request->session()->get('otp_account_type', 'resident');

    $cooldownSeconds = 0;
    $maskedContact = null;

    if ($accountType === 'admin') {
        if (! $request->session()->has('admin_otp_user_id')) {
            return redirect()->route('login')
                ->with('fail', 'OTP session expired. Please login again.');
        }

        $admin = AdminUser::find($request->session()->get('admin_otp_user_id'));

        if (! $admin) {
            return redirect()->route('login')
                ->with('fail', 'Admin account not found. Please login again.');
        }

        $expiresAt = $request->session()->get('admin_otp_expires_at');

        if ($expiresAt && now('Asia/Manila')->lt(\Carbon\Carbon::parse($expiresAt))) {
            $cooldownSeconds = max(
                0,
                now('Asia/Manila')->diffInSeconds(\Carbon\Carbon::parse($expiresAt), false)
            );
        }

        $email = (string) $admin->email;
        [$name, $domain] = array_pad(explode('@', $email, 2), 2, '');

        $maskedContact = strlen($name) > 2
            ? substr($name, 0, 2).'****@'.$domain
            : '****@'.$domain;

        return view('barangay_system.verify_otp', [
            'otpCooldownSeconds' => (int) $cooldownSeconds,
            'maskedContact' => $maskedContact,
        ]);
    }

    if (! $request->session()->has('resident_id')) {
        return redirect()->route('login')
            ->with('fail', 'OTP session expired. Please login again.');
    }

    $resident = Residents::find($request->session()->get('resident_id'));

    if ($resident) {
        if (! empty($resident->phone_otp_expires_at) && now()->lt($resident->phone_otp_expires_at)) {
            $cooldownSeconds = max(0, now()->diffInSeconds($resident->phone_otp_expires_at, false));
        }

        $contactDigits = preg_replace('/\D+/', '', (string) $resident->contact);

        if (strlen($contactDigits) >= 4) {
            $maskedContact = '******'.substr($contactDigits, -4);
        }
    }

    return view('barangay_system.verify_otp', [
        'otpCooldownSeconds' => (int) $cooldownSeconds,
        'maskedContact' => $maskedContact,
    ]);
})->name('otp.form');

Route::post('/verify-otp', [ResidentsController::class, 'verifyOtp'])->middleware('throttle:8,1')->name('otp.verify');

Route::post('/resend-otp', [ResidentsController::class, 'resendOtp'])->middleware('throttle:3,5')->name('otp.resend');

Route::get('/email/verify', function (Request $request) {
    if ($request->user() && $request->user()->hasVerifiedEmail()) {
        return redirect()->route('barangay_system.index');
    }

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

Route::middleware('auth')->group(function () {
    Route::post('/logout', [ResidentsController::class, 'destroy'])->name('logout.res')->middleware('throttle:5,1');

    Route::get('/secure/resident-id/{side}', [SecureFileController::class, 'residentId'])->whereIn('side', ['front', 'back', 'selfie'])->name('secure.resident-id');
    Route::get('/secure/profile-photo', [SecureFileController::class, 'residentProfilePhoto'])->name('secure.profile-photo');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.info');
    Route::post('/profile/account', [ProfileController::class, 'updateAccount'])->name('profile.account');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
    Route::post('/profile/id', [ProfileController::class, 'updateId'])->name('profile.id');
    Route::post('/profile/classification', [ProfileController::class, 'updateClassification'])->middleware('throttle:5,10')->name('profile.classification');
    Route::post('/profile/report-incorrect-profile', [ProfileController::class, 'reportIncorrectProfile'])->middleware(['auth', 'verified', 'throttle:3,60'])->name('profile.reportIncorrectProfile');

    Route::middleware(['verified', 'prevent-back-history'])->group(function () {

        Route::get('/services', [RequestsController::class, 'service'])->name('services');

        Route::get('/indigency', function () {
            session()->forget([
                'submitted_application',
                'reference_number',
            ]);

            return view('barangay_system.certificate_indigency');
        })->name('indigency');

        Route::get('/indigency/form', [IndigencyApplicationController::class, 'index'])->middleware('no-pending-correction')->name('indigency.form');

        Route::post('/indigency/store', [IndigencyApplicationController::class, 'store'])->middleware('no-pending-correction')->name('indigency.store');

        Route::get('/residency', function () {
            session()->forget([
                'submitted_application',
                'reference_number',
            ]);

            return view('barangay_system.certificate_residency');
        })->name('residency');

        Route::get('/residency/form', [ResidencyApplicationController::class, 'index'])->middleware('no-pending-correction')->name('residency.form');

        Route::post('/residency/store', [ResidencyApplicationController::class, 'store'])->middleware('no-pending-correction')->name('residency.store');

        Route::get('/clearance', function () {
            session()->forget([
                'submitted_application',
                'reference_number',
            ]);

            return view('barangay_system.clearance');
        })->name('clearance');

        Route::get('/clearance/form', [BarangayClearanceController::class, 'index'])->middleware('no-pending-correction')->name('clearance.form');

        Route::post('/clearance/store', [BarangayClearanceController::class, 'store'])->middleware('no-pending-correction')->name('clearance.store');

        Route::post('/indigency/report-incorrect-profile', [IndigencyApplicationController::class, 'reportIncorrectProfile'])->middleware(['auth', 'verified', 'throttle:3,60'])->name('indigency.reportIncorrectProfile');

        Route::post('/residency/report-incorrect-profile', [ResidencyApplicationController::class, 'reportIncorrectProfile'])->middleware(['auth', 'verified', 'throttle:3,60'])->name('residency.reportIncorrectProfile');

        Route::post('/clearance/report-incorrect-profile', [BarangayClearanceController::class, 'reportIncorrectProfile'])->middleware(['auth', 'verified', 'throttle:3,60'])->name('clearance.reportIncorrectProfile');

        Route::get('/incident', function () {
            session()->forget([
                'submitted_application',
                'reference_number',
            ]);

            return view('barangay_system.incident');
        })->name('incident');

        Route::get('/incident/form', [BlotterController::class, 'index'])->name('incident.form');

        Route::post('/incident/store', [BlotterController::class, 'store'])->name('incident.store');

        Route::get('/incidentreport/{reference}', [BlotterController::class, 'success'])->name('incident_success');

        Route::get('/receipt/{service}/{reference}', function ($service, $reference_number) {
            $route = session('route');
            $applicant_name = session('applicant_name');
            $date_submitted = session('date_submitted');
            $status = session('status');
            $amount = session('amount');

            return view('barangay_system.success_form.successform', compact(
                'service',
                'reference_number',
                'route',
                'applicant_name',
                'date_submitted',
                'status',
                'amount'
            ));
        })->name('success');
    });
});

Route::prefix(env('ADMIN_PATH'))->name('admin.')->group(function () {
    Route::get('/', function () {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard.index');
        }

        if (Auth::guard('web')->check()) {
            return redirect()->route('barangay_system.index');
        }

        return redirect()->route('login');
    })->name('index');

    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

Route::middleware(['admin.auth'])->prefix(env('ADMIN_PATH'))->name('admin.')->group(function () {

    Route::get('/chatbot/ai-status', [ChatbotController::class, 'aiStatus'])
        ->middleware(['permission:system_settings', 'throttle:30,1,ai-status'])->name('chatbot.ai.status');
    Route::post('/chatbot/test-ai', [ChatbotController::class, 'testAI'])
        ->middleware(['permission:system_settings', 'throttle:5,1,ai-test'])->name('chatbot.test.ai');

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/feed', [AdminNotificationController::class, 'feed'])->name('feed');

        Route::get('/{notification}/open', [AdminNotificationController::class, 'open'])->name('open');

        Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('read-all');

        Route::post('/{notification}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
    });

    Route::get('/secure-file', [SecureFileController::class, 'admin'])->name('secure.file');

    Route::get('/requests', [RequestHubController::class, 'index'])->middleware('permission:view_requests')->name('request.index');

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:view_dashboard|view_forecast')->name('dashboard.index');

    Route::get('/forecast', [DashboardController::class, 'forecastRequests'])->middleware('permission:view_forecast|view_dashboard')->name('dashboard.forecast');

    Route::get('/dashboard/live-stats', [DashboardController::class, 'liveStats'])->middleware('permission:view_dashboard|view_forecast')->name('dashboard.live_stats');

    Route::post('/dashboard/refresh-forecast', [DashboardController::class, 'refreshForecast'])->middleware('permission:view_forecast')->name('dashboard.refresh_forecast');

    Route::prefix('/chatbot')->name('chatbot.')->middleware('permission:manage_chatbot')->group(function () {
        Route::get('/', [ChatbotController::class, 'adminIndex'])->name('index');
        Route::get('/stats', [ChatbotController::class, 'getStats'])->name('stats');
        Route::post('/knowledge', [ChatbotController::class, 'storeKnowledge'])->middleware('permission:edit_chatbot')->name('knowledge.store');
        Route::put('/knowledge/{id}', [ChatbotController::class, 'updateKnowledge'])->middleware('permission:edit_chatbot')->name('knowledge.update');
        Route::delete('/knowledge/{id}', [ChatbotController::class, 'deleteKnowledge'])->middleware('permission:edit_chatbot')->name('knowledge.delete');
        Route::patch('/unmatched/{id}/resolve', [ChatbotController::class, 'resolveUnmatched'])->middleware('permission:edit_chatbot')->name('unmatched.resolve');
    });

    Route::prefix('residents')->name('residents.')->group(function () {
        Route::get('/', [ResidentController::class, 'index'])->middleware('permission:view_residents')->name('index');
        Route::post('/store', [ResidentController::class, 'store'])->middleware('permission:create_residents')->name('store');
        Route::post('/bulk-delete', [ResidentController::class, 'bulkDelete'])->middleware('permission:bulk_delete_residents')->name('bulkDelete');
        Route::post('/export', [ResidentController::class, 'export'])->middleware('permission:export_residents')->name('export');
        Route::put('/{resident}', [ResidentController::class, 'update'])->middleware('permission:update_residents')->name('update');
        Route::delete('/{resident}', [ResidentController::class, 'destroy'])->middleware('permission:delete_residents')->name('destroy');
        Route::post('/{id}/restore', [ResidentController::class, 'restore'])->middleware('permission:restore_residents')->name('restore');
        Route::post('/{id}/verify-id', [ResidentController::class, 'verifyValidId'])->middleware('permission:update_residents')->name('verifyId');
        Route::post('/{id}/reject-id', [ResidentController::class, 'rejectValidId'])->middleware('permission:update_residents')->name('rejectId');
        Route::post('/{id}/classification/{type}/{decision}', [ResidentController::class, 'updateClassificationStatus'])
            ->whereIn('type', ['pwd', 'student'])
            ->whereIn('decision', ['verified', 'rejected'])
            ->middleware('permission:update_residents')
            ->name('classification.update');
    });

    Route::prefix('residency')->name('residency.')->group(function () {
        Route::get('/', [ResidencyController::class, 'index'])->middleware('permission:view_residency')->name('index');
        Route::post('/store', [ResidencyController::class, 'store'])->middleware('permission:create_residency')->name('store');
        Route::post('/bulk-delete', [ResidencyController::class, 'bulkDelete'])->middleware('permission:delete_residency')->name('bulkDelete');
        Route::post('/export', [ResidencyController::class, 'export'])->middleware('permission:export_residency')->name('export');
        Route::put('/{application}', [ResidencyController::class, 'update'])->middleware('permission:update_residency')->name('update');
        Route::post('/{id}/status', [ResidencyController::class, 'updateStatus'])->middleware('permission:approve_residency')->name('status');
        Route::delete('/{id}', [ResidencyController::class, 'destroy'])->middleware('permission:delete_residency')->name('destroy');
        Route::post('/{id}/restore', [ResidencyController::class, 'restore'])->middleware('permission:delete_residency')->name('restore');
        Route::get('/generate-document', [ResidencyController::class, 'generate'])->middleware('permission:generate_residency_document')->name('document');
        Route::get('/generate-document-residency-only', [DocumentController::class, 'generateResidencyOnly'])->middleware('permission:generate_residency_document')->name('document.residency_only');
        Route::post('/bulk-restore', [ResidencyController::class, 'bulkRestore'])->middleware('permission:delete_residency')->name('bulkRestore');
    });

    Route::get('/documents/preview-file/{file}', [DocumentController::class, 'previewGeneratedPdf'])->middleware('permission:generate_indigency_document|generate_clearance_document|generate_residency_document')->where('file', '[A-Za-z0-9._-]+')->name('documents.preview_file');

    Route::prefix('indigency')->name('indigency.')->group(function () {
        Route::get('/', [IndigencyController::class, 'index'])->middleware('permission:view_indigency')->name('index');
        Route::post('/store', [IndigencyController::class, 'store'])->middleware('permission:create_indigency')->name('store');
        Route::post('/bulk-delete', [IndigencyController::class, 'bulkDelete'])->middleware('permission:delete_indigency')->name('bulkDelete');
        Route::post('/export', [IndigencyController::class, 'export'])->middleware('permission:export_indigency')->name('export');
        Route::put('/{application}', [IndigencyController::class, 'update'])->middleware('permission:update_indigency')->name('update');
        Route::post('/{id}/status', [IndigencyController::class, 'updateStatus'])->middleware('permission:approve_indigency')->name('status');
        Route::delete('/{id}', [IndigencyController::class, 'destroy'])->middleware('permission:delete_indigency')->name('destroy');
        Route::post('/{id}/restore', [IndigencyController::class, 'restore'])->middleware('permission:delete_indigency')->name('restore');
        Route::get('/generate-document', [IndigencyController::class, 'generate'])->middleware('permission:generate_indigency_document')->name('document');
        Route::get('/generate-document-indigency-only', [DocumentController::class, 'generateIndigencyOnly'])->middleware('permission:generate_indigency_document')->name('document.indigency_only');
        Route::post('/bulk-restore', [IndigencyController::class, 'bulkRestore'])->middleware('permission:delete_indigency')->name('bulkRestore');
    });

    Route::prefix('barangay-clearance')->name('clearance.')->group(function () {
        Route::get('/', [ClearanceController::class, 'index'])->middleware('permission:view_clearance')->name('index');
        Route::post('/store', [ClearanceController::class, 'store'])->middleware('permission:create_clearance')->name('store');
        Route::post('/bulk-delete', [ClearanceController::class, 'bulkDelete'])->middleware('permission:delete_clearance')->name('bulkDelete');
        Route::post('/export', [ClearanceController::class, 'export'])->middleware('permission:export_clearance')->name('export');
        Route::put('/{application}', [ClearanceController::class, 'update'])->middleware('permission:update_clearance')->name('update');
        Route::post('/{id}/status', [ClearanceController::class, 'updateStatus'])->middleware('permission:approve_clearance')->name('status');
        Route::delete('/{id}', [ClearanceController::class, 'destroy'])->middleware('permission:delete_clearance')->name('destroy');
        Route::post('/{id}/restore', [ClearanceController::class, 'restore'])->middleware('permission:delete_clearance')->name('restore');
        Route::get('/generate-document', [ClearanceController::class, 'generate'])->middleware('permission:generate_clearance_document')->name('document');
        Route::get('/generate-document-clearance-only', [DocumentController::class, 'generateClearanceOnly'])->middleware('permission:generate_clearance_document')->name('document.clearance_only');
        Route::post('/bulk-restore', [ClearanceController::class, 'bulkRestore'])->middleware('permission:delete_clearance')->name('bulkRestore');
    });

    Route::prefix('blotter')->name('blotter.')->group(function () {
        Route::get('/', [IncidentReportController::class, 'index'])->middleware('permission:view_blotter')->name('index');
        Route::post('/store', [IncidentReportController::class, 'store'])->middleware('permission:create_blotter')->name('store');
        Route::post('/bulk-delete', [IncidentReportController::class, 'bulkDelete'])->middleware('permission:delete_blotter')->name('bulkDelete');
        Route::post('/export', [IncidentReportController::class, 'export'])->middleware('permission:export_blotter')->name('export');
        Route::put('/{application}', [IncidentReportController::class, 'update'])->middleware('permission:update_blotter')->name('update');
        Route::post('/{id}/processing', [IncidentReportController::class, 'markProcessing'])->middleware('permission:approve_blotter')->name('processing');
        Route::post('/{id}/approve', [IncidentReportController::class, 'approve'])->middleware('permission:approve_blotter')->name('approve');
        Route::post('/{id}/reject', [IncidentReportController::class, 'reject'])->middleware('permission:reject_blotter')->name('reject');
        Route::delete('/{id}', [IncidentReportController::class, 'destroy'])->middleware('permission:delete_blotter')->name('destroy');
        Route::post('/{id}/restore', [IncidentReportController::class, 'restore'])->middleware('permission:restore_blotter')->name('restore');
        Route::post('/bulk-restore', [IncidentReportController::class, 'bulkRestore'])->middleware('permission:restore_blotter')->name('bulkRestore');
    });

    Route::post('/blotter/{id}/witness', [WitnessController::class, 'store'])->middleware('permission:add_witness')->name('witness.store');

    Route::delete('/witness/{id}', [WitnessController::class, 'destroy'])->middleware('permission:delete_witness')->name('witness.destroy');

    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [AdminAnnouncement::class, 'index'])->middleware('permission:view_announcements')->name('index');
        Route::post('/store', [AdminAnnouncement::class, 'store'])->middleware('permission:create_announcements')->name('store');
        Route::put('/{id}', [AdminAnnouncement::class, 'update'])->middleware('permission:update_announcements')->name('update');
        Route::delete('/{id}', [AdminAnnouncement::class, 'destroy'])->middleware('permission:delete_announcements')->name('destroy');
        Route::post('/bulk-delete', [AdminAnnouncement::class, 'bulkDelete'])->middleware('permission:delete_announcements')->name('bulkDelete');
        Route::post('/{id}/restore', [AdminAnnouncement::class, 'restore'])->middleware('permission:delete_announcements')->name('restore');
        Route::post('/{id}/toggle-feature', [AdminAnnouncement::class, 'toggleFeature'])->middleware('permission:feature_announcements')->name('toggle-feature');
        Route::post('/announcements/bulk-notify-residents', [AdminAnnouncement::class, 'bulkNotifyResidents'])->middleware('permission:create_announcements')->name('bulkNotifyResidents');
    });

    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->middleware('permission:view_events')->name('index');
        Route::post('/store', [EventController::class, 'store'])->middleware('permission:create_events')->name('store');
        Route::put('/{id}', [EventController::class, 'update'])->middleware('permission:update_events')->name('update');
        Route::delete('/{id}', [EventController::class, 'destroy'])->middleware('permission:delete_events')->name('destroy');
        Route::post('/bulk-delete', [EventController::class, 'bulkDelete'])->middleware('permission:delete_events')->name('bulkDelete');
        Route::post('/{id}/restore', [EventController::class, 'restore'])->middleware('permission:delete_events')->name('restore');
        Route::post('/{id}/duplicate', [EventController::class, 'duplicate'])->middleware('permission:duplicate_events')->name('duplicate');
        Route::get('/{id}', [EventController::class, 'show'])->middleware('permission:view_events')->name('show');
    });

    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->middleware('permission:view_projects')->name('index');
        Route::post('/store', [ProjectController::class, 'store'])->middleware('permission:create_projects')->name('store');
        Route::put('/{id}', [ProjectController::class, 'update'])->middleware('permission:update_projects')->name('update');
        Route::delete('/{id}', [ProjectController::class, 'destroy'])->middleware('permission:delete_projects')->name('destroy');
        Route::post('/bulk-delete', [ProjectController::class, 'bulkDelete'])->middleware('permission:delete_projects')->name('bulkDelete');
        Route::post('/{id}/restore', [ProjectController::class, 'restore'])->middleware('permission:delete_projects')->name('restore');
        Route::get('/{id}', [ProjectController::class, 'show'])->middleware('permission:view_projects')->name('show');
        Route::patch('/{id}/progress', [ProjectController::class, 'updateProgress'])->middleware('permission:update_project_progress')->name('progress');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/send-email', [NotificationController::class, 'sendEmail'])->middleware('permission:send_email')->name('sendEmail');
        Route::post('/send-sms', [NotificationController::class, 'sendSMS'])->middleware('permission:send_sms')->name('sendSMS');
        Route::get('/remarks-history', [NotificationController::class, 'remarksHistory'])->middleware('permission:send_email|send_sms')->name('remarksHistory');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/dialogs/{type}/{id}', [AdminUserController::class, 'dialog'])
            ->whereIn('type', ['user-permissions', 'role-edit'])->whereNumber('id')->name('dialog');
        Route::get('/', [AdminUserController::class, 'index'])->middleware('permission:view_users')->name('index');
        Route::post('/', [AdminUserController::class, 'store'])->middleware('permission:create_users')->name('store');
        Route::put('/{id}', [AdminUserController::class, 'update'])->middleware('permission:update_users')->name('update');
        Route::delete('/{id}', [AdminUserController::class, 'destroy'])->middleware('permission:delete_users')->name('destroy');
        Route::post('/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->middleware('permission:reset_user_password')->name('reset-password');

        Route::post('/{id}/permissions', [AdminUserController::class, 'updatePermissions'])->middleware('permission:manage_user_permissions')->name('permissions');
        Route::post('/bulk-actions', [AdminUserController::class, 'bulkAction'])->middleware('permission:bulk_delete_users')->name('bulk-action');
        Route::get('/audit-logs/export-csv', [AdminUserController::class, 'exportAuditLogsCsv'])->middleware('permission:view_users')->name('audit-logs.export-csv');
    });

    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [AdminRoleController::class, 'index'])->middleware('permission:view_roles')->name('index');
        Route::post('/', [AdminRoleController::class, 'store'])->middleware('permission:create_roles')->name('store');
        Route::put('/{id}', [AdminRoleController::class, 'update'])->middleware('permission:update_roles')->name('update');
        Route::delete('/{id}', [AdminRoleController::class, 'destroy'])->middleware('permission:delete_roles')->name('destroy');
        Route::get('/{id}/permissions', [AdminRoleController::class, 'getPermissions'])->middleware('permission:view_roles')->name('permissions');
        Route::post('/{id}/permissions', [AdminRoleController::class, 'updatePermissions'])->middleware('permission:manage_role_permissions')->name('permissions.update');

        Route::post('/{role}/members', [AdminRoleController::class, 'addMember'])->middleware('permission:manage_role_permissions')->name('members.add');
        Route::delete('/{role}/members/{user}', [AdminRoleController::class, 'removeMember'])->middleware('permission:manage_role_permissions')->name('members.remove');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [AdminPermissionController::class, 'index'])->middleware('permission:view_permissions')->name('index');
        Route::post('/role/{roleId}', [AdminPermissionController::class, 'updateRolePermissions'])->middleware('permission:update_permissions')->name('update-role');
        Route::post('/reset-defaults', [AdminPermissionController::class, 'resetToDefault'])->middleware('permission:reset_permission_defaults')->name('reset-defaults');
    });

    Route::prefix('settings/backup')->name('backup.')->group(function () {
        Route::get('/', [BackupSettingsController::class, 'index'])->middleware('permission:view_backup|manage_backup|backup_system')->name('index');
        Route::post('/create', [BackupSettingsController::class, 'store'])->middleware('permission:manage_backup|backup_system')->name('store');
        Route::post('/restore', [BackupSettingsController::class, 'restore'])->middleware('permission:manage_backup|backup_system')->name('restore');
        Route::get('/download/{file}', [BackupSettingsController::class, 'download'])->middleware('permission:view_backup|manage_backup|backup_system')->where('file', '[A-Za-z0-9._-]+')->name('download');
        Route::delete('/{file}', [BackupSettingsController::class, 'destroy'])->middleware('permission:manage_backup|backup_system')->where('file', '[A-Za-z0-9._-]+')->name('destroy');
    });

    Route::get('/settings', [SystemSettingsController::class, 'index'])->middleware('permission:system_settings')->name('settings.index');
    Route::put('/settings', [SystemSettingsController::class, 'update'])->middleware('permission:system_settings')->name('settings.update');
    Route::prefix('settings/document-templates')->name('settings.templates.')->middleware('permission:system_settings')->group(function () {
        Route::get('/{type}/download', [SystemSettingsController::class, 'downloadDocumentTemplate'])->name('download');

        Route::post('/{type}/upload', [SystemSettingsController::class, 'uploadDocumentTemplate'])->name('upload');
    });
    Route::post('/settings/semaphore/refresh', [SystemSettingsController::class, 'refreshSemaphoreStats'])->middleware('permission:system_settings')->name('settings.semaphore.refresh');
});
