<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');
Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('otp.verify');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.check');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AssetController::class, 'dashboard'])->name('dashboard');
    Route::get('/assets/groups', [AssetController::class, 'groups'])->name('assets.groups');
    Route::get('/assets/categories', [AssetController::class, 'categories'])->name('assets.categories');
    
    Route::get('/assets/{asset}/documents/create', [AssetController::class, 'createDocument'])->name('assets.documents.create');
    Route::post('/assets/{asset}/documents', [AssetController::class, 'storeDocument'])->name('assets.documents.store');

    Route::resource('assets', AssetController::class);

    Route::get('/reports/summary', [ReportController::class, 'summary'])->name('reports.summary');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
    // Centralized Document Upload
    Route::get('/documents/upload', [ReportController::class, 'create'])->name('documents.create');
    Route::post('/documents', [ReportController::class, 'store'])->name('documents.store');
    
    Route::post('/documents/{document}/approve', [ReportController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [ReportController::class, 'reject'])->name('documents.reject');
});

