<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Direction;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegionalOverviewController extends Controller
{
    /**
     * Display the Regional Matrix for the DR.
     */
    public function index()
    {
        $user = Auth::user();

        // Security check
        if ($user->role !== 'DR') {
            abort(403, "Seul un Directeur Régional peut accéder à cette vue.");
        }

        $villeId = $user->direction?->id_ville;
        $directions = collect();
        $stats = [];

        if ($villeId) {
            // Aggregated directions in the region/city
            $directions = Direction::where('id_ville', $villeId)
                ->with(['services.bureaux'])
                ->get()
                ->map(function($direction) {
                    // Total demands for this specific direction
                    $direction->total_demandes = Demande::whereHas('utilisateur', function($q) use ($direction) {
                        $q->where('id_direction', $direction->id)
                          ->orWhereHas('bureau.service', fn($sq) => $sq->where('id_direction', $direction->id));
                    })->count();
                    
                    // Top categories demanded in this direction
                    $direction->top_categories = Demande::whereHas('utilisateur', function($q) use ($direction) {
                        $q->where('id_direction', $direction->id)
                          ->orWhereHas('bureau.service', fn($sq) => $sq->where('id_direction', $direction->id));
                    })
                    ->select('categorie_demandee', DB::raw('count(*) as total'))
                    ->groupBy('categorie_demandee')
                    ->orderByDesc('total')
                    ->limit(3)
                    ->get();
                    
                    return $direction;
                });

            // Global regional KPIs
            $stats = [
                'total_directions' => $directions->count(),
                'total_services' => Service::whereHas('direction', fn($q) => $q->where('id_ville', $villeId))->count(),
                'total_demandes' => $directions->sum('total_demandes'),
                'nom_ville' => $user->direction?->ville?->nom_ville ?? 'Ma Région',
            ];
        }

        return view('dr.ma-region', compact('directions', 'stats'));
    }
}
