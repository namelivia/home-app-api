<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class UsersController extends BaseController
{
	/**
	 * The model name related to the controller
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
		return response()->json(Auth::user());
	}

	/**
	 * Updates the firebase token
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
