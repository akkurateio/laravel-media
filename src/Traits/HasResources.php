<?php

namespace Akkurate\LaravelMedia\Traits;

use Akkurate\LaravelMedia\Models\Resource;

/**
 * Trait HasResources
 */
trait HasResources
{
    public function resources()
    {
        return $this->morphToMany(Resource::class, 'resourceable', 'media_model_resource');
    }

    public function syncResources($request)
    {
        if ($request->resource_id && ! empty($request->resource_id)) {
            $resources = explode(',', $request->resource_id);
            $this->resources()->sync($resources);
        }
    }

    public function getFirstResource()
    {
        return $this->resources->first();
    }

    public function getLastResource()
    {
        return $this->resources->last();
    }

    public function getFirstResourceMedia()
    {
        return $this->resources->first()->getFirstMedia();
    }

    public function getLastResourceMedia()
    {
        return $this->resources->last()->getFirstMedia();
    }

    public function getFirstResourceMediaUrl()
    {
        return $this->resources->first()->getFirstMediaUrl();
    }

    public function getLastResourceMediaUrl()
    {
        return $this->resources->last()->getFirstMediaUrl();
    }

    public function getLastResourceMediaName()
    {
        return $this->resources->last()->getFirstMedia()->name;
    }

    public function getThumb()
    {
        if (! $this->resources->first()) {
            return false;
        }

        return $this->resources->last()->getFirstMediaUrl('default', 'thumb');
    }

    public function getThumbAsBase64()
    {
        if (! $this->resources->first()) {
            return false;
        }

        return $this->resources->last()->thumb;
    }

    public function getPreview()
    {
        if (! $this->resources->first()) {
            return false;
        }

        return $this->resources->last()->getFirstMediaUrl('default', 'preview');
    }

    public function getSquare()
    {
        if (! $this->resources->first()) {
            return false;
        }

        return $this->resources->last()->getFirstMediaUrl('default', 'square');
    }

    public function get4_3()
    {
        if (! $this->resources->first()) {
            return false;
        }

        return $this->resources->last()->getFirstMediaUrl('default', '4:3');
    }

    public function get16_9()
    {
        if (! $this->resources->first()) {
            return false;
        }

        return $this->resources->last()->getFirstMediaUrl('default', '16:9');
    }

    public function getOriginalRatio()
    {
        if (! $this->resources->first()) {
            return false;
        }

        return $this->resources->last()->getFirstMediaUrl('default', 'originalRatio');
    }

    public function getResourceId()
    {
        return $this->resources->last()->id;
    }

    public function getResourceAlt()
    {
        return $this->resources->last()->alt;
    }

    public function getResourceLegend()
    {
        return $this->resources->last()->legend;
    }
}
