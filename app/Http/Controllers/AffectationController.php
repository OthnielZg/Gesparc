<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Demande;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AffectationController extends Controller
{
    /**
     * Liste des demandes validées par la DSI et prêtes pour la remise physique.
     */
    public function dotations()
    {
        // On récupère les demandes validées par la DSI ou déjà en cours de finalisation
        $demandes = Demande::whereIn('statut', ['validee_dsi', 'en_finalisation'])
            ->with(['utilisateur.bureau.service'])
            ->latest()
            ->paginate(10);

        return view('affectations.dotations', compact('demandes'));
    }

    /**
     * Formulaire de finalisation pas à pas pour une demande spécifique.
     */
    public function finaliser(Demande $demande)
    {
        // Vérification du statut
        if (! in_array($demande->statut, ['validee_dsi', 'en_finalisation'])) {
            return redirect()->route('affectations.dotations')
                ->with('error', 'Cette demande ne peut pas être finalisée dans son état actuel.');
        }

        // Passage au statut "en_finalisation" si ce n'est pas déjà le cas
        if ($demande->statut === 'validee_dsi') {
            $demande->update(['statut' => 'en_finalisation']);
        }

        return view('affectations.finaliser', compact('demande'));
    }

    /**
     * Enregistre l'affectation finale et clôture la demande.
     */
    public function storeFinalisation(Request $request, Demande $demande)
    {
        $request->validate([
            'date_remise' => 'required|date',
            'lieu_remise' => 'required|string|max:255',
            'etat_reception' => 'required|in:neuf,bon,usage,degrade',
            'observations' => 'nullable|string|max:1000',
            'confirme' => 'accepted',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $demande) {
            $materielId = $this->getMaterielIdFromReference($demande->getMaterielRetenuLabel());

            if (! $materielId) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Impossible de localiser le matériel spécifié dans le stock. Veuillez vérifier le numéro de série.');
            }

            // 1. Créer l'affectation
            $affectation = Affectation::create([
                'reference_bordereau' => Affectation::generateReference(),
                'id_demande' => $demande->id,
                'id_utilisateur' => $demande->id_utilisateur,
                'id_materiel' => $materielId,
                'etat_reception' => $request->etat_reception,
                'date_affectation' => now(),
                'date_remise' => $request->date_remise,
                'lieu_remise' => $request->lieu_remise,
                'statut_bordereau' => 'brouillon',
                'observations' => $request->observations,
            ]);

            // 2. Mettre à jour la demande
            $demande->update(['statut' => 'finalisee']);

            // 3. Mettre à jour le statut du matériel
            \App\Models\Materiel::where('id', $materielId)->update(['statut' => 'affecte']);

            return redirect()->route('affectations.dotations')
                ->with('success', 'Dossier clôturé avec succès et affectation enregistrée.');
        });
    }

    /**
     * Helper pour extraire l'ID du matériel à partir de la chaîne stockée (Marque Modèle (SN)).
     */
    private function getMaterielIdFromReference(?string $reference): ?int
    {
        if (! $reference) {
            return null;
        }

        // On cherche le numéro de série entre parenthèses
        if (preg_match('/\(([^)]+)\)/', $reference, $matches)) {
            $sn = $matches[1];

            return \App\Models\Materiel::where('numero_serie', $sn)->value('id');
        }

        return null;
    }

    public function index()
    {
        $affectations = Affectation::with(['demande.utilisateur', 'materiel', 'utilisateur'])->latest()->paginate(10);

        return view('affectations.index', compact('affectations'));
    }

    public function download(Affectation $affectation)
    {
        $affectation->load(['demande.utilisateur', 'materiel', 'utilisateur']);

        $pdf = Pdf::loadView('affectations.bordereau', [
            'affectation' => $affectation,
            'date' => now()->format('d/m/Y'),
        ]);

        return $pdf->download('Bordereau-'.$affectation->reference_bordereau.'.pdf');
    }
}
