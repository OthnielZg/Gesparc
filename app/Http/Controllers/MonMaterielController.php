<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Direction;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MonMaterielController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('agent')) {
            $affectations = Affectation::where('id_utilisateur', $user->id)
                ->with(['materiel', 'demande'])
                ->latest('date_affectation')
                ->get();

            return view('mon_materiel.agent', compact('affectations'));
        }

        if ($user->hasRole('chef_cdi')) {
            $service = $user->bureau?->service;

            if (! $service) {
                return view('mon_materiel.chef', [
                    'service' => null,
                    'affectations' => collect(),
                    'membres' => collect(),
                ]);
            }

            $userIds = User::whereHas('bureau', fn ($q) => $q->where('id_service', $service->id))
                ->pluck('id');

            $affectations = Affectation::whereIn('id_utilisateur', $userIds)
                ->with(['materiel', 'utilisateur.bureau'])
                ->whereNull('date_retour')
                ->latest('date_affectation')
                ->get();

            $membres = User::whereHas('bureau', fn ($q) => $q->where('id_service', $service->id))
                ->with(['bureau', 'affectations.materiel'])
                ->get();

            return view('mon_materiel.chef', compact('service', 'affectations', 'membres'));
        }

        if ($user->hasRole('DR')) {
            // DR sees all services stats in their direction
            $direction = $user->direction;

            if (! $direction) {
                // Fallback: show all directions
                $direction = Direction::with(['services.bureaux.utilisateurs'])->first();
            }

            // Load services with their bureaux, users and affectations
            $services = Service::where('id_direction', $direction->id)
                ->with(['bureaux.utilisateurs.affectations.materiel'])
                ->get();

            // Build stats per service
            $servicesStats = $services->map(function ($service) {
                $userIds = $service->bureaux
                    ->flatMap(fn ($b) => $b->utilisateurs)
                    ->pluck('id');

                $totalMateriels = Affectation::whereIn('id_utilisateur', $userIds)
                    ->whereNull('date_retour')
                    ->count();

                $agentsCount = $userIds->count();

                return [
                    'service' => $service,
                    'totalMateriels' => $totalMateriels,
                    'agentsCount' => $agentsCount,
                    'bureauxCount' => $service->bureaux->count(),
                    'userIds' => $userIds,
                ];
            });

            $grandTotal = $servicesStats->sum('totalMateriels');

            return view('mon_materiel.directeur', compact('direction', 'servicesStats', 'grandTotal'));
        }

        return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
    }
}
