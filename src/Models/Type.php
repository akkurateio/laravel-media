<?php

namespace Akkurate\LaravelMedia\Models;

use Akkurate\LaravelMedia\Database\Factories\TypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;


class Type extends Model implements Searchable
{
    use HasFactory, softDeletes;

    protected $table = 'media_types';

    protected $fillable = ['code','name','description','priority','is_active'];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TypeFactory::new();
    }

    public function resources() {
        return $this->hasMany('Akkurate\LaravelMedia\Models\Resource');
    }

    public function getSearchResult(): SearchResult
    	{
    		$url = route('brain.media.types.show', ['uuid' => auth()->user()->account->slug, $this->id]);

    		return new SearchResult(
    			$this,
    			$this->name,
    			$url
    		);
    	}
}
