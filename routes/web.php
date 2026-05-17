<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FundingOpportunityController;
use App\Http\Controllers\FundingRequestController;
use App\Http\Controllers\InvestorProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StartupProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/opportunities', [FundingOpportunityController::class, 'index'])->name('opportunities.index');
    Route::get('/opportunities/{opportunity}', [FundingOpportunityController::class, 'show'])->name('opportunities.show');

    Route::get('/requests', [FundingRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{fundingRequest}/messages', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/requests/{fundingRequest}/messages/partial', [MessageController::class, 'partial'])->name('messages.partial');
    Route::post('/requests/{fundingRequest}/messages', [MessageController::class, 'store'])->name('messages.store');
});

Route::middleware(['auth', 'role:startup'])->group(function () {
    Route::get('/startup/profile', [StartupProfileController::class, 'edit'])->name('startup.profile.edit');
    Route::put('/startup/profile', [StartupProfileController::class, 'update'])->name('startup.profile.update');
    Route::post('/requests', [FundingRequestController::class, 'store'])->name('requests.store');
});

Route::middleware(['auth', 'role:investor'])->group(function () {
    Route::get('/investor/profile', [InvestorProfileController::class, 'edit'])->name('investor.profile.edit');
    Route::put('/investor/profile', [InvestorProfileController::class, 'update'])->name('investor.profile.update');
    Route::get('/opportunities/create', [FundingOpportunityController::class, 'create'])->name('opportunities.create');
    Route::post('/opportunities', [FundingOpportunityController::class, 'store'])->name('opportunities.store');
    Route::patch('/requests/{fundingRequest}/status', [FundingRequestController::class, 'updateStatus'])->name('requests.status');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::patch('/admin/users/{user}/verify', [AdminController::class, 'verify'])->name('admin.users.verify');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
