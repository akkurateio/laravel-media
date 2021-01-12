<?php

namespace Akkurate\LaravelMedia\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Media extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'collection_name' => $this->collection_name,
            'name' => $this->name,
            'file_name' => $this->file_name,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'custom_properties' => $this->custom_properties,
            'responsive_images' => $this->responsive_images,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
