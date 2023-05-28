<?php

namespace Database\Factories;

use App\Model;
use Domain\Attestation\Models\AttestationType;
use Domain\Supply\Models\Supply;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'supplier_id' => null,
            'attestation_type_id' => null,
            'received_at' => $this->faker->dateTimeBetween('-4 years'),
        ];
    }

    public function withRanges()
    {
        return $this->state(function (array $attributes) {
            switch ($attributes['attestation_type_id']):
                case AttestationType::YELLOW:
                    return [
                        'range_start' => 80220000000,
                        'range_end' => $this->faker->numberBetween(80220000001, 80220090000),
                    ];
            case AttestationType::GREEN:
                    return [
                        'range_start' => 8522000000,
                        'range_end' => $this->faker->numberBetween(8522000001, 85220090000),
                    ];
            case AttestationType::BROWN:
                return [
                    'range_start' => 127000000,
                    'range_end' => $this->faker->numberBetween(127000000, 127090000),
                ];
            endswitch;
        });
    }
}
