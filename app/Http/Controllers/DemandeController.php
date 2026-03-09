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

        // Filter by role: Agents only see their own. 
        // Chef CDI sees their own + their service's.
        // DR sees their own + their direction's.
        // DSI/Gestionnaire/Admin see all.
        if ($user->hasRole(['agent'])) {
            $query->where('id_utilisateur', $user->id);
        } elseif ($user->hasRole(['chef_cdi'])) {
            if ($user->bureau && $user->bureau->id_service) {
                $idService = $user->bureau->id_service;
                $query->where(function ($q) use ($user, $idService) {
                    $q->where('id_utilisateur', $user->id)
                      ->orWhereHas('utilisateur.bureau', function ($bureauQuery) use ($idService) {
                          $bureauQuery->where('id_service', $idService);
                      });
                });
            } else {
                $query->where('id_utilisateur', $user->id);
            }
        } elseif ($user->hasRole(['DR'])) {
            if ($user->id_direction) {
                $idDirection = $user->id_direction;
                $query->where(function ($q) use ($user, $idDirection) {
                    $q->where('id_utilisateur', $user->id)
                      ->orWhereHas('utilisateur.bureau.service', function ($serviceQuery) use ($idDirection) {
                          $serviceQuery->where('id_direction', $idDirection);
                      })
                      ->orWhereHas('utilisateur', function ($userQuery) use ($idDirection) {
                          $userQuery->where('id_direction', $idDirection);
                      });
                });
            } else {
                // If the DR is linked via bureau -> service -> direction instead of directly (fallback)
                if ($user->bureau && $user->bureau->service && $user->bureau->service->id_direction) {
                    $idDirection = $user->bureau->service->id_direction;
                    $query->where(function ($q) use ($user, $idDirection) {
                        $q->where('id_utilisateur', $user->id)
                          ->orWhereHas('utilisateur.bureau.service', function ($sQuery) use ($idDirection) {
                              $sQuery->where('id_direction', $idDirection);
                          });
                    });
                } else {
                    $query->where('id_utilisateur', $user->id);
                }
            }
        }

        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
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
        if (Auth::user()->hasRole(['DR', 'DSI'])) {
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
     * Officialise a pre-demande (Called by Chef CDI)
     */
    public function officialise(Demande $demande)
    {
        $this->authorizeAction('chef_cdi', $demande, 'pre_demande');

        $demande->statut = 'en_attente_dr';
        $demande->id_chef_cdi = Auth::id();
        $demande->save();

        return redirect()->route('demandes.show', $demande)->with('success', 'La demande a été officialisée et transmise au DR.');
    }

    private function authorizeAction($role, $demande, $requiredStatut)
    {
        if (!Auth::user()->hasRole($role) || $demande->statut !== $requiredStatut) {
            abort(403, 'Action non autorisée pour ce statut ou ce rôle.');
        }
    }
}
