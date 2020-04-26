<?php

namespace App\Lib;

class FiltersHelper
{
    /**
     * Gets the filters defined in the request
     * and maps them to the scopes defined in the
     * related model.
     *
     * @param array $queryDataFilters
     * @param array $modelFilters
     *
     * @return	array
     */
    public function getFilters($queryDataFilters, $modelFilters)
    {
        $result = [];
        foreach ($modelFilters as $filter) {
            $scope = $filter[0];
            $params = explode(',', $filter[1]);

            if (count(array_intersect_key(array_flip($params), $queryDataFilters)) === count($params)) {
                $values = [];
                foreach ($params as $param) {
                    if (preg_match('[,]', $queryDataFilters[$param]) || preg_match('/(_id)$/', $param)) {
                        $values[] = explode(',', $queryDataFilters[$param]);
                    } else {
                        $values[] = $queryDataFilters[$param];
                    }
                }
                $result[$scope] = $values;
            }
        }

        return $result;
    }
}
