<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\AndroidManagement\EnterpriseController;

Route::get('/health', HealthController::class);
Route::get('/android-management/enterprise/callback', [EnterpriseController::class, 'callback'])->name('android-management.enterprise.callback');
