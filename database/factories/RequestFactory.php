<?php

namespace Database\Factories;

use Domain\Attestation\Models\AttestationType;
use Domain\Request\Models\Request;
use Domain\Request\States\Pending;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Request::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quantity' => $this->faker->randomNumber(3),
            'notes' => $this->faker->text,
            'attestation_type_id' => $this->faker->randomElement([AttestationType::GREEN, AttestationType::YELLOW]),
            'expected_at' => $this->faker->date(),
            'state' => Pending::$name
        ];
    }
}
