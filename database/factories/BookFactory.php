<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(50),
            'description' => $this->faker->text(),
            'purchase_date' => $this->faker->date(),
            'cover_type' => $this->faker->randomElement(['softcover', 'hardcover_casewrap', 'hardcover_dust_jacket']),
            'user_id' => User::factory(),
            'location_id' => Location::factory()
        ];
    }
}
