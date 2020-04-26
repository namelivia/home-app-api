<?php

namespace App\Models;

use App\Lib\Constants\ErrorCodes;
use App\Models\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Builder;

class Garment extends BaseModel
{
    use ImageTrait;

    /**
     * Attributes that can be written by an API call.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'garment_type_id',
        'color_id',
        'status_id',
        'place_id',
    ];

    public function getFilters()
    {
        return [
            ['ForGarmentTypeIds',	'garment_type_id'],
            ['ForColorIds',		'color_id'],
            ['ForPlaceIds',		'place_id'],
            ['ForStatusIds',	'status_id'],
            ['ForSearchTerm',	'search'],
        ];
    }

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
            'notFound' => ErrorCodes::GARMENT_NOT_FOUND,
            'invalidData' => ErrorCodes::INVALID_GARMENT,
            'failedToCreate' => ErrorCodes::FAILED_TO_CREATE_GARMENT,
            'failedToUpdate' => ErrorCodes::FAILED_TO_UPDATE_GARMENT,
            'failedToDelete' => ErrorCodes::FAILED_TO_DELETE_GARMENT,
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
            'garment_type_id' => 'required|exists:garment_types,id',
            'color_id' => 'required|exists:colors,id',
            'status_id' => 'required|exists:statuses,id',
            'place_id' => 'required|exists:places,id',
        ];
    }

    public function scopeForGarmentTypeIds(Builder $query, $garmentTypeIds)
    {
        $garmentTypeIds = is_array($garmentTypeIds) ? $garmentTypeIds : [$garmentTypeIds];

        return $query->whereIn('garment_type_id', $garmentTypeIds);
    }

    public function scopeForColorIds(Builder $query, $colorIds)
    {
        $colorIds = is_array($colorIds) ? $colorIds : [$colorIds];

        return $query->whereIn('color_id', $colorIds);
    }

    public function scopeForStatusIds(Builder $query, $statusIds)
    {
        $statusIds = is_array($statusIds) ? $statusIds : [$statusIds];

        return $query->whereIn('status_id', $statusIds);
    }

    public function scopeForClean(Builder $query)
    {
        return $query->where('clean', 1);
    }

    public function scopeForSearchTerm(Builder $query, array $searchTerm)
    {
        $searchTerm = $searchTerm[0];

        return $query->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        });
    }

    public function scopeForPlaceIds(Builder $query, $placeIds)
    {
        $placeIds = is_array($placeIds) ? $placeIds : [$placeIds];

        return $query->whereIn('place_id', $placeIds);
    }
}
