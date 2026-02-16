<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];
    
    // tugas dimiliki oleh satu course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // tugas memiliki banyak submission
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
