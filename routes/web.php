<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\DashboardController;

// Student views mapped to Laravel Blade views
Route::get('/', function () {
    return view('landing');
});

Route::get('/index.html', function () {
    return redirect('/');
});

Route::get('/dashboard.html', function () {
    return view('student.dashboard');
});

Route::get('/notifications.html', function () {
    return view('student.notifications');
});

Route::get('/simulation.html', function () {
    return view('student.simulation');
});

Route::get('/result.html', function () {
    return view('student.result');
});

// Authentication routes
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Protected Dashboard routes (Protected by auth and CheckDosenRole middleware)
Route::middleware(['auth', \App\Http\Middleware\CheckDosenRole::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/students', [DashboardController::class, 'students'])->name('dashboard.students');
    Route::get('/dashboard/pre-tests', [DashboardController::class, 'preTests'])->name('dashboard.pre-tests');
    Route::get('/dashboard/simulations', [DashboardController::class, 'simulations'])->name('dashboard.simulations');
});
