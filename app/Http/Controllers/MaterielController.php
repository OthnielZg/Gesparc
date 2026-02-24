<?php

namespace App\Http\Controllers;

use App\Models\Materiel;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class MaterielController extends Controller
{
    public function index(Request $request)
    {
        $query = Materiel::with('fournisseur');

        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('numero_serie', 'like', '%' . $request->search . '%')
                  ->orWhere('marque', 'like', '%' . $request->search . '%')
                  ->orWhere('modele', 'like', '%' . $request->search . '%')
                  ->orWhere('categorie', 'like', '%' . $request->search . '%');
            });
        }

        $materiels = $query->latest()->paginate(10);

        return view('materiels.index', compact('materiels'));
    }

    public function create()
    {
        $fournisseurs = Fournisseur::orderBy('nom_fournisseur')->get();
        $categories = Materiel::categories();
        return view('materiels.create', compact('fournisseurs', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_serie' => 'required|string|unique:materiels,numero_serie',
            'categorie' => 'required|string',
            'marque' => 'required|string',
            'modele' => 'required|string',
            'statut' => 'required|in:disponible,affecte,maintenance,hors_service',
            'prix_acquisition' => 'nullable|numeric',
            'id_fournisseur' => 'nullable|exists:fournisseurs,id',
            'description' => 'nullable|string',
        ]);

        Materiel::create($validated);

        return redirect()->route('materiels.index')->with('success', 'Matériel ajouté avec succès.');
    }

    public function show(Materiel $materiel)
    {
        return view('materiels.show', compact('materiel'));
    }

    public function edit(Materiel $materiel)
    {
        $fournisseurs = Fournisseur::orderBy('nom_fournisseur')->get();
        $categories = Materiel::categories();
        return view('materiels.edit', compact('materiel', 'fournisseurs', 'categories'));
    }

    public function update(Request $request, Materiel $materiel)
    {
        $validated = $request->validate([
            'numero_serie' => 'required|string|unique:materiels,numero_serie,' . $materiel->id,
            'categorie' => 'required|string',
            'marque' => 'required|string',
            'modele' => 'required|string',
            'statut' => 'required|in:disponible,affecte,maintenance,hors_service',
            'prix_acquisition' => 'nullable|numeric',
            'id_fournisseur' => 'nullable|exists:fournisseurs,id',
            'description' => 'nullable|string',
        ]);

        $materiel->update($validated);

        return redirect()->route('materiels.index')->with('success', 'Matériel mis à jour.');
    }

    public function destroy(Materiel $materiel)
    {
        $materiel->delete();
        return redirect()->route('materiels.index')->with('success', 'Matériel supprimé.');
    }
}
