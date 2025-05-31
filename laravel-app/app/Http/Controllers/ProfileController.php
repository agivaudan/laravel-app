<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\StoreProfileRequest;
use App\Enums\ProfileStatus;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get only ACTIVE profiles
        // return response()->json(['result' => Profile::all()], 200);
        return response()->json(['result' => Profile::where('status', '=', ProfileStatus::ACTIVE->value)->get()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request)
    {
        // Validate the request
        $validated = $request->validated();
        $validated['user_id'] = 2; // TODO get currently connected auth
        $profile = Profile::create($validated);
        return response()->json(['result' => $profile], 201);
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
    public function update(Request $request, Profile $profile)
    {
        // Validate the request
        $validated = $request->validate([
            'last_name' => 'string|max:50',
            'first_name'=> 'string|max:50',
        ]);
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
