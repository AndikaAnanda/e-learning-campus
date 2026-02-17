<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:10240', // max 10MB
        ]);

        $filePath = $request->file('file')->store('submissions', 'public');

        // menghindari double submit
        $exist = Submission::where('assignment_id', $validated['assignment_id'])
                            ->where('student_id', $request->user()->id)
                            ->first();

        if ($exist) {
            return response()->json(['message' => 'Anda sudah mengumpulkan tugas ini'], 400);
        }

        $submission = Submission::create([
            'assignment_id' => $validated['assignment_id'],
            'student_id' => $request->user()->id,
            'file_path' => $filePath,
        ]);

        return response()->json([
            'message' => 'Tugas berhasil dikupulkan',
            'submission' => $submission
        ], 201);
    }
    

    public function grade(Request $request, $id){
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ]);

        $submission = Submission::findOrFail($id);
        $assignment = $submission->assignment;
        $course = $assignment->course;

        // cek apakah user adalah dosen dari course tersebut
        if ($request->user()->id !== $course->lecturer_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $submission->update([
            'score' => $validated['score']
        ]);
        

        return response()->json([
            'message' => 'Nilai berhasil diberikan',
            'submission' => $submission
        ]);
    }
}
