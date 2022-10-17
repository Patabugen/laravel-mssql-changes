<?php

namespace Patabugen\MssqlChanges\Tests\Fixtures\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Patabugen\MssqlChanges\Tests\fixtures\Models\Address;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'first_line' => fake()->streetAddress(),
            'second_line' => fake()->city(),
        ];
    }
}
