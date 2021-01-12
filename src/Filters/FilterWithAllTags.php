<?php

namespace Akkurate\LaravelMedia\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterWithAllTags implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->withAllTags($value);
    }
}