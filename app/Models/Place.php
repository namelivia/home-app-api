<?php

namespace App\Models;

class Place extends BaseModel
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
            'notFound' => ErrorCodes::PLACE_NOT_FOUND,
            'invalidData' => ErrorCodes::INVALID_PLACE,
            'failedToCreate' => ErrorCodes::FAILED_TO_CREATE_PLACE,
            'failedToUpdate' => ErrorCodes::FAILED_TO_UPDATE_PLACE,
            'failedToDelete' => ErrorCodes::FAILED_TO_DELETE_PLACE,
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
