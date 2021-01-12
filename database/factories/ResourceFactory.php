<?php

namespace Akkurate\LaravelMedia\Database\Factories;

use Akkurate\LaravelMedia\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resource::class;

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
