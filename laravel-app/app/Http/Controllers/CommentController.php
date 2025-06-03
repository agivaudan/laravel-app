<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Profile;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments for a profile
     * 
     * @param Profile $profile
     */
    public function index(Profile $profile)
    {
        // Get all comments for the specified profile
        return response()->json(
            [
                'result' => Comment::where('profile_id', '=', $profile->id)->get()
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created comment for a profile in DB
     * 
     * @param StoreCommentRequest $request quick validator of the format of the request
     * @param Profile $profile
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

            // Since user already posted a comment for this profile, he cannot do it again
            if (!empty($comment)) {
               return response()->json(['result' => 'You already posted a comment for this profile'], Response::HTTP_FORBIDDEN);
            }
        }

        $comment = Comment::create($validated);

        return response()->json(['result' => $comment], Response::HTTP_CREATED);
    }

    /**
     * Display the specified comment
     * 
     * @param Comment $comment
     */
    public function show(Comment $comment)
    {
        // Get the specified comment
        return response()->json(['result' => $comment], Response::HTTP_OK);
    }

    /**
     * Update the specified comment for a profile in DB
     * 
     * @param StoreCommentRequest $request quick validator of the format of the request
     * @param Comment $comment
     */
    public function update(StoreCommentRequest $request, Comment $comment)
    {
        // Validate the request
        // Same FormRequest as the store method because only 1 field is checked
        $validated = $request->validated();
        $comment->update($validated);

        return response()->json(['result' => Comment::find($comment->id)], Response::HTTP_OK);    
    }

    /**
     * Delete the specified comment from DB
     * 
     * @param Comment $comment
     */
    public function destroy(Comment $comment)
    {
        $deleted = $comment->delete();

        return response()->json(
            [
                'result' => $comment ? 'Comment has been deleted' : 'Comment has not been deleted'
            ],
            Response::HTTP_OK
        );    
    }
}
