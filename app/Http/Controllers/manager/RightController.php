<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\manager\Profile;
use App\Models\manager\Right;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RightController extends Controller
{
    /**
     * This takes all rights and returns them to the front-end in a customized format
     * @return array
     */
    public function index(): array
    {
        $rights = Right::all();

        // Categorize rights by their categories
        $categorizedRights = [];
        foreach ($rights as $right) {
            if (!isset($categorizedRights)) {
                $categorizedRights = [];
            }

            $categorizedRights[] = [
                'id' => $right->id,
                'name' => $right->name,
                'category' => $right->category,
                'right_assigned' => false,
            ];
        }

        return $categorizedRights;
    }

    /**
     * This returns all profiles each together with the rights and the values per right per profile
     * @return JsonResponse
     */
    public function profileWithRights(): Collection|\Illuminate\Support\Collection
    {
        $profiles = Profile::with('rights')->get();

        // Process profile data with associated rights and right_assigned property
        $processedProfiles = $profiles->map(function ($profile) {
            $categorizedRights = [];

            // Process each right associated with the profile
            $profile->rights->each(function ($right) use (&$categorizedRights) {
                $categoryName = $right->category;

                if (!isset($categorizedRights)) {
                    $categorizedRights = [];
                }

                $categorizedRights[] = [
                    'id' => $right->id,
                    'name' => $right->name,
                    'right_assigned' => $right->pivot->right_assigned == 1,
                ];
            });
            return [
                'id' => $profile->id,
                'name' => $profile->name,
                'users' => $profile->users,
                'rights' => $categorizedRights,
            ];
        });

        return $processedProfiles;
    }

    /**
     * This saves the given profile's right in the pivot table together with the value and profile's ID
     * @param Request $request
     * @return void
     */
    public function saveRight(Request $request): void
    {
        $profile = Profile::find($request->input('profile'));
        $right = $request->input('right_id');
        $profile->rights()->updateExistingPivot($right, [
            'right_assigned' => $request->input('right_value')
        ]);
    }
}
