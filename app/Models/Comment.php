<?php

namespace App\Models;

use App\Lib\Constants\ErrorCodes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Comment extends BaseModel
{
    /**
     * Attributes that can be written by an API call.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'user_id',
        'commentable_id',
        'commentable_type',
    ];

    public function getFilters()
    {
        return [
            ['ForCommentableId',  'commentable_id'],
            ['ForCommentableType',  'commentable_type'],
        ];
    }

    /**
     * List of common error codes for the model.
     *
     * @return array
     */
    public function getErrorCodes()
    {
        return [
            'notFound' => ErrorCodes::COMMENT_NOT_FOUND,
            'invalidData' => ErrorCodes::INVALID_COMMENT,
            'failedToCreate' => ErrorCodes::FAILED_TO_CREATE_COMMENT,
            'failedToUpdate' => ErrorCodes::FAILED_TO_UPDATE_COMMENT,
            'failedToDelete' => ErrorCodes::FAILED_TO_DELETE_COMMENT,
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
            'content' => 'required',
        ];
    }

    /**
     * This hook is called before data is inserted.
     *
     * @param	array $data
     */
    public function beforeInsert(array &$data)
    {
        $data['user_id'] = Auth::id();
        $data['commentable_type'] = 'App\\Models\\' . $data['commentable_type'];
    }

    public function scopeForCommentableId(Builder $query, array $commentableIds)
    {
        return $query->where('commentable_id', $commentableIds[0]);
    }

    public function scopeForCommentableType(Builder $query, array $commentableTypes)
    {
        return $query->where('commentable_type', 'App\\Models\\' . $commentableTypes[0]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function litems()
    {
        return $this->morphedByMany(Litem::class, 'commentable');
    }
}
