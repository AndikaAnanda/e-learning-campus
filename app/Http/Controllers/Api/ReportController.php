<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use App\Models\User;
class ReportController extends Controller
{
    // return jumlah mahasiswa per course
    public function courseReport(){
        $courses = Course::withCount('students')->get();

        return response()->json([
            $courses
        ]);
    }

    // statistik tugas
    public function assignmentsReport(){
        $assignments = Assignment::withCount('submissions')->withAvg('submissions', 'score')->get();

        return response()->json([
            $assignments
        ]);
    }

    // statistik per mahasiswa
    public function studentReport($id){
        $student = User::where('role', 'mahasiswa')
            ->withCount('submissions')
            ->findOrFail($id);

        $averageScore = Submission::where('student_id', $id)
            ->avg('score');

        return response()->json([
            'student' => $student,
            'total_submissions' => $student->submissions_count,
            'average_score' => round($averageScore, 2),
        ]);
    }

}
