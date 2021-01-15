<?php

namespace Akkurate\LaravelMedia\Traits;

use Intervention\Image\Facades\Image;

/**
 * Trait HasImage
 */
trait HasCrop
{
    public function crop($model, $request)
    {
        if ($request->file('image')) {
            $file = $request->file('image');
            if (isset($request->all()['crop'])) {
                $crop = json_decode($request->all()['crop']);
                $img = Image::make($file);
                $img->crop(intval($crop->width), intval($crop->height), intval($crop->x), intval($crop->y));
                $img->save($file->getRealPath(), 100);
            }
            $name = $request['alt'] ? $request['alt'] : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $model->addMedia($file)->usingName($name)->withResponsiveImages()->toMediaCollection('images');
        }
    }
}
