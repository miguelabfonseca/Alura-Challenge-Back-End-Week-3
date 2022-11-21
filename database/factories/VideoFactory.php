<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category' => \App\Models\Category::all()->pluck('id')->random(),
            'title' => fake()->name(),
            'description' => fake()->unique()->text(),
            'url' => fake()->unique()->url(),
        ];
    }

}
