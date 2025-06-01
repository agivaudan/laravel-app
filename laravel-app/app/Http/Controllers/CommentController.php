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
        // Get all comments for the specified profile
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
        $validated['user_id'] = \Auth::user()->id;

        // If the user is an admin, check if they have already posted a comment for this profile
        if (\Auth::user()->type === "ADMIN") {
            $comment = Comment::where('profile_id', '=', $validated['profile_id'])
                ->where('user_id', '=', $validated['user_id'])
                ->first();

            if (!empty($comment)) {
               return response()->json(['result' => 'You already posted a comment for this profile'], 403);
            }
        }

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
    public function update(StoreCommentRequest $request, Comment $comment)
    {
        // Validate the request
        // Same FormRequest as the store method because only 1 field is checked
        $validated = $request->validated();
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
