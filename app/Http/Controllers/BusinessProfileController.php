<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessProfileRequest;
use App\Models\BusinessProfile;

class BusinessProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->businessProfile;

        return view('business-profile.edit', [
            'profile' => $profile,
        ]);
    }

    public function store(BusinessProfileRequest $request)
    {
        $data = $request->validated();

        BusinessProfile::query()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return redirect()->route('dashboard')->with('status', 'Business profile saved.');
    }
}
