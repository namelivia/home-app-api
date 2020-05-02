<?php

namespace App\Models;

class Permission extends BaseModel
{
    /**
     * Attributes that can be written by an API call.
     *
     * @var array
     */
    protected $fillable = [
        'key',
    ];

    /**
     * List of common error codes for the model.
     *
     * @return array
     */
    public function getErrorCodes()
    {
        return [
            'notFound' => ErrorCodes::PERMISSION_NOT_FOUND,
            'invalidData' => ErrorCodes::INVALID_PERMISSION,
            'failedToCreate' => ErrorCodes::FAILED_TO_CREATE_PERMISSION,
            'failedToUpdate' => ErrorCodes::FAILED_TO_UPDATE_PERMISSION,
            'failedToDelete' => ErrorCodes::FAILED_TO_DELETE_PERMISSION,
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
            'key' => 'required',
        ];
    }

}
