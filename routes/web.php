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

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

//

Route::get('/switch-language', function (Request $request) {
    $lang = $request->query('lang');
    if (in_array($lang, ['en', 'tl'])) {
        Session::put('locale', $lang);
    }
    return redirect()->back();
})->name('switch.language');

Route::get('/', function () {
    App::setLocale(Session::get('locale', config('app.locale')));
    return view('barangay_system.index');
})->name('barangay_system.index');

//Resident Registration
Route::get('/register', function() { return view('barangay_system.register'); })->name('register');
Route::post('/register', [ResidentsController::class, 'register_res'])->name('register.res');

//Resident Login
Route::get('/login', function() { return view('barangay_system.login'); })->name('login');
Route::post('/login', [ResidentsController::class, 'login_res'])->name('login.res');

//Resident Logout
Route::get('/logout', [ResidentsController::class, 'destroy'])->name('logout.res');

//Services
Route::get('/services', [RequestsController::class, 'service'])->middleware('auth')->name('barangay_system.services');

//Services - Indigency
Route::get('/indigency', function() { return view('barangay_system.indigency'); })->name('indigency');
Route::post('/indigency', [RequestsController::class, 'requestStore'])->middleware('auth')->name('indigency.req');

//Services - First-time Job Seeker
Route::get('/jobseek', function() { return view('barangay_system.job_seeker'); })->name('jobseek');
Route::post('/jobseek', [RequestsController::class, 'requestStore'])->middleware('auth')->name('job_seeker.req');

//Services - Barangay Clearance
Route::get('/clearance', function() { return view('barangay_system.clearance'); })->name('clearance');
Route::post('/clearance', [RequestsController::class, 'requestStore'])->middleware('auth')->name('clearance.req');

//Incident Report
Route::get('/incident', [IncidentReportController::class, 'incident'])->middleware('auth')->name('barangay_system.incident');
Route::post('/incident', [IncidentReportController::class, 'incidentStore'])->middleware('auth')->name('incident.add');

//Admin Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/api/forecast-requests', [DashboardController::class, 'forecastRequests']);

//Admin Request Status
Route::get('/request-status', [RequestStatusController::class, 'index'])->name('request.index');
//View request
Route::get('/request-view/{request_id}', [RequestStatusController::class, 'show'])->name('request.view');
// Edit request
Route::get('/request-edit/{request_id}', [RequestStatusController::class, 'edit'])->name('request.edit');
// Update request
Route::put('/request-update/{request_id}', [RequestStatusController::class, 'update'])->name('request.update');
// Delete request
Route::delete('/request-delete/{request_id}', [RequestStatusController::class, 'destroy'])->name('request.delete');
// Notify resident
Route::post('/request/send-email', [RequestStatusController::class, 'sendEmail'])->name('request.sendEmail');
Route::post('/request/send-sms', [RequestStatusController::class, 'sendSMS'])->name('request.sendSMS');


//Admin Document Auto Format Word Document
Route::get('/documents', [DocumentController::class, 'document'])->name('barangay_system.docu');
Route::get('/generate-document', [DocumentController::class, 'generate'])->name('generate.document');

//Admin Residents Management
Route::get('/residents', [AResidentsController::class, 'index'])->name('resident.index');
//View resident
Route::get('/resident-view/{resident_id}', [AResidentsController::class, 'show'])->name('resident.view');
// Edit resident
Route::get('/resident-edit/{resident_id}', [AResidentsController::class, 'edit'])->name('resident.edit');
// Update resident
Route::put('/resident-update/{resident_id}', [AResidentsController::class, 'update'])->name('resident.update');
// Delete resident
Route::delete('/resident-delete/{resident_id}', [AResidentsController::class, 'destroy'])->name('resident.delete');

//Admin Incident Report Management
Route::get('/incident-reports', [AIncidentReportsController::class, 'index'])->name('incident.index');
//View incident
Route::get('/incident-view/{incident_id}', [AIncidentReportsController::class, 'show'])->name('incident.view');
// Edit incident
Route::get('/incident-edit/{incident_id}', [AIncidentReportsController::class, 'edit'])->name('incident.edit');
// Update incident
Route::put('/incident-update/{incident_id}', [AIncidentReportsController::class, 'update'])->name('incident.update');
// Delete incident
Route::delete('/incident-delete/{incident_id}', [AIncidentReportsController::class, 'destroy'])->name('incident.delete');
// Notify resident
Route::post('/incident/send-email', [AIncidentReportsController::class, 'sendEmail'])->name('incident.sendEmail');
Route::post('/incident/send-sms', [AIncidentReportsController::class, 'sendSMS'])->name('incident.sendSMS');
