<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Demande>
 */
class DemandeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => 'REF-'.now()->format('Ymd').'-'.$this->faker->unique()->numberBetween(1000, 9999),
            'quantite' => 1,
            'categorie_demandee' => $this->faker->randomElement(['Ordinateur Portable', 'Imprimante', 'Scanner']),
            'motif_demande' => $this->faker->paragraph(),
            'priorite' => 'normale',
            'statut' => 'pre_demande',
            'id_utilisateur' => \App\Models\User::factory(),
            'id_chef_cdi' => null,
            'materiel_propose' => null,
            'commentaire_gestionnaire' => null,
        ];
    }

    public function valideeDsi(): self
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'validee_dsi',
            'materiel_propose' => 'Dell Latitude (SN-1234)',
            'materiel_retenu_dsi' => 'Dell Latitude (SN-1234)',
            'decision_dsi' => 'retient_sedmi',
        ]);
    }
}
