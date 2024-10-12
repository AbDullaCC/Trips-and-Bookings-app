<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_date = fake()->dateTimeBetween(now()->addDay(), now() .'+ 50 days');
        $string_date = $start_date->format('Y-m-d');

        return [
            'destination' => fake()->city(),
            'available_seats' => rand(10,30),
            'price' => rand(50, 900) * 1000,
            'start_date' => $start_date,
            'end_date' => fake()->dateTimeBetween($string_date . '+ 3 days', $string_date .'+ 10 days'),
            'status' => Trip::PENDING
        ];
    }
}
