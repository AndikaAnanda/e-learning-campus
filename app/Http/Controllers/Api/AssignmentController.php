<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Notifications\NewAssignmentNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class AssignmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $course = Course::findOrFail($validated['course_id']);

        // cek apakah user adalah dosen dari course tersebut
        if ($request->user()->id !== $course->lecturer_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // buat assignment
        $assignment = Assignment::create($validated);

        // kirim notifikasi ke semua mahasiswa yang enroll di course tersebut
        $students = $course->students;
        foreach ($students as $student) {
            $student->notify(new NewAssignmentNotification($assignment));
        }

        return response()->json([
            'message' => 'Tugas dikirim, notifikasi sudah dikirim ke ' . $students->count() . ' mahasiswa',
            'assignment' => $assignment
        ], 201);
    }   
}
