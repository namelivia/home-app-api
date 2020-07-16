<?php

namespace App\Models;

use App\Lib\Constants\ErrorCodes;
use App\Models\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Builder;

class Litem extends BaseModel
{
    use ImageTrait;

    /**
     * Attributes that can be written by an API call.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'destination_id',
        'is_container',
    ];

    protected $appends = [
        'file_url',
        'thumb_url',
        'location',
    ];

    /**
     * Get the list of filters for the model.
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            //:scope                            :params
            ['ForParentIds',                     'parent_id'],
            ['ForDestinationIds',                 'destination_id'],
            ['RootLitems',                          'root'],
            ['ForSearchTerm',                     'search'],
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
            'notFound' => ErrorCodes::LITEM_NOT_FOUND,
            'invalidData' => ErrorCodes::INVALID_LITEM,
            'failedToCreate' => ErrorCodes::FAILED_TO_CREATE_LITEM,
            'failedToUpdate' => ErrorCodes::FAILED_TO_UPDATE_LITEM,
            'failedToDelete' => ErrorCodes::FAILED_TO_DELETE_LITEM,
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
            'is_container' => 'required|boolean',
            'parent_id' => 'nullable|exists:litems,id',
            'destination_id' => 'nullable|exists:destinations,id',
        ];
    }

    /**
     * Returns all items with the given parent ids.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param  int|array $parentIds
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForParentIds(Builder $query, $parentIds)
    {
        $parentIds = is_array($parentIds) ? $parentIds : [$parentIds];

        return $query->whereIn('parent_id', $parentIds);
    }

    /**
     * Returns all items with the given destination ids.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param  int|array $destinationIds
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDestinationIds(Builder $query, $destinationIds)
    {
        $destinationIds = is_array($destinationIds) ? $destinationIds : [$destinationIds];

        return $query->whereIn('destination_id', $destinationIds);
    }

    public function scopeForSearchTerm(Builder $query, array $searchTerm)
    {
        $searchTerm = $searchTerm[0];

        return $query->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%')
          ->orWhere('description', 'like', '%' . $searchTerm . '%');
        });
    }

    /**
     * Returns all items with no parents.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool $apply
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRootLitems(Builder $query, $apply)
    {
        if ($apply) {
            return $query->where('parent_id', null);
        }

        return $this;
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getLocationAttribute()
    {
        $location = collect([]);
        //TODO: This can get super slow
        /*$pointer = $this->parent;
        while (!is_null($pointer)) {
            $location->prepend($pointer);
            $pointer = $pointer->parent;
        }*/
        return $location;
    }
}
