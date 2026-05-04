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
                $villeId = $user->direction?->id_ville;
                if ($villeId) {
                    $stats['nom_ville'] = $user->direction?->ville?->nom_ville ?? 'Région';
                    $stats['total_services'] = \App\Models\Service::whereHas('direction', function($q) use ($villeId) {
                        $q->where('id_ville', $villeId);
                    })->count();

                    // --- Consolidated Regional Matrix Data ---
                    $stats['directions_region'] = \App\Models\Direction::where('id_ville', $villeId)
                        ->with(['services'])
                        ->get()
                        ->map(function($direction) {
                            $count = Demande::where('statut', 'en_attente_dr')
                                ->whereHas('utilisateur', fn($q) => $q->where('id_direction', $direction->id))
                                ->count();
                            
                            $direction->attente_validation = $count;
                            $direction->health = match(true) {
                                $count > 5 => 'unhealthy', // Rouge
                                $count > 0 => 'needs_action', // Jaune
                                default => 'healthy' // Vert
                            };
                            return $direction;
                        });

                    // --- Recent Regional Activity ---
                    $stats['recent_region'] = Demande::whereHas('utilisateur', function($q) use ($villeId) {
                            $q->whereHas('direction', fn($dq) => $dq->where('id_ville', $villeId))
                              ->orWhereHas('bureau.service.direction', fn($dq) => $dq->where('id_ville', $villeId));
                        })
                        ->with(['utilisateur'])
                        ->latest()
                        ->limit(5)
                        ->get();
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
            $villeId = $user->direction?->id_ville;
            if ($villeId) {
                // Filtre par Ville (Région)
                $statutQuery->where(function($q) use ($villeId) {
                    $q->whereHas('utilisateur.direction', fn($dq) => $dq->where('id_ville', $villeId))
                      ->orWhereHas('utilisateur.bureau.service.direction', fn($dq) => $dq->where('id_ville', $villeId));
                });
                $stockQuery->whereHas('affectations.utilisateur.direction', fn($dq) => $dq->where('id_ville', $villeId))
                      ->orWhereHas('affectations.utilisateur.bureau.service.direction', fn($dq) => $dq->where('id_ville', $villeId));
            }
        } elseif ($user->hasRole(['DSI', 'SDGRTE'])) {
            $directionId = $user->id_direction;
            if ($directionId) {
                // Filtre par Direction uniquement (Bilan)
                $statutQuery->where(function($q) use ($directionId) {
                    $q->whereHas('utilisateur.direction', fn($dq) => $dq->where('id_direction', $directionId))
                      ->orWhereHas('utilisateur.bureau.service.direction', fn($dq) => $dq->where('id_direction', $directionId));
                });
                $stockQuery->whereHas('affectations.utilisateur.direction', fn($dq) => $dq->where('id_direction', $directionId))
                      ->orWhereHas('affectations.utilisateur.bureau.service.direction', fn($dq) => $dq->where('id_direction', $directionId));
            }
        }
        // Pour Gestionnaire on garde la vue globale (pas de filtre)

        $repartitionStatut = collect([]);
        if ($user->role !== 'agent') {
            $repartitionStatut = $statutQuery->select('statut', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('statut')
                ->get()
                ->map(function($item) {
                    return [
                        'statut' => $item->statut,
                        'label'  => $item->statut_label,
                        'total'  => $item->total,
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
                $villeId = $user->direction?->id_ville;
                
                if ($villeId) {
                    $query->where(function ($q) use ($villeId) {
                        $q->whereHas('utilisateur.direction', function ($dq) use ($villeId) {
                            $dq->where('id_ville', $villeId);
                        })
                        ->orWhereHas('utilisateur.bureau.service.direction', function ($dq) use ($villeId) {
                            $dq->where('id_ville', $villeId);
                        });
                    });
                } else {
                    $query->where('id_utilisateur', $user->id);
                }
            }
            
            return $query->count();
        }

        return 0;
    }
}
