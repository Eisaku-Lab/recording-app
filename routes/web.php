<?php

use App\Http\Controllers\RecordingController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ↓ 一時的なセットアップ用ルート（後で削除する）
Route::get('/setup-admin', function () {
    App\Models\User::create([
        'name'      => '管理者',
        'email'     => 'admin@example.com',
        'password'  => bcrypt('password123'),
        'kanri_flg' => 1,
    ]);
    return '管理者ユーザーを作成しました！';
});

// ログイン画面
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

// ログアウト
Route::post('/logout', [AuthController::class, 'logout']);

// 録音一覧（ログイン必須）
Route::get('/', [RecordingController::class, 'index']);
