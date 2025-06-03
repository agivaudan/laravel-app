<?php

namespace App\Http\Controllers;

use App\Enums\ProfileStatus;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the active profiles
     * Additional fields will be displayed if the user is logged in
     */
    public function index()
    {
        // Get only ACTIVE profiles
        $profiles = ProfileResource::collection(Profile::with('user')->where('status', '=', ProfileStatus::ACTIVE->value)->get());
        return response()->json(['result' => $profiles], Response::HTTP_OK);
    }

    /**
     * Store a newly created profile in DB
     * 
     * @param StoreProfileRequest $request quick validator of the format of the request
     */
    public function store(StoreProfileRequest $request)
    {
        // Validate the request
        $validated = $request->validated();
        $validated['user_id'] = \Auth::user()->id;

        // If the profile has a picture, save its path
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('images/profiles', 'local');
            $validated['image'] = $path;
        }

        $profile = Profile::create($validated);
        return response()->json(['result' => Profile::find($profile->id)], Response::HTTP_CREATED);
    }

    /**
     * Display the specified profile
     * 
     * @param Profile $profile
     */
    public function show(Profile $profile)
    {
        // Get the specified profile
        return response()->json(['result' => $profile], Response::HTTP_OK);
    }

    /**
     * Update the specified profile in DB
     * 
     * @param UpdateProfileRequest $request quick validator of the format of the request
     * @param Profile $profile
     */
    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        // Validate the request
        $validated = $request->validated();
        $profile->update($validated);
        return response()->json(['result' => Profile::find($profile->id)], Response::HTTP_OK);
    }

    /**
     * Delete the specified profile from DB
     * 
     * @param Profile $profile
     */
    public function destroy(Profile $profile)
    {
        $deleted = $profile->delete();
        return response()->json(
            [
                'result' => $deleted ? 'Profile has been deleted' : 'Profile has not been deleted'
            ],
            Response::HTTP_OK
        );
    }
}
