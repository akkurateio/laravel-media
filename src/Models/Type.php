<?php

namespace Akkurate\LaravelMedia\Models;

use Akkurate\LaravelCore\Traits\HasUuid;
use Akkurate\LaravelMedia\Database\Factories\TypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model implements Searchable
{
    use HasFactory, HasUuid, softDeletes;

    protected $table = 'media_types';

    protected $fillable = ['code','name','description','priority','is_active'];


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
