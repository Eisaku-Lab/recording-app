<?php

use App\Http\Controllers\RecordingController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ログイン画面
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

// ログアウト
Route::post('/logout', [AuthController::class, 'logout']);

// 録音一覧（ログイン必須）
Route::get('/', [RecordingController::class, 'index']);
