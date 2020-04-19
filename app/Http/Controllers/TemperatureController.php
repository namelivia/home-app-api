<?php

namespace App\Http\Controllers;

use App\Models\Temperature;

class TemperatureController extends BaseController
{
	/**
	 * Corresponding model name.
	 *
	 * @var App\Models\Temperature
	 */
	protected $modelName = Temperature::class;

	public function getCurrent()
	{
		return response()->json(
			$this->model->lastRecord()
		);
	}
}
