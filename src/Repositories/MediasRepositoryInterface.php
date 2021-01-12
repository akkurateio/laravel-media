<?php

namespace Akkurate\LaravelMedia\Repositories;

interface MediasRepositoryInterface
{
	public function search(string $query = "");
}
