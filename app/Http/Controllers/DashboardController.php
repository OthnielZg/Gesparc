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
        
        // Basic stats for everyone
        $stats = [
            'total_materiels' => Materiel::count(),
            'disponible' => Materiel::where('statut', 'disponible')->count(),
            'mes_demandes' => Demande::where('id_utilisateur', $user->id)->count(),
        ];

        // Role specific logic
        if ($user->hasRole(['DR', 'gestionnaire', 'SDREVT', 'DSI'])) {
            $stats['demandes_a_valider'] = $this->getPendingValidationsCount($user);
        }

        if ($user->hasRole(['admin'])) {
            $stats['total_users'] = User::count();
        }

        return view('dashboard', compact('stats'));
    }

    private function getPendingValidationsCount($user)
    {
        // Simple logic based on status
        $statusMap = [
            'DR' => 'en_attente_dr',
            'gestionnaire' => 'transmise_gestionnaire',
            'SDREVT' => 'proposition_sdrevt',
            'DSI' => 'en_attente_dsi',
        ];

        $targetStatus = $statusMap[$user->role] ?? null;
        
        if ($targetStatus) {
            return Demande::where('statut', $targetStatus)->count();
        }

        return 0;
    }
}
