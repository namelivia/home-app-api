<?php

namespace App\Models;

use App\Lib\Constants\ErrorCodes;

class SpendingCategory extends BaseModel
{
	/**
	 * Overriding the default table name.
	 *
	 * @var string
	 */
	protected $table = 'spending_categories';

	/**
	 * Attributes that can be written by an API call.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name'
	];

	/**
	 * List of common error codes for the model.
	 *
	 * @return array
	 */
	public function getErrorCodes()
	{
		return [
			'notFound' => ErrorCodes::SPENDING_CATEGORY_NOT_FOUND,
			'invalidData' => ErrorCodes::INVALID_SPENDING_CATEGORY,
			'failedToCreate' => ErrorCodes::FAILED_TO_CREATE_SPENDING_CATEGORY,
			'failedToUpdate' => ErrorCodes::FAILED_TO_UPDATE_SPENDING_CATEGORY,
			'failedToDelete' => ErrorCodes::FAILED_TO_DELETE_SPENDING_CATEGORY
		];
	}

	/**
	 * List of valuation rules for the model.
	 *
	 * @param integer|null $entityId
	 * @return array
	 */
	public function getValidationRules($entityId = null)
	{
		return [
			'name' => 'required'
		];
	}
}
