<?php

namespace App\Http\Controllers;

use App\Enums\ProfileStatus;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get only ACTIVE profiles
        $profiles = ProfileResource::collection(Profile::with('user')->where('status', '=', ProfileStatus::ACTIVE->value)->get());
        return response()->json(['result' => $profiles], 200);
    }

    /**
     * Store a newly created resource in storage.
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
        return response()->json(['result' => Profile::find($profile->id)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        // Get the specified profile
        return response()->json(['result' => $profile], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        // Validate the request
        $validated = $request->validated();
        $profile->update($validated);
        return response()->json(['result' => Profile::find($profile->id)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        $deleted = $profile->delete();
        return response()->json(['result' => $deleted ? 'Profile has been deleted' : 'Profile has not been deleted'], 200);
    }
}
