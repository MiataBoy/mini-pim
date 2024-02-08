<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\manager\createUserRequest;
use App\Http\Requests\manager\updateUserRequest;
use App\Models\manager\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Return a list with all registered users
     * @return JsonResponse
     */
    public function read_all(): JsonResponse
    {
        $users = User::with('profile')->get();
        return response()->json($users);
    }

    /**
     * Return the user identified by the given ID
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = User::with('profile')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * When a privileged user creates a new user account on the client, the user account is saved to the database with the data saved in Request
     * @param createUserRequest $request
     * @return JsonResponse
     */
    public function create(createUserRequest $request) {
        $user = new User();

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->company = $request->input('company');
        $user->profile_id = $request->input('profile_id');
        $user->email = $request->input('email');
        $user->locale = $request->input('locale');
        if ($request->input('defaultPage')) {
            $user->defaultPage = $request->input('defaultPage');
        }
        $user->password = Hash::make($request->input('password'));
        $user->remember_token = Str::random(10);

        $user->save();

        return response()->json(['message' => 'Succesfully created profile.']);
    }

    /**
     * When a privileged user updates a user account on the client, the user account is updated in the database with the data saved in Request
     * @param createUserRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function save(updateUserRequest $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->company = $request->input('company');
            $user->profile_id = $request->input('profile_id');
            $user->email = $request->input('email');
            $user->locale = $request->input('locale');
            if ($request->input('defaultPage')) {
                $user->defaultPage = $request->input('defaultPage');
            }
            if ($request->input('password')) {
                $user->password = Hash::make($request->input('password'));
            }
            $user->save();

            return response()->json(['message' => 'Succesfully saved user.']);
        }
        return response()->json(['message' => 'Failed to save user...'], 500);
    }

    /**
     *  When a privileged user deletes a user account on the client, the user account will be identified by the id ($id) and be deleted from the database
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = User::find($id);
        if (Gate::denies('delete', $user)) {
            abort(403, __('users.deleteOwn'));
        }

        if ($user) {
            $user->delete();

            return response(['message' => 'Succesfully deleted user.']);
        }

        return response(['message' =>'Failed to delete user...'], 500);
    }
}
