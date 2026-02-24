<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Validation;
use App\Models\Materiel;
use App\Models\Affectation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidationController extends Controller
{
    /**
     * Display a listing of demands waiting for the current user's role validation
     */
    public function index()
    {
        $user = Auth::user();
        $statusMap = [
            'DR' => 'en_attente_dr',
            'gestionnaire' => 'transmise_gestionnaire',
            'SDREVT' => 'proposition_sdrevt',
            'DSI' => 'en_attente_dsi',
        ];

        $targetStatus = $statusMap[$user->role] ?? null;

        if (!$targetStatus) {
            return redirect()->route('demandes.index');
        }

        $demandes = Demande::where('statut', $targetStatus)->with('utilisateur')->latest()->paginate(10);

        return view('validations.index', compact('demandes'));
    }

    /**
     * Handle the validation decision (OK/KO)
     */
    public function store(Request $request, Demande $demande)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'decision' => 'required|in:OK,KO',
            'commentaire' => 'nullable|string',
            'id_materiel' => 'nullable|exists:materiels,id', // For gestionnaire proposal
        ]);

        return DB::transaction(function () use ($user, $demande, $validated) {
            // 1. Create Validation record
            Validation::create([
                'id_demande' => $demande->id,
                'id_utilisateur' => $user->id,
                'decision' => $validated['decision'],
                'niveau_validation' => $user->role,
                'commentaire' => $validated['commentaire'],
            ]);

            // 2. Update Demande status based on role and decision
            if ($validated['decision'] === 'KO') {
                $demande->statut = ($user->role === 'DSI') ? 'annulee' : 'rejetee';
                $demande->save();
                return redirect()->route('demandes.show', $demande)->with('error', 'La demande a été rejetée/annulée.');
            }

            // Decision is OK
            switch ($user->role) {
                case 'DR':
                    $demande->statut = 'transmise_gestionnaire';
                    break;
                case 'gestionnaire':
                    $demande->statut = 'proposition_sdrevt';
                    $demande->materiel_propose = Materiel::find($validated['id_materiel'])->numero_serie ?? null;
                    break;
                case 'SDREVT':
                    $demande->statut = 'en_attente_dsi';
                    break;
                case 'DSI':
                    $demande->statut = 'validee';
                    // Final step: Create Affectation
                    $this->createAffectation($demande);
                    break;
            }

            $demande->save();
            return redirect()->route('demandes.show', $demande)->with('success', 'Validation enregistrée avec succès.');
        });
    }

    private function createAffectation(Demande $demande)
    {
        // Try to find the proposed material or first available of the category
        $materiel = Materiel::where('numero_serie', $demande->materiel_propose)->first();
        
        if (!$materiel) {
            $materiel = Materiel::where('categorie', $demande->categorie_demandee)
                                ->where('statut', 'disponible')
                                ->first();
        }

        if ($materiel) {
            Affectation::create([
                'reference_bordereau' => Affectation::generateReference(),
                'id_demande' => $demande->id,
                'id_utilisateur' => $demande->id_utilisateur,
                'id_materiel' => $materiel->id,
                'etat_reception' => 'neuf',
                'date_affectation' => now(),
            ]);

            $materiel->update(['statut' => 'affecte']);
        }
    }
}
