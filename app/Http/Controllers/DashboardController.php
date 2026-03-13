<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Materiel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = [];

        if ($user->role === 'agent') {
            // Stats uniquement pour l'agent
            $stats = [
                'mes_demandes' => Demande::where('id_utilisateur', $user->id)->count(),
                'mes_materiels' => \App\Models\Affectation::where('id_utilisateur', $user->id)
                    ->whereNull('date_retour')
                    ->count(),
            ];
        } elseif ($user->role === 'chef_cdi') {
            // Stats pour le Chef de Service
            $serviceId = $user->bureau?->id_service;
            
            $stats = [
                'mes_demandes' => Demande::where('id_utilisateur', $user->id)->count(),
                'mes_materiels' => \App\Models\Affectation::where('id_utilisateur', $user->id)
                    ->whereNull('date_retour')
                    ->count(),
                'materiels_service' => \App\Models\Affectation::whereHas('utilisateur.bureau', function($query) use ($serviceId) {
                        $query->where('id_service', $serviceId);
                    })
                    ->whereNull('date_retour')
                    ->count(),
            ];
        } else {
            // Stats globales filtrées selon le privilège
            $stats = [
                'mes_demandes' => Demande::where('id_utilisateur', $user->id)->count(),
            ];

            if ($user->hasRole(['gestionnaire', 'DSI'])) {
                $stats['total_materiels'] = Materiel::count();
                $stats['disponible'] = Materiel::where('statut', 'disponible')->count();
            }

            // Role specific logic
            if ($user->hasRole(['DR', 'gestionnaire', 'SDGRTE', 'DSI'])) {
                $stats['demandes_a_valider'] = $this->getPendingValidationsCount($user);
            }

            if ($user->role === 'DR') {
                $directionId = $user->id_direction ?? $user->bureau?->service?->id_direction;
                if ($directionId) {
                    $stats['total_services'] = \App\Models\Service::where('id_direction', $directionId)->count();
                }
            }

            if ($user->hasRole(['admin'])) {
                $stats['total_users'] = User::count();
                $stats['users_actifs'] = User::where('actif', true)->count();
                $stats['users_bloques'] = User::where('actif', false)->count();
                $stats['total_villes'] = \App\Models\Ville::count();
                $stats['total_directions'] = \App\Models\Direction::count();
                $stats['total_services'] = \App\Models\Service::count();
            }
        }

        // --- Données pour les Graphiques (Filtrées par Rôle) ---
        
        $statutQuery = Demande::query();
        $stockQuery = Materiel::query();
        $userRoleData = collect([]);
        $userStructureData = collect([]);

        if ($user->hasRole('admin')) {
            // Graphique 1: Utilisateurs par Rôle
            $userRoleData = User::select('role', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('role')
                ->get()
                ->map(function($item) {
                    return [
                        'label' => User::roleLabels()[$item->role] ?? $item->role,
                        'total' => $item->total
                    ];
                });

            // Graphique 2: Utilisateurs par Direction
            $userStructureData = User::join('directions', 'users.id_direction', '=', 'directions.id')
                ->select('directions.nom_direction', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('directions.nom_direction')
                ->get();
            
            // Si pas assez de données par direction, essayer par service via bureau
            if ($userStructureData->isEmpty()) {
                $userStructureData = User::join('bureaux', 'users.id_bureau', '=', 'bureaux.id')
                    ->join('services', 'bureaux.id_service', '=', 'services.id')
                    ->select('services.nom_service as label', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                    ->groupBy('services.nom_service')
                    ->get();
            }
        }

        if ($user->role === 'agent') {
            $statutQuery->where('id_utilisateur', $user->id);
            // Pour l'agent, le stock n'est pas forcément pertinent en détail global, 
            // mais on peut lui montrer les catégories de ses propres matériels.
            $stockQuery = \App\Models\Affectation::where('id_utilisateur', $user->id)
                ->whereNull('date_retour')
                ->join('materiels', 'affectations.id_materiel', '=', 'materiels.id')
                ->select('materiels.categorie', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('materiels.categorie');
        } elseif ($user->role === 'chef_cdi') {
            $serviceId = $user->bureau?->id_service;
            $statutQuery->whereHas('utilisateur.bureau', fn($q) => $q->where('id_service', $serviceId));
            $stockQuery->whereHas('affectations.utilisateur.bureau', fn($q) => $q->where('id_service', $serviceId));
        } elseif ($user->role === 'DR') {
            $directionId = $user->id_direction ?? $user->bureau?->service?->id_direction;
            $statutQuery->whereHas('utilisateur.bureau.service', fn($q) => $q->where('id_direction', $directionId));
            $stockQuery->whereHas('affectations.utilisateur.bureau.service', fn($q) => $q->where('id_direction', $directionId));
        }
        // Pour Gestionnaire, SDGRTE, DSI, on garde la vue globale (pas de filtre)

        // 1. Répartition par Statut (Désactivé pour agent)
        $repartitionStatut = collect([]);
        if ($user->role !== 'agent') {
            $repartitionStatut = $statutQuery->select('statut', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('statut')
                ->get()
                ->map(function($item) {
                    return [
                        'label' => $item->statut_label,
                        'total' => $item->total,
                        'color' => $item->statut_color_hex
                    ];
                });
        }

        // 2. Stock par Catégorie (Désactivé pour agent)
        $stockParCategorie = collect([]);
        if ($user->role !== 'agent') {
            $stockParCategorie = $stockQuery->select('categorie', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('categorie')
                ->get();
        }

        return view('dashboard', compact(
            'stats', 
            'repartitionStatut', 
            'stockParCategorie', 
            'userRoleData', 
            'userStructureData'
        ));
    }

    private function getPendingValidationsCount($user)
    {
        // Simple logic based on status
        $statusMap = [
            'DR' => 'en_attente_dr',
            'gestionnaire' => 'transmise_gestionnaire',
            'SDGRTE' => 'proposition_sdgrte',
            'DSI' => 'en_attente_dsi',
        ];

        $targetStatus = $statusMap[$user->role] ?? null;
        
        if ($targetStatus) {
            $query = Demande::where('statut', $targetStatus);
            
            if ($user->hasRole('DR')) {
                $directionId = $user->id_direction ?? $user->bureau?->service?->id_direction;
                
                if ($directionId) {
                    $direction = \App\Models\Direction::find($directionId);
                    $idVille = $direction?->id_ville;

                    if ($idVille) {
                        $query->whereHas('utilisateur.bureau.service.direction', function ($q) use ($idVille) {
                            $q->where('id_ville', $idVille);
                        });
                    } else {
                        $query->where('id_utilisateur', $user->id);
                    }
                } else {
                    $query->where('id_utilisateur', $user->id);
                }
            }
            
            return $query->count();
        }

        return 0;
    }
}
