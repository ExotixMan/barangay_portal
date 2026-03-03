<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

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

use App\Http\Controllers\Admin\RequestStatusController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AIncidentReportsController;
use App\Http\Controllers\Admin\AResidentsController;
use App\Http\Controllers\Admin\NotificationController;
// use App\Http\Controllers\IncidentReportController;
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
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

//

// ------------------------------
// Language switch (public)
// ------------------------------
Route::get('/switch-language', function (Request $request) {
    $lang = $request->query('lang');
    if (in_array($lang, ['en', 'tl'])) {
        Session::put('locale', $lang);
    }
    return redirect()->back();
})->name('switch.language');

// ------------------------------
// Public routes
// ------------------------------
Route::get('/', [IndexController::class,'index'])->name('barangay_system.index');

// ------------------------------
// About pages
// ------------------------------
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

// ------------------------------
// Community pages
// ------------------------------

Route::get('/announcements', [AnnouncementController::class,'index'])->name('announcements');
Route::get('/announcements/{slug}', [AnnouncementController::class,'show'])->name('announcements.show');

Route::get('/events-project', [EventProjectController::class, 'index'])->name('events_project');

// ------------------------------
// Other pages
// ------------------------------

Route::get('/contacts', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.contacts');
})->name('contacts');

Route::get('/login', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.login');
})->name('login');

Route::post('/login', [ResidentsController::class, 'login_res'])->name('login.res');

Route::get('/register', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.register');
})->name('register');

Route::post('/register', [ResidentsController::class, 'register_res'])->name('register.res');

// Forgot / Reset Password (Residents)
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

//OTP
Route::get('/verify-otp', function () {
    return view('barangay_system.verify_otp');
})->name('otp.form');

Route::post('/verify-otp', [ResidentsController::class, 'verifyOtp'])
    ->name('otp.verify');

Route::get('/email/verify', function () {
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

// ------------------------------
// Authenticated routes
// ------------------------------
Route::middleware('auth')->group(function () {

    Route::get('/logout', [ResidentsController::class, 'destroy'])->name('logout.res');

    // ------------------------------
    // Services pages
    // ------------------------------
    Route::get('/services', [RequestsController::class, 'service'])->name('services');

    Route::get('/indigency', function () { return view('barangay_system.certificate_indigency'); })->name('indigency');
    Route::get('/indigency/form', [IndigencyApplicationController::class, 'index'])->name('indigency.form');
    Route::post('/indigency/store', [IndigencyApplicationController::class, 'store'])->name('indigency.store');

    Route::get('/residency', function () { return view('barangay_system.certificate_residency'); })->name('residency');
    Route::get('/residency/form', [ResidencyApplicationController::class, 'index'])->name('residency.form');
    Route::post('/residency/store', [ResidencyApplicationController::class, 'store'])->name('residency.store');

    Route::get('/clearance', function () { return view('barangay_system.clearance'); })->name('clearance');
    Route::get('/clearance/form', [BarangayClearanceController::class, 'index'])->name('clearance.form');
    Route::post('/clearance/store', [BarangayClearanceController::class, 'store'])->name('clearance.store');

    // Route::get('/incident', [IncidentReportController::class, 'incident'])->name('incident');
    Route::get('/incident', function () { return view('barangay_system.incident'); })->name('incident');
    Route::get('/incident/form', [BlotterController::class, 'index'])->name('incident.form');
    Route::post('/incident/store', [BlotterController::class, 'store'])->name('incident.store');
    
    Route::get('/incidentreport/{reference}', [BlotterController::class, 'success'])->name('incident_success');

    //Success
    Route::get('/receipt/{service}/{reference}', function($service, $reference_number) {
        $route = session('route');
        $applicant_name = session('applicant_name');
        $date_submitted = session('date_submitted');
        $status = session('status');
        $amount = session('amount');
        return view('barangay_system.success_form.successform', compact('service', 'reference_number', 'route', 'applicant_name', 'date_submitted', 'status', 'amount'));
    })->name('success');

    // ------------------------------
    // Admin pages
    // ------------------------------
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/admin/forecast', [DashboardController::class, 'forecastRequests'])->name('dashboard.forecast');

    // Residents
    Route::get('/admin/residents', [ResidentController::class, 'index'])->name('residents.index');
    Route::post('/admin/residents', [ResidentController::class, 'store'])->name('residents.store');
    Route::post('/admin/residents/bulk-delete', [ResidentController::class, 'bulkDelete'])->name('residents.bulkDelete');
    Route::post('/admin/residents/export', [ResidentController::class, 'export'])->name('residents.export');

    Route::put('/admin/residents/{resident}', [ResidentController::class, 'update'])->name('residents.update');
    Route::delete('/admin/residents/{resident}', [ResidentController::class, 'destroy'])->name('residents.destroy');
    Route::post('/admin/residents/{id}/restore', [ResidentController::class, 'restore'])->name('residents.restore');



    //Notification
    Route::post('/admin//send-email', [NotificationController::class, 'sendEmail'])->name('sendEmail');
    Route::post('/admin/send-sms', [NotificationController::class, 'sendSMS'])->name('sendSMS');

    //Residency
    Route::get('/admin/residency', [ResidencyController::class, 'index'])->name('residency.index');
    Route::post('/admin/residency/store', [ResidencyController::class, 'store'])->name('residency_app.store');
    Route::post('/admin/residency/bulk-delete', [ResidencyController::class, 'bulkDelete'])->name('residency.bulkDelete');
    Route::post('/admin/residency/export', [ResidencyController::class, 'export'])->name('residency.export');

    Route::put('/admin/residency/{application}', [ResidencyController::class, 'update'])->name('residency.update');
    Route::post('/admin/residency/{id}/approve', [ResidencyController::class, 'approve'])->name('residency.approve');
    Route::post('/admin/residency/{id}/reject', [ResidencyController::class, 'reject'])->name('residency.reject');
    Route::delete('/admin/residency/{id}', [ResidencyController::class, 'destroy'])->name('residency.destroy');

    Route::get('/admin/residency/generate-document', [ResidencyController::class, 'generate'])->name('residency.document');

    //Indigency
    Route::get('/admin/indigency', [IndigencyController::class, 'index'])->name('indigency.index');
    Route::post('/admin/indigency/store', [IndigencyController::class, 'store'])->name('indigency_app.store');
    Route::post('/admin/indigency/bulk-delete', [IndigencyController::class, 'bulkDelete'])->name('indigency.bulkDelete');
    Route::post('/admin/indigency/export', [IndigencyController::class, 'export'])->name('indigency.export');

    Route::put('/admin/indigency/{application}', [IndigencyController::class, 'update'])->name('indigency.update');
    Route::post('/admin/indigency/{id}/approve', [IndigencyController::class, 'approve'])->name('indigency.approve');
    Route::post('/admin/indigency/{id}/reject', [IndigencyController::class, 'reject'])->name('indigency.reject');
    Route::delete('/admin/indigency/{id}', [IndigencyController::class, 'destroy'])->name('indigency.destroy');

    Route::get('/admin/indigency/generate-document', [IndigencyController::class, 'generate'])->name('indigency.document');

    //Barangay Clearance
    Route::get('/admin/barangay-clearance', [ClearanceController::class, 'index'])->name('clearance.index');
    Route::post('/admin/barangay-clearance/store', [ClearanceController::class, 'store'])->name('clearance_app.store');
    Route::post('/admin/barangay-clearance/bulk-delete', [ClearanceController::class, 'bulkDelete'])->name('clearance.bulkDelete');
    Route::post('/admin/barangay-clearance/export', [ClearanceController::class, 'export'])->name('clearance.export');

    Route::put('/admin/barangay-clearance/{application}', [ClearanceController::class, 'update'])->name('clearance.update');
    Route::post('/admin/barangay-clearance/{id}/approve', [ClearanceController::class, 'approve'])->name('clearance.approve');
    Route::post('/admin/barangay-clearance/{id}/reject', [ClearanceController::class, 'reject'])->name('clearance.reject');
    Route::delete('/admin/barangay-clearance/{id}', [ClearanceController::class, 'destroy'])->name('clearance.destroy');

    Route::get('/admin/barangay-clearance/generate-document', [ClearanceController::class, 'generate'])->name('clearance.document');

    //Incident Report
    Route::get('/admin/blotter', [IncidentReportController::class, 'index'])->name('blotter.index');
    Route::get('/admin/blotter/{id}', [IncidentReportController::class, 'show'])->name('blotter.show');
    Route::post('/admin/blotter/store', [IncidentReportController::class, 'store'])->name('blotter.store');
    Route::post('/admin/blotter/bulk-delete', [IncidentReportController::class, 'bulkDelete'])->name('blotter.bulkDelete');
    Route::post('/admin/blotter/export', [IncidentReportController::class, 'export'])->name('blotter.export');

    Route::put('/admin/blotter/{application}', [IncidentReportController::class, 'update'])->name('blotter.update');
    Route::post('/admin/blotter/{id}/approve', [IncidentReportController::class, 'approve'])->name('blotter.approve');
    Route::post('/admin/blotter/{id}/reject', [IncidentReportController::class, 'reject'])->name('blotter.reject');
    Route::delete('/admin/blotter/{id}', [IncidentReportController::class, 'destroy'])->name('blotter.destroy');
    Route::post('/admin/blotter/{id}/restore', [IncidentReportController::class, 'restore'])->name('blotter.restore');

    //Witness
    Route::post('/admin/blotter/{id}/witness', [WitnessController::class, 'store'])->name('witness.store');
    Route::delete('/admin/witness/{id}', [WitnessController::class, 'destroy'])->name('witness.destroy');

    //Announcements
    Route::get('/admin/announcements', [AdminAnnouncement::class, 'index'])->name('announcements.index');
    Route::get('/admin/announcements/create', [AdminAnnouncement::class, 'create'])->name('announcements.create');
    Route::post('/admin/announcements/store', [AdminAnnouncement::class, 'store'])->name('announcements.store');
    Route::get('/admin/announcements/{id}/edit', [AdminAnnouncement::class, 'edit'])->name('announcements.edit');
    Route::put('/admin/announcements/{id}', [AdminAnnouncement::class, 'update'])->name('announcements.update');
    Route::delete('/admin/announcements/{id}', [AdminAnnouncement::class, 'destroy'])->name('announcements.destroy');
    Route::post('/admin/announcements/bulk-delete', [AdminAnnouncement::class, 'bulkDelete'])->name('announcements.bulkDelete');
    Route::post('/announcements/{id}/toggle-feature', [AdminAnnouncement::class, 'toggleFeature'])->name('announcements.toggle-feature');


    // Events 
    Route::get('/admin/events', [EventController::class, 'index'])->name('events.index');
    Route::post('/admin/events/store', [EventController::class, 'store'])->name('events.store');
    Route::put('/admin/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/admin/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/admin/events/bulk-delete', [EventController::class, 'bulkDelete'])->name('events.bulkDelete');
    Route::post('/admin/events/{id}/duplicate', [EventController::class, 'duplicate'])->name('events.duplicate');
    Route::get('/admin/events/{id}', [EventController::class, 'show'])->name('events.show');

    // Projects routes
    Route::get('/admin/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/admin/projects/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/admin/admin/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/admin/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/admin/projects/bulk-delete', [ProjectController::class, 'bulkDelete'])->name('projects.bulkDelete');
    Route::get('/admin/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::patch('/admin/projects/{id}/progress', [ProjectController::class, 'updateProgress'])->name('projects.progress');

    // Route::post('/announcements-dashboard', [AnnouncementController::class, 'store']);

    //Request Status
    Route::get('/request-status', [RequestStatusController::class, 'index'])->name('request.index');
    Route::get('/request-view/{request_id}', [RequestStatusController::class, 'show'])->name('request.view');
    Route::get('/request-edit/{request_id}', [RequestStatusController::class, 'edit'])->name('request.edit');
    Route::put('/request-update/{request_id}', [RequestStatusController::class, 'update'])->name('request.update');
    Route::delete('/request-delete/{request_id}', [RequestStatusController::class, 'destroy'])->name('request.delete');
    Route::post('/request/send-email', [RequestStatusController::class, 'sendEmail'])->name('request.sendEmail');
    Route::post('/request/send-sms', [RequestStatusController::class, 'sendSMS'])->name('request.sendSMS');

    // Documents
    // Route::get('/documents', [DocumentController::class, 'document'])->name('barangay_system.docu');
    // Route::get('/generate-document', [DocumentController::class, 'generate'])->name('generate.document');

    // Route::get('/residentss', [AResidentsController::class, 'index'])->name('resident.index');
    // Route::get('/residents-view/{resident_id}', [AResidentsController::class, 'show'])->name('resident.view');
    // Route::get('/residents-edit/{resident_id}', [AResidentsController::class, 'edit'])->name('resident.edit');
    // Route::put('/residents-update/{resident_id}', [AResidentsController::class, 'update'])->name('resident.update');
    // Route::delete('/residents-delete/{resident_id}', [AResidentsController::class, 'destroy'])->name('resident.delete');

    // Incident Reports
    // Route::get('/incident-reports', [AIncidentReportsController::class, 'index'])->name('incident.index');
    // Route::get('/incident-view/{incident_id}', [AIncidentReportsController::class, 'show'])->name('incident.view');
    // Route::get('/incident-edit/{incident_id}', [AIncidentReportsController::class, 'edit'])->name('incident.edit');
    // Route::put('/incident-update/{incident_id}', [AIncidentReportsController::class, 'update'])->name('incident.update');
    // Route::delete('/incident-delete/{incident_id}', [AIncidentReportsController::class, 'destroy'])->name('incident.delete');
    // Route::post('/incident/send-email', [AIncidentReportsController::class, 'sendEmail'])->name('incident.sendEmail');
    // Route::post('/incident/send-sms', [AIncidentReportsController::class, 'sendSMS'])->name('incident.sendSMS');


    Route::get('/switch-language', [LanguageController::class, 'change'])->name('switch.language');
});