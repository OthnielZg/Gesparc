<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ville;
use App\Models\Direction;
use App\Models\Service;
use App\Models\Bureau;
use Illuminate\Http\Request;

class OrganisationController extends Controller
{
    public function index()
    {
        $villes = Ville::with('directions.services.bureaux')->get();
        return view('admin.organisation.index', compact('villes'));
    }

    public function storeVille(Request $request)
    {
        $request->validate(['nom_ville' => 'required|string|max:255']);
        Ville::create($request->all());
        return back()->with('success', 'Ville ajoutée.');
    }

    public function storeDirection(Request $request)
    {
        $request->validate(['nom_direction' => 'required|string|max:255', 'id_ville' => 'required|exists:villes,id']);
        Direction::create($request->all());
        return back()->with('success', 'Direction ajoutée.');
    }

    public function storeService(Request $request)
    {
        $request->validate(['nom_service' => 'required|string|max:255', 'id_direction' => 'required|exists:directions,id']);
        Service::create($request->all());
        return back()->with('success', 'Service ajouté.');
    }

    public function storeBureau(Request $request)
    {
        $request->validate(['nom_bureau' => 'required|string|max:255', 'id_service' => 'required|exists:services,id']);
        Bureau::create($request->all());
        return back()->with('success', 'Bureau ajouté.');
    }

    // Deletion methods
    public function destroyVille(Ville $ville) { $ville->delete(); return back()->with('success', 'Ville supprimée.'); }
    public function destroyDirection(Direction $direction) { $direction->delete(); return back()->with('success', 'Direction supprimée.'); }
    public function destroyService(Service $service) { $service->delete(); return back()->with('success', 'Service supprimé.'); }
    public function destroyBureau(Bureau $bureau) { $bureau->delete(); return back()->with('success', 'Bureau supprimé.'); }
}
