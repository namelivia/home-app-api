<?php

namespace App\Models;

class GarmentType extends BaseModel
{
	const SHIRT = 1;
	const SHOE = 2;
	const PANTS = 3;
	const TSHIRT = 4;
	const COAT = 5;
	const PIJAMA = 6;
	const SOCKS = 7;
	const UNDERPANTS = 8;
	const SWEATER = 9;
	const JACKET = 10;
	const OTHER = 11;

	/**
	 * Whether or not this model is
	 * read-only or can be modified.
	 *
	 * @var boolean
	 */
	public static $readOnly = true;
}
