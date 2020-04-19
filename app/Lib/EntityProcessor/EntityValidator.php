<?php

namespace App\Lib\EntityProcessor;

use Illuminate\Support\Facades\Validator;
use App\Lib\ValidatorHelper;
use App\Models\BaseModel;

class EntityValidator
{

	protected $validatorHelper;
	protected $modelErrorCodeAccessor;

	public function __construct()
	{
		$this->validatorHelper = app()->make(ValidatorHelper::class);
		$this->modelErrorCodeAccessor = app()->make(ModelErrorCodeAccessor::class);
	}

	/**
	 * Checks the validation rules on the data by
	 * consulting the validation rules from the model.
	 * Returns an error array if fails.
	 *
	 * @param array $data
	 * @param App\Models\BaseModel $model
	 * @return mixed
	 */
	public function validateEntity(array $data, BaseModel $model)
	{
		$modelId = isset($model->id) ? $model->id : null;
		$validator = Validator::make(
			$data,
			$model->getValidationRules($modelId)
		);
		if ($validator->fails()) {
			return [
				'code' => $this->modelErrorCodeAccessor->getModelErrorCode($model, 'invalidData'),
				'data' => $this->validatorHelper->parseValidationErrors($validator->errors()->getMessages())
			];
		}
	}
}
