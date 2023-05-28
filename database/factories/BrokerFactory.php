<?php

namespace Database\Factories;

use Domain\Broker\Models\Broker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrokerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Broker::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'code' => Str::random(6),
            'email' => $this->faker->safeEmail,
            'department_id' => null,
            'address' => $this->faker->address,
            'contact' => $this->faker->phoneNumber,
            'notes' => null,
            'logo_url' => $this->faker->imageUrl(150, 150),
            'active' => true,
            'minimum_consumption_percentage' => config('saham.brokers.minimum_consumption_percentage'),
        ];
    }
}
