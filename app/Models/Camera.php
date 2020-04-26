<?php

namespace App\Models;

use App\Lib\Constants\ErrorCodes;
use App\Models\Traits\ImageTrait;

class Camera extends BaseModel
{
    use ImageTrait;

    /**
     * Attributes that can be written by an API call.
     *
     * @var array
     */
    protected $fillable = [
        'created_at',
    ];

    protected $appends = [
        'file_url',
        'thumb_url',
    ];

    /**
     * List of common error codes for the model.
     *
     * @return array
     */
    public function getErrorCodes()
    {
        return [
            'notFound' => ErrorCodes::CAMERA_NOT_FOUND,
            'invalidData' => ErrorCodes::INVALID_CAMERA,
            'failedToCreate' => ErrorCodes::FAILED_TO_CREATE_CAMERA,
            'failedToUpdate' => ErrorCodes::FAILED_TO_UPDATE_CAMERA,
            'failedToDelete' => ErrorCodes::FAILED_TO_DELETE_CAMERA,
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
            //
        ];
    }

    /**
     * This hook is called before data is inserted.
     *
     * @param	array $data
     */
    public function beforeInsert(array &$data)
    {
        //Notification
    }
}
