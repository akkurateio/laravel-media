<?php

namespace Akkurate\LaravelMedia\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FilterWithAllTags implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->withAllTags($value);
    }
}
