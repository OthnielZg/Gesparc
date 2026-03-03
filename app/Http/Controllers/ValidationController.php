<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Materiel;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidationController extends Controller
{
    /**
     * Liste des demandes en attente de traitement selon le rôle connecté.
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

        if (! $targetStatus) {
            return redirect()->route('demandes.index');
        }

        $demandes = Demande::where('statut', $targetStatus)
            ->with(['utilisateur.bureau.service', 'validations.utilisateur'])
            ->latest()
            ->paginate(10);

        // Pour le Gestionnaire : charger les matériels disponibles pour la proposition
        $materielsDisponibles = collect();
        if ($user->isGestionnaire()) {
            $materielsDisponibles = Materiel::where('statut', 'disponible')
                ->orderBy('categorie')
                ->orderBy('marque')
                ->get();
        }

        return view('validations.index', compact('demandes', 'materielsDisponibles'));
    }

    /**
     * Enregistre la décision de validation selon le rôle de l'utilisateur.
     */
    public function store(Request $request, Demande $demande)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'decision' => 'required|in:OK,KO',
        ]);

        return DB::transaction(function () use ($user, $demande, $validated, $request) {

            // 1. Enregistrer la validation dans l'historique
            Validation::create([
                'id_demande' => $demande->id,
                'id_utilisateur' => $user->id,
                'decision' => $validated['decision'],
                'niveau_validation' => $user->role,
                'commentaire' => $request->input('commentaire'),
            ]);

            // 2. Décision KO → Rejeter ou Annuler
            if ($validated['decision'] === 'KO') {
                $demande->statut = ($user->role === 'DSI') ? 'annulee' : 'rejetee';
                $demande->save();

                return redirect()
                    ->route('demandes.show', $demande)
                    ->with('error', 'La demande a été rejetée/annulée.');
            }

            // 3. Décision OK → Transition selon le rôle
            if ($user->role === 'DR') {
                $demande->statut = 'transmise_gestionnaire';
            } elseif ($user->role === 'gestionnaire') {
                $materiel = Materiel::findOrFail($validated['id_materiel']);
                $demande->materiel_propose = $materiel->marque.' '.$materiel->modele.' ('.$materiel->numero_serie.')';
                $demande->commentaire_gestionnaire = $request->input('note_gestionnaire');
                $demande->statut = 'proposition_sdrevt';
            } elseif ($user->role === 'SDREVT') {
                $decisionSdrevt = $request->input('decision_sdrevt', 'valide_sedmi');
                $demande->decision_sdrevt = $decisionSdrevt;

                if ($decisionSdrevt === 'contre_propose') {
                    // Valider que les champs obligatoires sont présents (dépend de la validation ci-dessus mais check supplémentaire)
                    if (empty($request->input('materiel_contre_propose')) || empty($request->input('justification_sdrevt'))) {
                        // Pour les tests, lancer une exception de validation si manque
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'materiel_contre_propose' => 'Le matériel alternatif et la justification sont obligatoires.',
                        ]);
                    }
                    $demande->materiel_contre_propose = $request->input('materiel_contre_propose');
                    $demande->reference_contre_propose = $request->input('reference_contre_propose');
                    $demande->justification_sdrevt = $request->input('justification_sdrevt');
                }
                $demande->statut = 'en_attente_dsi';

            } elseif ($user->role === 'DSI') {
                $decisionDsi = $request->input('decision_dsi', 'retient_sedmi');
                $demande->decision_dsi = $decisionDsi;
                $demande->commentaire_dsi = $request->input('commentaire_dsi');

                // Déterminer le matériel retenu selon le choix DSI
                if ($decisionDsi === 'retient_sedmi') {
                    $demande->materiel_retenu_dsi = $demande->materiel_propose;
                } elseif ($decisionDsi === 'retient_sdrevt') {
                    $demande->materiel_retenu_dsi = $demande->materiel_contre_propose;
                } elseif ($decisionDsi === 'autre') {
                    if (empty($request->input('materiel_retenu_dsi'))) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'materiel_retenu_dsi' => 'Le matériel imposé est obligatoire pour cette option.',
                        ]);
                    }
                    $demande->materiel_retenu_dsi = $request->input('materiel_retenu_dsi');
                }

                // Le DSI valide → le dossier passe en attente de finalisation par le SEDMI
                $demande->statut = 'validee_dsi';
            }

            $demande->save();

            return redirect()
                ->route('demandes.show', $demande)
                ->with('success', 'Validation enregistrée avec succès.');
        });
    }
}
