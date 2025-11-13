<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Symfony\Component\Uid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WebauthnKey>
 */
final class WebauthnKeyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'kind' => 'passkey',
            'used_at' => now(),
            'counter' => 0,
            'type' => 'public-key',
            'transports' => [],
            'attestationType' => 'none',
            'trustPath' => new \Webauthn\TrustPath\EmptyTrustPath,
            'credentialId' => fake()->name(),
            'aaguid' => Uuid::fromString('00000000-0000-0000-0000-000000000000'),
            'credentialPublicKey' => fake()->name(),
        ];
    }
}
