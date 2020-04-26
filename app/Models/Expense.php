<?php

namespace App\Models;

use App\Lib\Constants\ErrorCodes;
use App\Models\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Builder;

class Expense extends BaseModel
{
    use ImageTrait;

    /**
     * Attributes that can be written by an API call.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
        'owner_id',
        'spending_category_id',
    ];

    protected $appends = [
        'file_url',
        'thumb_url',
    ];

    public function getFilters()
    {
        return [
            ['ForTimeAfter',        'after'],
            ['ForTimeBefore',       'before'],
            ['ForOwnerIds',       'owner_id'],
            ['ForSpendingCategoryIds',       'spending_category_id'],
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
            'notFound' => ErrorCodes::EXPENSE_NOT_FOUND,
            'invalidData' => ErrorCodes::INVALID_EXPENSE,
            'failedToCreate' => ErrorCodes::FAILED_TO_CREATE_EXPENSE,
            'failedToUpdate' => ErrorCodes::FAILED_TO_UPDATE_EXPENSE,
            'failedToDelete' => ErrorCodes::FAILED_TO_DELETE_EXPENSE,
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
            'value' => 'required|numeric',
            'owner_id' => 'required|exists:owners,id',
            'spending_category_id' => 'required|exists:spending_categories,id',
        ];
    }

    /**
     * Hook called before data is saved in an insert.
     *
     * @param	array $data
     *
     * @return 	null|Illuminate\Http\JsonResponse
     */
    public function beforeValidate(array &$data)
    {
        $data['spending_category_id'] = SpendingCategory::firstOrCreate(
            ['name' => $data['spending_category']]
        )->id;
    }

    public function scopeForTimeAfter(Builder $query, array $time)
    {
        return $query->where('created_at', '>=', $time);
    }

    public function scopeForTimeBefore(Builder $query, array $time)
    {
        return $query->where('created_at', '<=', $time);
    }

    public function scopeForOwnerIds(Builder $query, array $ownerIds)
    {
        return $query->whereIn('owner_id', $ownerIds);
    }

    public function scopeForSpendingCategoryIds(Builder $query, array $spendingCategoryIds)
    {
        return $query->whereIn('spending_category_id', $spendingCategoryIds);
    }

    public function spendingCategory()
    {
        return $this->belongsTo(SpendingCategory::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function owner1Total()
    {
        return $this->where('owner_id', Owner::OWNER1)->sum('value');
    }

    public function owner2Total()
    {
        return $this->where('owner_id', Owner::OWNER2)->sum('value');
    }
}
