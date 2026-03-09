<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prenom' => fake()->firstName(),
            'nom' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'agent',
            'actif' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'admin']);
    }

    public function agent(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'agent']);
    }

    public function chefCdi(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'chef_cdi']);
    }

    public function dr(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'DR']);
    }

    public function gestionnaire(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'gestionnaire']);
    }

    public function sdgrte(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'SDGRTE']);
    }

    public function dsi(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'DSI']);
    }
}
