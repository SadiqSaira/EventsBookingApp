<?php

namespace Database\Factories;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'num_tickets_booked' => $this->faker->numberBetween(1, 10),
            'booking_datetime' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
        ];
    }
}
