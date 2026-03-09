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

            if ($user->hasRole(['admin'])) {
                $stats['total_users'] = User::count();
            }
        }

        return view('dashboard', compact('stats'));
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
                } elseif ($user->bureau && $user->bureau->service && $user->bureau->service->id_direction) {
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
            
            return $query->count();
        }

        return 0;
    }
}
