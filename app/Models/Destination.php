<?php

namespace App\Models;

use App\Lib\Constants\ErrorCodes;

class Destination extends BaseModel
{
    /**
     * Attributes that can be written by an API call.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * List of common error codes for the model.
     *
     * @return array
     */
    public function getErrorCodes()
    {
        return [
            'notFound' => ErrorCodes::DESTINATION_NOT_FOUND,
            'invalidData' => ErrorCodes::INVALID_DESTINATION,
            'failedToCreate' => ErrorCodes::FAILED_TO_CREATE_DESTINATION,
            'failedToUpdate' => ErrorCodes::FAILED_TO_UPDATE_DESTINATION,
            'failedToDelete' => ErrorCodes::FAILED_TO_DELETE_DESTINATION,
        ];
    }

    /**
     * List of valuation rules for the model.
     *
     * @param int|null $entityId
     *
     * @return array
     */
    public function getValidationRules($entityId = null)
    {
        return [
            'name' => 'required',
        ];
    }
}
