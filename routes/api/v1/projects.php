<?php

use App\Http\Controllers\Api\v1\Project\ProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('projects')->middleware('auth:api', 'check.access')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::post('/', [ProjectController::class, 'store']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::patch('/{id}', [ProjectController::class, 'update']);
    Route::delete('/{id}', [ProjectController::class, 'destroy']);
});
