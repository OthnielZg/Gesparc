<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Demande::with(['utilisateur', 'chefCdi']);

        // Filtrage par rôle :
        // Les agents ne voient que les leurs.
        // Le Chef CDI voit les siennes + celles de son service.
        // Le DR voit les siennes + celles de sa direction.
        // DSI/Gestionnaire/Admin voient tout.
        if ($user->hasRole(['agent'])) {
            $query->where('id_utilisateur', $user->id);
        } elseif ($user->hasRole(['chef_cdi'])) {
            $idService = $user->bureau?->id_service;

            if ($idService) {
                // Chef avec bureau configuré : voir les demandes de son service
                $query->where(function ($q) use ($user, $idService) {
                    $q->where('id_utilisateur', $user->id)
                      ->orWhereHas('utilisateur.bureau', function ($bureauQuery) use ($idService) {
                          $bureauQuery->where('id_service', $idService);
                      });
                });
            } else {
                // Chef sans bureau : voir ses propres demandes + toutes les pre-demandes du système
                // (cas transitoire pendant la configuration des bureaux)
                $query->where(function ($q) use ($user) {
                    $q->where('id_utilisateur', $user->id)
                      ->orWhere('statut', 'pre_demande');
                });
            }
        } elseif ($user->hasRole(['DR'])) {
            $directionId = $user->id_direction ?? $user->bureau?->service?->id_direction;
            
            if ($directionId) {
                $query->where(function ($q) use ($user, $directionId) {
                    $q->where('id_utilisateur', $user->id)
                      ->orWhereHas('utilisateur.bureau.service', function ($sq) use ($directionId) {
                          $sq->where('id_direction', $directionId);
                      })
                      ->orWhereHas('utilisateur', function ($uq) use ($directionId) {
                          $uq->where('id_direction', $directionId);
                      });
                });
            } else {
                $query->where('id_utilisateur', $user->id);
            }
        }

        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', '%' . $search . '%')
                  ->orWhere('categorie_demandee', 'like', '%' . $search . '%')
                  ->orWhere('motif_demande', 'like', '%' . $search . '%');
                
                // Si l'utilisateur n'est pas un agent, il peut aussi chercher par nom du demandeur
                if (!auth()->user()->hasRole('agent')) {
                    $q->orWhereHas('utilisateur', function($uq) use ($search) {
                        $uq->where('nom', 'like', '%' . $search . '%')
                          ->orWhere('prenom', 'like', '%' . $search . '%');
                    });
                }
            });
        }

        $demandes = $query->latest()->paginate(10);

        return view('demandes.index', compact('demandes'));
    }

    public function create()
    {
        if (Auth::user()->hasRole(['admin', 'gestionnaire'])) {
            abort(403, 'Les administrateurs et gestionnaires ne peuvent pas créer de demandes.');
        }

        return view('demandes.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasRole(['admin', 'gestionnaire'])) {
            abort(403, 'Les administrateurs et gestionnaires ne peuvent pas créer de demandes.');
        }

        $validated = $request->validate([
            'categorie_demandee' => 'required|string',
            'quantite' => 'required|integer|min:1',
            'motif_demande' => 'required|string',
            'priorite' => 'required|in:basse,normale,haute,urgente',
        ]);

        $demande = new Demande($validated);
        $demande->reference = Demande::generateReference();
        $demande->id_utilisateur = Auth::id();
        
        // Workflow spécifique selon le rôle
        if (Auth::user()->hasRole(['DR', 'SDGRTE', 'DSI'])) {
            $demande->statut = 'transmise_gestionnaire';
            $successMessage = 'Votre demande a été enregistrée et transmise directement au Gestionnaire.';
        } elseif (Auth::user()->hasRole(['chef_cdi'])) {
            $demande->statut = 'en_attente_dr';
            $demande->id_chef_cdi = Auth::id(); // Il est son propre validateur niveau CDI
            $successMessage = 'Votre demande a été enregistrée et transmise directement à la Direction Régionale.';
        } else {
            $demande->statut = 'pre_demande';
            $successMessage = 'Votre pré-demande a été enregistrée.';
        }
        
        $demande->save();

        return redirect()->route('demandes.index')->with('success', $successMessage);
    }

    public function show(Demande $demande)
    {
        $demande->load(['utilisateur', 'chefCdi', 'validations.utilisateur', 'affectation.materiel']);
        
        $materielsDisponibles = [];
        $materielsCategorie = [];

        if (Auth::user()->isGestionnaire() && $demande->statut === 'transmise_gestionnaire') {
            $materielsDisponibles = \App\Models\Materiel::where('statut', 'disponible')->get();
        }

        if (Auth::user()->isSDGRTE() || Auth::user()->isDSI()) {
            $materielsCategorie = \App\Models\Materiel::where('categorie', $demande->categorie_demandee)->get();
        }

        return view('demandes.show', compact('demande', 'materielsDisponibles', 'materielsCategorie'));
    }

    /**
     * Officialise une pré-demande (Appelé par le Chef CDI)
     */
    public function officialise(Request $request, Demande $demande)
    {
        $this->authorizeAction('chef_cdi', $demande, 'pre_demande');

        $validated = $request->validate([
            'decision' => 'required|in:OK,KO',
            'commentaire' => $request->decision === 'KO' ? 'required|string|min:5' : 'nullable|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $demande, $validated) {
            // 1. Enregistrer dans l'historique
            \App\Models\Validation::create([
                'id_demande' => $demande->id,
                'id_utilisateur' => Auth::id(),
                'decision' => $validated['decision'],
                'niveau_validation' => 'chef_cdi',
                'commentaire' => $validated['commentaire'],
            ]);

            // 2. Mettre à jour la demande
            if ($validated['decision'] === 'OK') {
                $demande->statut = 'en_attente_dr';
                $demande->id_chef_cdi = Auth::id();
                $message = 'La demande a été officialisée et transmise au DR.';
            } else {
                $demande->statut = 'rejetee';
                $message = 'La demande a été rejetée.';
            }

            $demande->save();

            session()->flash($validated['decision'] === 'OK' ? 'success' : 'error', $message);
        });

        return redirect()->route('demandes.show', $demande);
    }

    private function authorizeAction($role, $demande, $requiredStatut)
    {
        if (!Auth::user()->hasRole($role) || $demande->statut !== $requiredStatut) {
            abort(403, 'Action non autorisée pour ce statut ou ce rôle.');
        }
    }
}
