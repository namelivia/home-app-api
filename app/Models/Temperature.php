<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Temperature extends BaseModel
{
    public static $readOnly = true;
    protected $connection = 'temperature-database';
    protected $table = 'temperature';

    public function getFilters()
    {
        return [
            ['ForTimeAfter',        'after'],
            ['ForTimeBefore',       'before'],
        ];
    }

    public function lastRecord()
    {
        return $this->orderBy('id', 'desc')->first();
    }

    public function scopeForTimeAfter(Builder $query, array $time)
    {
        return $query->where('time', '>=', $time);
    }

    public function scopeForTimeBefore(Builder $query, array $time)
    {
        return $query->where('time', '<=', $time);
    }
}
