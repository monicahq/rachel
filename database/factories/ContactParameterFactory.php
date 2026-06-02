<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Contact;
use App\Models\ContactParameter;
use App\Models\Vault;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactParameter>
 */
final class ContactParameterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_id' => Contact::factory(),
            'vault_id' => Vault::factory(),
            'key' => fake()->word(),
            'type' => fake()->word(),
            'label' => fake()->word(),
            'data' => fake()->sentence(),
        ];
    }
}
