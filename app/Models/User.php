<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Lib\Constants\ErrorCodes;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class User extends BaseModel implements
	AuthenticatableContract,
	AuthorizableContract,
	CanResetPasswordContract
{
	use HasApiTokens, Notifiable, Authenticatable, Authorizable, CanResetPassword;

	protected $fillable = [
		'place_id',
		'username',
		'password',
		'firebase_token',
		'email'
	];
	protected $hidden = ['password', 'firebase_token'];

	/**
	 * Sets the field for passport login as the username, not the email.
	 *
	 * @param string $username
	 * @return App\Models\User
	 */
	public function findForPassport(string $username)
	{
		return $this->where('username', $username)
			->first();
	}

	public function setPasswordAttribute($value)
	{
		if (\Hash::needsRehash($value)) {
			$this->attributes['password'] = ($value === null) ? null : \Hash::make($value);
		} else {
			$this->attributes['password'] = $value;
		}
	}

}
