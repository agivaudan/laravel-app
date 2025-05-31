<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Profile;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Profile $profile)
    {
        // Get all comments
        return response()->json(['result' => Comment::where('profile_id', '=', $profile->id)->get()], 200);
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Profile $profile)
    {
        // Validate the request
        $validated = $request->validated();

        $validated['profile_id'] = $profile->id;
        $validated['user_id'] = 2; // TODO get currently connected auth
        $comment = Comment::create($validated);
        return response()->json(['result' => $comment], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        // Get the specified comment
        return response()->json(['result' => $comment], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Validate the request
        $validated = $request->validate([
            'content' => 'string|max:100',
        ]);
        $comment->update($validated);
        return response()->json(['result' => Comment::find($comment->id)], 200);
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $deleted = $comment->delete();
        return response()->json(['result' => $comment ? 'Comment has been deleted' : 'Comment has not been deleted'], 200);
    
    }
}
