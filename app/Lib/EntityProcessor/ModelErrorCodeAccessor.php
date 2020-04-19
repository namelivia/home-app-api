<?php

namespace App\Lib\EntityProcessor;

use App\Models\BaseModel;
use Throwable;
use Exception;

class ModelErrorCodeAccessor
{
	/**
	 * Gets the desired error code from the model error codes.
	 *
	 * @param App\Models\BaseModel $model
	 * @param String $key
	 * @throws Exception if the model has not defined error
	 * for the key.
	 * @return int
	 */
	public function getModelErrorCode(BaseModel $model, String $key)
	{
		try {
			$errorCodes = $model->getErrorCodes();
			return $errorCodes[$key];
		} catch (Throwable $e) {
			throw new Exception('The model '. get_class($model) .' has no defined error code for the key ' . $key);
		}
	}
}
