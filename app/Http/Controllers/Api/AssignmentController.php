<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;

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

        // Create assignment
        $assignment = Assignment::create($validated);

        return response()->json([
            'message' => 'Assignment created successfully',
            'assignment' => $assignment
        ], 201);
    }   
}
