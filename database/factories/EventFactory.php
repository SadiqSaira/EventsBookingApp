<?php

namespace Database\Factories;
use App\Models\Event;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Event::class;
    public function definition()
    {
        return [
            'event_name' => $this->faker->sentence,
            'start_datetime' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_datetime' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'ticket_allocation' => $this->faker->numberBetween(50, 200),
            'max_tickets_per_customer' => $this->faker->numberBetween(5, 10),
        ];
    }
}
