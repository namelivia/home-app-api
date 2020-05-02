<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends BaseController
{
    /**
     * The model name related to the controller.
     *
     * @var App\Models\User
     */
    protected $modelName = User::class;

    /**
     * Return the current authenticated user info.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function currentUserInfo()
    {
		$currentUser = Auth::user();
		return response()->json([
			'user' => $currentUser,
			'permissions' => $currentUser->permissions->all(),
		]);
    }

    /**
     * Updates the firebase token.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function updateFirebaseToken()
    {
        $user = Auth::user();
        $user->firebase_token = $this->requestHelper->requestData()['token'];
        $user->save();

        return response()->json(Auth::user());
    }
}
