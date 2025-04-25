<?php

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BaseFilter
{
    /**
     * Apply filters to the query.
     *
     * @param Builder $query
     * @param array|null $filters
     * @return Builder
     */
    public function apply(Builder $query, ?array $filters): Builder
    {
        if (!$filters) {
            return $query;
        }

        foreach ($filters as $filter => $value) {
            $method = 'filter' . Str::camel($filter);
            if (method_exists($this, $method) && $value !== null) {
                $this->{$method}($query, $value);
            }
        }

        return $query;
    }
}
