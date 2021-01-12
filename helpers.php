<?php

if (!function_exists('media64')) {
    /**
     * Convert media to Base64
     *
     * @param $media
     * @return mixed
     */
    function media64($media)
    {
        if (!empty($media)) {
            $path = $media->getPath();
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:application/' . $type . ';base64,' . base64_encode($data);
        }
    }
}

if (!function_exists('getMediaResource')) {
    /**
     * @param null $slug
     * @param string $conversion
     * @param string $class
     * @param null $alt
     * @return |null
     */
    function getMediaResource($slug = null, $conversion = 'originalRatio', $class = 'img-fluid', $alt = null)
    {
        if (!empty($slug)) {
            $resource = \Akkurate\LaravelMedia\Models\Resource::where('slug', $slug)->first();
            if (!empty($resource)) {
                if (!empty($alt)) {
                    $attrAlt = $alt;
                } else {
                    $attrAlt = $resource->alt ? $resource->alt : $resource->getFirstMedia()->name;
                }

                if(!empty($resource->getFirstMedia()($conversion))) {
                    return ($resource->getFirstMedia()($conversion))->attributes([
                        'class' => $class,
                        'alt' => $attrAlt
                    ]);
                }
            }
        }
        return null;
    }
}

if (!function_exists('getMediaFromModel')) {
    /**
     * @param null $model
     * @param string $conversion
     * @param string $alt
     * @return null
     */
    function getMediaFromModel($model = null, $conversion = 'originalRatio', $alt = 'alt', $class = 'img-fluid')
    {
        if (!empty($model) && !empty($model->getLastResource()) && !empty($model->getLastResource()->getFirstMedia())) {
            return ($model->getLastResource()->getFirstMedia()($conversion))->attributes([
                'class' => $class,
                'alt' => $model->{$alt} ?? $model->getLastResource()->getFirstMedia()->name
            ]);
        }
        return null;
    }
}
