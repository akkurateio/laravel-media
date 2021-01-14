<?php

namespace Akkurate\LaravelMedia\Repositories;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediasRepository implements MediasRepositoryInterface
{
	public function search(string $query = null)
	{
		$medias = Media::where('name', 'like', "%{$query}%")
			->orderBy('created_at', 'desc')
			->paginate(auth()->user()->preference->pagination ?: auth()->user()->account->preference->pagination);

        return $medias;
	}
}
