<?php

namespace Tests\Feature;

use App\Models\Demande;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowValidationTest extends TestCase
{
    use RefreshDatabase;

    /** Crée un utilisateur avec un rôle donné */
    private function makeUser(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    /** Crée une demande à un statut donné */
    private function makeDemande(string $statut, User $agent): Demande
    {
        return Demande::create([
            'reference' => Demande::generateReference(),
            'quantite' => 1,
            'categorie_demandee' => 'Ordinateur Portable',
            'motif_demande' => 'Remplacement matériel défectueux en service',
            'priorite' => 'normale',
            'statut' => $statut,
            'id_utilisateur' => $agent->id,
        ]);
    }

    // ─── Étape 3 : Validation DR ──────────────────────────────────────────

    public function test_dr_peut_valider_et_transmettre_au_gestionnaire(): void
    {
        $agent = $this->makeUser('agent');
        $dr = $this->makeUser('DR');
        $demande = $this->makeDemande('en_attente_dr', $agent);

        $response = $this->actingAs($dr)->post(route('validations.store', $demande), [
            'decision' => 'OK',
            'commentaire' => 'Dossier conforme.',
        ]);

        $response->assertRedirect(route('demandes.show', $demande));
        $this->assertDatabaseHas('demandes', ['id' => $demande->id, 'statut' => 'transmise_gestionnaire']);
        $this->assertDatabaseHas('validations', ['id_demande' => $demande->id, 'decision' => 'OK', 'niveau_validation' => 'DR']);
    }

    public function test_dr_peut_rejeter_une_demande(): void
    {
        $agent = $this->makeUser('agent');
        $dr = $this->makeUser('DR');
        $demande = $this->makeDemande('en_attente_dr', $agent);

        $this->actingAs($dr)->post(route('validations.store', $demande), [
            'decision' => 'KO',
            'commentaire' => 'Demande non justifiée.',
        ]);

        $this->assertDatabaseHas('demandes', ['id' => $demande->id, 'statut' => 'rejetee']);
    }

    // ─── Étape 5 : SDREVT — Valider proposition SEDMI ────────────────────

    public function test_sdrevt_peut_valider_proposition_sedmi(): void
    {
        $agent = $this->makeUser('agent');
        $sdrevt = $this->makeUser('SDREVT');
        $demande = $this->makeDemande('proposition_sdrevt', $agent);
        $demande->update(['materiel_propose' => 'Dell Latitude 5540 (SN-001)']);

        $this->actingAs($sdrevt)->post(route('validations.store', $demande), [
            'decision' => 'OK',
            'decision_sdrevt' => 'valide_sedmi',
            'commentaire' => 'Proposition SEDMI appropriée.',
        ]);

        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'statut' => 'en_attente_dsi',
            'decision_sdrevt' => 'valide_sedmi',
        ]);
    }

    public function test_sdrevt_peut_soumettre_une_contre_proposition(): void
    {
        $this->withoutExceptionHandling();

        $agent = $this->makeUser('agent');
        $sdrevt = $this->makeUser('SDREVT');
        $demande = $this->makeDemande('proposition_sdrevt', $agent);
        $demande->update(['materiel_propose' => 'Dell Latitude 5540 (SN-001)']);

        $this->actingAs($sdrevt)->post(route('validations.store', $demande), [
            'decision' => 'OK',
            'decision_sdrevt' => 'contre_propose',
            'materiel_contre_propose' => 'HP EliteBook 840 G9',
            'reference_contre_propose' => 'HP-840G9-I7',
            'justification_sdrevt' => 'Le HP offre de meilleures performances pour ce profil utilisateur.',
        ]);

        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'statut' => 'en_attente_dsi',
            'decision_sdrevt' => 'contre_propose',
            'materiel_contre_propose' => 'HP EliteBook 840 G9',
            'reference_contre_propose' => 'HP-840G9-I7',
        ]);
    }

    public function test_sdrevt_contre_proposition_sans_justification_echoue(): void
    {
        $agent = $this->makeUser('agent');
        $sdrevt = $this->makeUser('SDREVT');
        $demande = $this->makeDemande('proposition_sdrevt', $agent);

        $this->actingAs($sdrevt)->post(route('validations.store', $demande), [
            'decision' => 'OK',
            'decision_sdrevt' => 'contre_propose',
            'materiel_contre_propose' => 'HP EliteBook 840 G9',
            // justification_sdrevt manquante intentionnellement
        ]);

        // La demande doit rester bloquée à proposition_sdrevt, pas transmise au DSI
        $this->assertDatabaseHas('demandes', ['id' => $demande->id, 'statut' => 'proposition_sdrevt']);
        $this->assertDatabaseMissing('demandes', ['id' => $demande->id, 'statut' => 'en_attente_dsi']);
    }

    // ─── Étape 6 : DSI — Arbitrage ────────────────────────────────────────

    public function test_dsi_peut_retenir_proposition_sedmi(): void
    {
        $agent = $this->makeUser('agent');
        $dsi = $this->makeUser('DSI');
        $demande = $this->makeDemande('en_attente_dsi', $agent);
        $demande->update([
            'materiel_propose' => 'Dell Latitude 5540',
            'decision_sdrevt' => 'valide_sedmi',
        ]);

        $this->actingAs($dsi)->post(route('validations.store', $demande), [
            'decision' => 'OK',
            'decision_dsi' => 'retient_sedmi',
            'commentaire_dsi' => 'Bonne proposition.',
        ]);

        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'statut' => 'validee_dsi',
            'decision_dsi' => 'retient_sedmi',
            'materiel_retenu_dsi' => 'Dell Latitude 5540',
        ]);
    }

    public function test_dsi_peut_retenir_contre_proposition_sdrevt(): void
    {
        $agent = $this->makeUser('agent');
        $dsi = $this->makeUser('DSI');
        $demande = $this->makeDemande('en_attente_dsi', $agent);
        $demande->update([
            'materiel_propose' => 'Dell Latitude 5540',
            'decision_sdrevt' => 'contre_propose',
            'materiel_contre_propose' => 'HP EliteBook 840 G9',
            'justification_sdrevt' => 'Meilleures performances.',
        ]);

        $this->actingAs($dsi)->post(route('validations.store', $demande), [
            'decision' => 'OK',
            'decision_dsi' => 'retient_sdrevt',
            'commentaire_dsi' => 'La contre-proposition est justifiée.',
        ]);

        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'statut' => 'validee_dsi',
            'decision_dsi' => 'retient_sdrevt',
            'materiel_retenu_dsi' => 'HP EliteBook 840 G9',
        ]);
    }

    public function test_dsi_peut_imposer_une_troisieme_option(): void
    {
        $agent = $this->makeUser('agent');
        $dsi = $this->makeUser('DSI');
        $demande = $this->makeDemande('en_attente_dsi', $agent);
        $demande->update([
            'materiel_propose' => 'Dell Latitude 5540',
            'decision_sdrevt' => 'valide_sedmi',
        ]);

        $this->actingAs($dsi)->post(route('validations.store', $demande), [
            'decision' => 'OK',
            'decision_dsi' => 'autre',
            'materiel_retenu_dsi' => 'Lenovo ThinkPad T16 Gen 2',
            'commentaire_dsi' => 'Solution plus adaptée au budget disponible.',
        ]);

        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'statut' => 'validee_dsi',
            'decision_dsi' => 'autre',
            'materiel_retenu_dsi' => 'Lenovo ThinkPad T16 Gen 2',
        ]);
    }

    public function test_dsi_peut_annuler_une_demande(): void
    {
        $agent = $this->makeUser('agent');
        $dsi = $this->makeUser('DSI');
        $demande = $this->makeDemande('en_attente_dsi', $agent);

        $this->actingAs($dsi)->post(route('validations.store', $demande), [
            'decision' => 'KO',
            'commentaire' => 'Demande non conforme aux priorités de la direction.',
        ]);

        $this->assertDatabaseHas('demandes', ['id' => $demande->id, 'statut' => 'annulee']);
    }
}
