<?php

namespace App\Lib;

class RequestContext
{
    protected $page = null;
    protected $pageSize = null;
    protected $orderBy = null;
    protected $orderDirection = null;
    protected $with = [];
    protected $withCount = [];
    protected $filters = [];

    /**
     * Sets the request context object.
     *
     * @int $page
     * @int $pageSize
     * @string $pageSize
     * @string $orderDirection
     * @array with
     * @array withCount
     * @array filters
     */
    public function setRequestContext(
        $page = null,
        $pageSize = null,
        $orderBy = null,
        $orderDirection = null,
        $with = [],
        $withCount = [],
        $filters = []
    ) {
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->orderBy = $orderBy;
        $this->orderDirection = $orderDirection;
        $this->with = $with;
        $this->withCount = $withCount;
        $this->filters = $filters;
    }

    /**
     * Returns the page property.
     *
     * @return null|int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Returns the page size property.
     *
     * @return null|int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * Returns the order by property.
     *
     * @return null|string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Returns the order direction property.
     *
     * @return null|string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * Returns the with property.
     *
     * @return array
     */
    public function getWith()
    {
        return $this->with;
    }

    /**
     * Returns the withCount property.
     *
     * @return array
     */
    public function getWithCount()
    {
        return $this->withCount;
    }

    /**
     * Returns the filters property.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Add filter to context.
     *
     * @param string $name
     * @param mixed $value
     */
    public function addFilter(string $name, $value)
    {
        $this->filters[$name] = is_array($value) ? $value : [$value];
    }
}
