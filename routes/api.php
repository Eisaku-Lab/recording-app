<?php

use App\Http\Controllers\RecordingController;
use App\Http\Controllers\SummaryController;
use Illuminate\Support\Facades\Route;

Route::post('/recordings/upload', [RecordingController::class, 'upload']);
Route::get('/recordings', [RecordingController::class, 'list']);
Route::post('/recordings/{id}/summarize', [SummaryController::class, 'summarize']);
Route::delete('/recordings/{id}', [RecordingController::class, 'destroy']);
