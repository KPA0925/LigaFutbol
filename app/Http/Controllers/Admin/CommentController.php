<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user')->latest()->get();

        return Inertia::render('admin/Comments/Index', [
            'comments' => $comments,
        ]);
    }

    public function create()
    {
        $users = User::select('id', 'name')->get();

        return Inertia::render('admin/Comments/Create', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'id_user' => 'required|exists:users,id',
        ]);

        Comment::create($validated);

        return redirect()->route('admin.comments.index')->with('success', 'Comentario creado correctamente.');
    }

    public function edit(Comment $comment)
    {
        $users = User::select('id', 'name')->get();

        return Inertia::render('admin/Comments/Edit', [
            'comment' => $comment,
            'users' => $users,
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'id_user' => 'required|exists:users,id',
        ]);

        $comment->update($validated);

        return redirect()->route('admin.comments.index')->with('success', 'Comentario actualizado correctamente.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('admin.comments.index')->with('success', 'Comentario eliminado correctamente.');
    }
}
