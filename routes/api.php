<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TaskController;

Route::group(['prefix' => 'v1'], function () {
    require __DIR__ . '/api/v1/auth.php';
    require __DIR__ . '/api/v1/task.php';
});


