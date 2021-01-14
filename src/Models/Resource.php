<?php

namespace Akkurate\LaravelMedia\Models;

use App\Models\User;
use Akkurate\LaravelMedia\Database\Factories\ResourceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Webpatser\Uuid\Uuid;

class Resource extends Model implements HasMedia
{
    use Sluggable, HasFactory, InteractsWithMedia, softDeletes, HasTags;

    protected $table = 'media_resources';

    protected $fillable = ['slug', 'name', 'alt', 'legend', 'thumb', 'md5', 'type_id', 'media_id', 'user_id', 'account_id'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return ResourceFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $uuidFieldName = $model->getUuidFieldName();
            $model->$uuidFieldName = (string) Uuid::generate(4);
        });

        static::addGlobalScope('fromUserAccount', function (Builder $builder) {
            $builder->where('account_id', auth()->user()->account->id);
        });

    }

    public function resourceable()
    {
        return $this->morphTo('resourceable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * @param \Cocur\Slugify\Slugify $engine
     * @param string $attribute
     * @return \Cocur\Slugify\Slugify
     */
    public function customizeSlugEngine(\Cocur\Slugify\Slugify $engine, $attribute)
    {
        $engine->addRule('@', '-at-');
        return $engine;
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('default');
    }

    public function registerMediaConversions(Media $media = null): void
    {

        $this
            ->addMediaConversion('originalRatio')
            ->width(1920)
            ->height(1920)
            ->keepOriginalImageFormat()
            ->withResponsiveImages();

        if (config('laravel-media.conversions.' . config('app.env') . '.square')) {
            $this
                ->addMediaConversion('square')
                ->crop('crop-center', 1920, 1920)
                ->keepOriginalImageFormat()
                ->withResponsiveImages();
        }

        if (config('laravel-media.conversions.' . config('app.env') . '.16:9')) {
            $this
                ->addMediaConversion('16:9')
                ->crop('crop-center', 1920, 1080)
                ->keepOriginalImageFormat()
                ->withResponsiveImages();
        }

        if (config('laravel-media.conversions.' . config('app.env') . '.4:3')) {
            $this
                ->addMediaConversion('4:3')
                ->crop('crop-center', 1920, 1440)
                ->keepOriginalImageFormat()
                ->withResponsiveImages();
        }

        if (config('laravel-media.conversions.' . config('app.env') . '.preview')) {
            $this
                ->addMediaConversion('preview')
                ->keepOriginalImageFormat()
                ->crop('crop-center', 640, 640);
        }

        if (config('laravel-media.conversions.' . config('app.env') . '.thumb')) {
            $this
                ->addMediaConversion('thumb')
                ->width(320)
                ->height(320)
                ->keepOriginalImageFormat()
                ->crop('crop-center', 320, 320);
        }
    }

    public function scopeSearch($query, $q)
    {
        $ids = array_map(function ($item) {
            return $item['id'];
        }, Resource::select('id')->withAnyTags([$q])->get()->toArray());
        return $query->where('name', 'LIKE', '%' . $q . '%')->whereIn('id', $ids, 'or');
    }

}
