<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// channel untuk diskusi di course tertentu
Broadcast::channel('course.{courseId}', function ($user, $courseId) {
    // Cek apakah user enrolled di course ini atau user adalah dosen
    return $user->courses()->where('course_id', $courseId)->exists() || $user->role === 'dosen';
});

// channel untuk diskusi tertentu
Broadcast::channel('discussion.{discussionId}', function ($user, $discussionId) {
    return true;
});


