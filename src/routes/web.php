<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\MypageController;
use App\Http\Controllers\TaskController;

Route::get('/', function() { return view('top'); });
Route::get('/register', [RegisterController::class, 'create']);
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [LoginController::class, 'create']);
Route::post('/login', [LoginController::class, 'store']);
Route::get('/logout', [LogoutController::class, 'store']);
Route::get('/forgot_password', [ForgotPasswordController::class, 'show']);
Route::post('/forgot_password', [ForgotPasswordController::class, 'send']);
Route::get('/sent_forgot_password', function() { return view('auth.sent_forgot_password'); });
Route::get('/reset_password', [ResetPasswordController::class, 'show']);
Route::post('/reset_password', [ResetPasswordController::class, 'reset']);
Route::get('/success_reset_password', function() { return view('auth.success_reset_password'); });
Route::get('/mypage', [MypageController::class, 'index']);

Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::get('/tasks/calendar', [TaskController::class, 'calendar']);