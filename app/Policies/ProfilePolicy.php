<?php

namespace App\Policies;

use App\Models\manager\Profile;
use App\Models\manager\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Profile $profile)
    {
        // Deny deletion if the profile has associated users
        return $profile->users->isEmpty();
    }
}
