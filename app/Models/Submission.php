<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_path',
        'score',
        'feedback',
        'graded_at'
    ];

    protected $casts = [
        'score' => 'integer',
        'graded_at' => 'datetime'
    ];
    // submission dimiliki oleh satu tugas
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    // submission dimiliki oleh satu mahasiswa
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
