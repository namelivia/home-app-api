<?php

namespace App\Models;

class Place extends BaseModel
{
	const PLACE1 = 1;
	const PLACE2 = 2;
	const PLACE3 = 3;

	/**
	 * Whether or not this model is
	 * read-only or can be modified.
	 *
	 * @var boolean
	 */
	public static $readOnly = true;
}
