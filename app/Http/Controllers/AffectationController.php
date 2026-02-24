<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AffectationController extends Controller
{
    public function index()
    {
        $affectations = Affectation::with(['demande.utilisateur', 'materiel', 'utilisateur'])->latest()->paginate(10);
        return view('affectations.index', compact('affectations'));
    }

    public function download(Affectation $affectation)
    {
        $affectation->load(['demande.utilisateur', 'materiel', 'utilisateur']);
        
        $pdf = Pdf::loadView('affectations.bordereau', [
            'affectation' => $affectation,
            'date' => now()->format('d/m/Y'),
        ]);

        return $pdf->download('Bordereau-' . $affectation->reference_bordereau . '.pdf');
    }
}
