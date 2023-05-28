<?php

namespace Database\Factories;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'identifier' => Str::random('8'),
            'contact' => $this->faker->phoneNumber,
            'type' => User::TYPE_USER,
            'address' => $this->faker->address,
            'email_verified_at' => $this->faker->date(),
            'password' => 'password',
            'active' => true,
        ];
    }
}
