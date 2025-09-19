<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentsController;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get('/', [ResidentsController::class, 'index'])->name('barangay_system.index');
Route::get('/register', function() { return view('barangay_system.register'); })->name('register');
Route::post('/register', [ResidentsController::class, 'register_res'])->name('register.res');
Route::get('/login', function() { return view('barangay_system.login'); })->name('login');
Route::post('/login', [ResidentsController::class, 'login_res'])->name('login.res');
