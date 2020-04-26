<?php

namespace App\Lib;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class RequestHelper
{
    protected $requestContextInfo = [
        'page' => null,
        'pageSize' => null,
        'sortField' => null,
        'sortDirection' => null,
        'with' => [],
        'withCount' => [],
    ];

    /**
     * Returns an array of request data.
     *
     * @param  array  $additionalData
     *
     * @return array
     */
    public function requestData()
    {
        return array_diff_assoc(Request::all(), Request::query());
    }

    /**
     * Returns an array of query data.
     *
     * @return array
     */
    public function queryData()
    {
        $queryData = Request::query();
        $result = $this->requestContextInfo;
        foreach ($result as $key => &$value) {
            $value = isset($queryData[Str::snake($key)]) ? $queryData[Str::snake($key)] : $value;
        }

        return $result;
    }

    /**
     * Returns an array of the filters of the request.
     *
     * @return array
     */
    public function getFilters()
    {
        $result = [];
        $queryData = Request::query();
        foreach ($queryData as $key => $value) {
            if (!in_array($key, array_keys($this->requestContextInfo))) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
