<?php

use App\Http\Controllers\RecordingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RecordingController::class, 'index']);
