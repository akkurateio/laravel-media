<?php

namespace Akkurate\LaravelMedia\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'items' => $this->collection,
            'includes' => ['items'],
        ];
    }
}
