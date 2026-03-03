<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Materiel>
 */
class MaterielFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $marques = ['Dell', 'HP', 'Lenovo', 'Apple'];
        $categories = ['Ordinateur Portable', 'Ordinateur de Bureau', 'Imprimante', 'Scanner'];
        $marque = $this->faker->randomElement($marques);

        return [
            'numero_serie' => $this->faker->unique()->bothify('SN-####-????'),
            'categorie' => $this->faker->randomElement($categories),
            'marque' => $marque,
            'modele' => $marque.' '.$this->faker->word(),
            'statut' => 'disponible',
            'prix_acquisition' => $this->faker->randomFloat(2, 500000, 2000000),
            'id_fournisseur' => null,
            'description' => $this->faker->sentence(),
        ];
    }
}
