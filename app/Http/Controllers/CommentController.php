<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Movie $movie)
    {
        $request->validate([
            'content' => 'required|string|min:10|max:1000',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'commentable_type' => Movie::class,
            'commentable_id' => $movie->id,
            'content' => $request->content,
            'rating' => $request->rating,
            'is_approved' => true, // Auto-approve for now
        ]);

        return back()->with('success', 'Your review has been posted!');
    }
}