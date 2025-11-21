<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserCommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'description' => $comment->description,
                    'created_at' => $comment->created_at->toISOString(),
                    'user' => $comment->user ? [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                    ] : null,
                ];
            });

        return Inertia::render('user/Comment', [
            'comments' => $comments,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['description' => 'required|string|max:255']);

        Comment::create([
            'description' => $request->description,
            'id_user' => auth()->id(),
        ]);

        return back();
    }
}