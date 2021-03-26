<?php

namespace Database\Factories;

use App\Models\DivideGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class DivideGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DivideGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
