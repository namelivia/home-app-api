<?php

namespace App\Models;

class Status extends BaseModel
{
	const OK = 1;
	const DAMAGED = 2;
	const DESTROYED = 3;
	const TRASHED = 4;

	/**
	 * Whether or not this model is
	 * read-only or can be modified.
	 *
	 * @var boolean
	 */
	public static $readOnly = true;
}
