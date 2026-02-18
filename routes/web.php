<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

use App\Http\Controllers\ResidentsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestStatusController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AIncidentReportsController;
use App\Http\Controllers\AResidentsController;
use App\Http\Controllers\IncidentReportController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PasswordResetController;

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
Route::get('/', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.index');
})->name('barangay_system.index');

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
    return view('barangay_system.barangay_officials');
})->name('officials');

// ------------------------------
// Community pages
// ------------------------------

Route::get('/announcements', [AnnouncementController::class,'index'])->name('announcements');
Route::get('/announcements/{slug}', [AnnouncementController::class,'show'])->name('announcements.show');

Route::get('/events-project', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.events');
})->name('events_project');

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

// ------------------------------
// Authenticated routes
// ------------------------------
Route::middleware('auth')->group(function () {

    Route::get('/logout', [ResidentsController::class, 'destroy'])->name('logout.res');

    // ------------------------------
    // Services pages
    // ------------------------------
    Route::get('/services', [RequestsController::class, 'service'])->name('barangay_system.services');

    Route::get('/indigency', function () { return view('barangay_system.certificate_indigency'); })->name('indigency');
    Route::get('/indigency-form', function () { return view('barangay_system.indigency_form'); })->name('indigency_form');
    Route::post('/indigency', [RequestsController::class, 'requestStore'])->name('indigency.req');

    Route::get('/jobseek', function () { return view('barangay_system.job_seeker'); })->name('jobseek');
    Route::post('/jobseek', [RequestsController::class, 'requestStore'])->name('job_seeker.req');

    Route::get('/residency', function () { return view('barangay_system.certificate_residency'); })->name('residency');
    Route::get('/residency-form', function () { return view('barangay_system.residency_form'); })->name('residency_form');
    Route::post('/residency-form', [RequestsController::class, 'requestStore'])->name('residency.req');

    Route::get('/clearance', function () { return view('barangay_system.clearance'); })->name('clearance');
    Route::get('/clearance-form', function () { return view('barangay_system.clearance_form'); })->name('clearance_form');
    Route::post('/clearance-form', [RequestsController::class, 'requestStore'])->name('clearance.req');

    // Route::get('/incident', [IncidentReportController::class, 'incident'])->name('incident');
    Route::get('/incident', function () { return view('barangay_system.incident'); })->name('incident');
    Route::get('/incident-form', function () { return view('barangay_system.incident_form'); })->name('incident_form');
    Route::post('/incident-form', [IncidentReportController::class, 'incidentStore'])->name('incident.add');

    // ------------------------------
    // Admin pages
    // ------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/api/forecast-requests', [DashboardController::class, 'forecastRequests']);

    Route::post('/announcements-dashboard', [AnnouncementController::class, 'store']);

    // Request Status
    Route::get('/request-status', [RequestStatusController::class, 'index'])->name('request.index');
    Route::get('/request-view/{request_id}', [RequestStatusController::class, 'show'])->name('request.view');
    Route::get('/request-edit/{request_id}', [RequestStatusController::class, 'edit'])->name('request.edit');
    Route::put('/request-update/{request_id}', [RequestStatusController::class, 'update'])->name('request.update');
    Route::delete('/request-delete/{request_id}', [RequestStatusController::class, 'destroy'])->name('request.delete');
    Route::post('/request/send-email', [RequestStatusController::class, 'sendEmail'])->name('request.sendEmail');
    Route::post('/request/send-sms', [RequestStatusController::class, 'sendSMS'])->name('request.sendSMS');

    // Documents
    Route::get('/documents', [DocumentController::class, 'document'])->name('barangay_system.docu');
    Route::get('/generate-document', [DocumentController::class, 'generate'])->name('generate.document');

    // Residents
    Route::get('/residents', [AResidentsController::class, 'index'])->name('resident.index');
    Route::get('/resident-view/{resident_id}', [AResidentsController::class, 'show'])->name('resident.view');
    Route::get('/resident-edit/{resident_id}', [AResidentsController::class, 'edit'])->name('resident.edit');
    Route::put('/resident-update/{resident_id}', [AResidentsController::class, 'update'])->name('resident.update');
    Route::delete('/resident-delete/{resident_id}', [AResidentsController::class, 'destroy'])->name('resident.delete');

    // Incident Reports
    Route::get('/incident-reports', [AIncidentReportsController::class, 'index'])->name('incident.index');
    Route::get('/incident-view/{incident_id}', [AIncidentReportsController::class, 'show'])->name('incident.view');
    Route::get('/incident-edit/{incident_id}', [AIncidentReportsController::class, 'edit'])->name('incident.edit');
    Route::put('/incident-update/{incident_id}', [AIncidentReportsController::class, 'update'])->name('incident.update');
    Route::delete('/incident-delete/{incident_id}', [AIncidentReportsController::class, 'destroy'])->name('incident.delete');
    Route::post('/incident/send-email', [AIncidentReportsController::class, 'sendEmail'])->name('incident.sendEmail');
    Route::post('/incident/send-sms', [AIncidentReportsController::class, 'sendSMS'])->name('incident.sendSMS');


    Route::get('/switch-language', [LanguageController::class, 'change'])->name('switch.language');
});