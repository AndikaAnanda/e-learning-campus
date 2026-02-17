<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with('lecturer')->get();
        return response()->json($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));

        $validated['lecturer_id'] = $request->user()->id;
        $course = Course::create($validated);
        return response()->json([
            'message' => 'Course berhasil dibuat',
            'course' => $course,
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $course = Course::findorFail($id);

        // hanya dosen yang bisa edit course
        if ($request->user()->id !== $course->lecturer_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate(([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));

        $course->update($validated);
        
        return response()->json([
            'message' => 'Course berhasil diupdate',
            'course' => $course,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $course = Course::findorFail($id);

        // hanya dosen yang bisa delete course
        if ($request->user()->id !== $course->lecturer_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course->delete();
        return response()->json(['message' => 'Course berhasil dihapus']);
    }

    public function enroll(Request $request, $id)
    {
        $course = Course::findorFail($id);

        $user = $request->user();

        // hanya mahasiswa yang bisa enroll
        if ($request->user()->role !== 'mahasiswa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course->students()->syncWithoutDetaching($user->id);

        return response()->json(['message' => 'Berhasil enroll ke course']);
    }
}
