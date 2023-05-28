<?php

namespace Database\Factories;

use App\Model;
use Domain\Supply\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'code' => $this->faker->randomDigit,
            'email' => $this->faker->safeEmail,
            'type' => $this->faker->word,
            'contact' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
        ];
    }
}
