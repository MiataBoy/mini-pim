<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\manager\createProfileRequest;
use App\Http\Requests\manager\updateProfileRequest;
use App\Models\manager\Profile;
use App\Models\manager\Right;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    /**
     * Indexes all users in the database and returns them to the client
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $profile = Profile::with('users')->get();
        return response()->json($profile);
    }

    /**
     * Gets new profile data from the client and saves it in the database
     * @param createProfileRequest $request
     * @return JsonResponse
     */
    public function create(createProfileRequest $request): jsonResponse
    {
        $profile = Profile::create([
            'name' => $request->input('name')
        ]);

        $rights = Right::all();

        $profile->rights()->attach($rights->pluck('id'));

        return response()->json(['message' => 'Succesfully created profile.']);
    }

    /**
     * When a user updates a profile on the client, the profile's new name (in Request) is saved by its id ($id)
     * @param createProfileRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function save(updateProfileRequest $request, $id): JsonResponse
    {
        $profile = Profile::find($id);
        if ($profile) {
            $profile->name = $request->input('name');
            $profile->save();

            return response()->json(['message' => 'Succesfully saved profile.']);
        }
        return response()->json(['message' => 'Failed to create profile...'], 500);
    }

    /**
     * When a user deletes a profile on the client, the profile will be identified by the id ($id) and be deleted from the database
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $profile = Profile::with('users')->find($id);

        if (Gate::denies('delete', $profile)) {
            abort(403, __('profiles.hasUsers'));
        }

        if ($profile) {
            $profile->rights()->detach();
            $profile->delete();

            return response()->json(['message' => 'Succesfully deleted profile.']);
        }

        return response()->json(['message' => 'Failed to create profile...'], 500);
    }
}
