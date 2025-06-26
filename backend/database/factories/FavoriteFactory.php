<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'word' => $this->faker->word(),
            'phonetics' => [$this->faker->word()],
            'definitions' => [$this->faker->sentence()],
            'part_of_speech' => [$this->faker->word()],
            'example_usage' => [$this->faker->sentence()],
            'synonyms' => [$this->faker->word(), $this->faker->word()],
            'notes' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
