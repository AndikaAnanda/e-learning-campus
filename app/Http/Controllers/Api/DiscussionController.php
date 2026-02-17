<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Models\Reply;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'content' => 'required|string',
        ]);

        $discussion = Discussion::create([
            'course_id' => $validated['course_id'],
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'Diskusi berhasil dibuat',
            'discussion' => $discussion
        ], 201);
    }

    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $discussion = Discussion::findOrFail($id);

        $reply = Reply::create([
            'discussion_id' => $discussion->id,
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'Balasan berhasil dibuat',
            'reply' => $reply
        ], 201);
    }

    public function show($id)
    {
        $discussion = Discussion::with('user', 'replies.user')->findOrFail($id);

        return response()->json([
            'discussion' => $discussion
        ]);
    }
}
