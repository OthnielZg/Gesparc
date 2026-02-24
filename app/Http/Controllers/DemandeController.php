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

        // Filter by role: Agents only see their own. Others see all or filtered.
        if ($user->hasRole(['agent'])) {
            $query->where('id_utilisateur', $user->id);
        }

        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        $demandes = $query->latest()->paginate(10);

        return view('demandes.index', compact('demandes'));
    }

    public function create()
    {
        return view('demandes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie_demandee' => 'required|string',
            'quantite' => 'required|integer|min:1',
            'motif_demande' => 'required|string',
            'priorite' => 'required|in:basse,normale,haute,urgente',
        ]);

        $demande = new Demande($validated);
        $demande->reference = Demande::generateReference();
        $demande->id_utilisateur = Auth::id();
        $demande->statut = 'pre_demande';
        $demande->save();

        return redirect()->route('demandes.index')->with('success', 'Votre pré-demande a été enregistrée.');
    }

    public function show(Demande $demande)
    {
        $demande->load(['utilisateur', 'chefCdi', 'validations.utilisateur', 'affectation.materiel']);
        return view('demandes.show', compact('demande'));
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
