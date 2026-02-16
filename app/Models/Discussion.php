<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'content',
    ];
    // diskusi dimiliki oleh satu course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // diskusi dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // diskusi memiliki banyak reply
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
