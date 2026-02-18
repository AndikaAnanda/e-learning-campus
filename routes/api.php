<?php

use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DiscussionController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SubmissionController;
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


        Route::post('/materials', [MaterialController::class, 'store']);

        Route::post('/assignments', [AssignmentController::class, 'store']);
        Route::post('/submissions/{id}/grade', [SubmissionController::class, 'grade']);
    });

    Route::middleware(['auth:sanctum', 'role:mahasiswa'])->group(function () {
        Route::post('/submissions', [SubmissionController::class, 'store']);
    });
    
    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll']);
    Route::get('/materials/{id}/download', [MaterialController::class, 'download']);

    Route::post('/discussions', [DiscussionController::class, 'store']);
    Route::post('/discussions/{id}/reply', [DiscussionController::class, 'reply']);
    Route::get('/discussions/{id}', [DiscussionController::class, 'show']);

    Route::get('/reports/courses', [ReportController::class, 'courseReport']);
    Route::get('/reports/assignments', [ReportController::class, 'assignmentsReport']);
    Route::get('/reports/students/{id}', [ReportController::class, 'studentReport']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
});