<?php

// app/Policies/UserPolicy.php

namespace App\Policies;

use App\Models\manager\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function delete(User $authenticatedUser, User $targetUser)
    {
        return $authenticatedUser->id !== $targetUser->id;
    }
}
