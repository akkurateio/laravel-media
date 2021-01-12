<?php

namespace Akkurate\LaravelMedia\Database\Factories;

use Akkurate\LaravelMedia\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Type::class;

    public function definition()
    {
        return [
            'code' => $this->faker->currencyCode,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'priority' => $this->faker->numberBetween(1,10),
        ];
    }
}
