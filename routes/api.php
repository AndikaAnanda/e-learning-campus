<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\MaterialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function (){
    Route::get('/courses', [CourseController::class, 'index']);

    Route::middleware('role:dosen')->group(function () {
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    });

    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll']);
});

Route::middleware('auth:sanctum', 'role:dosen')->group(function () {
    Route::post('/materials', [MaterialController::class, 'store']);
});

Route::middleware('auth:sanctum')->get('/materials/{id}/download', [MaterialController::class, 'download']);