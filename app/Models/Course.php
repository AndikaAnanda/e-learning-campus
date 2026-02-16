<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'lecturer_id',
    ];

    // course dimiliki oleh satu dosen
    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    // course memiliki banyak mahasiswa
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'student_id')->withTimestamps();
    }

    // course memiliki banyak materi
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    // course memiliki banyak assignment
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    // course memiliki banyak diskusi
    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }
}
