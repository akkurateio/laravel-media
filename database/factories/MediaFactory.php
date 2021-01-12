<?php

namespace Akkurate\LaravelMedia\Database\Factories;

use Akkurate\LaravelMedia\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Media::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'alt' => $this->faker->word,
            'legend' => $this->faker->word,
            'user_id' => 1,
            'account_id' => 1,
            'md5' => md5($this->faker->sentence),
        ];
    }
}
