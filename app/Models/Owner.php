<?php

namespace App\Models;

class Owner extends BaseModel
{
	const OWNER1 = 1;
	const OWNER2 = 2;

	/**
	 * Whether or not this model is
	 * read-only or can be modified.
	 *
	 * @var boolean
	 */
	public static $readOnly = true;
}
