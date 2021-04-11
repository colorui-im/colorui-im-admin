<?php

namespace Database\Factories;

use App\Models\RandomChat;
use Illuminate\Database\Eloquent\Factories\Factory;

class RandomChatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RandomChat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'status' => 'joining',
            'extra' => [
                'count' => mt_rand(2,4)
            ]
        ];
    }
}
