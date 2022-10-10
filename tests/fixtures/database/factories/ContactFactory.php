<?php

namespace Patabugen\MssqlChanges\Tests\Fixtures\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Patabugen\MssqlChanges\Models\Contact;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'Firstname' => fake()->firstName,
            'Surname' => fake()->lastName,
        ];
    }
}
