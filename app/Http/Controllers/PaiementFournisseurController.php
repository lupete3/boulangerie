<?php

namespace App\Http\Controllers;

use App\Models\DetteFournisseur;
use App\Models\Fournisseur;
use App\Models\PaiementFournisseur;
use Illuminate\Http\Request;

class PaiementFournisseurController extends Controller
{
    public function index()
    {
        $viewData['title'] = 'Liste des paiements fournisseurs';

        $paiements = PaiementFournisseur::with(['dette.fournisseur', 'dette.achat'])
            ->latest()
            ->get();

        return view('paiements_fournisseurs.index', compact('paiements'))->with('viewData', $viewData);
    }


    public function create($fournisseurId)
    {
        $viewData['title'] = 'Liste des dettes clients';

        $fournisseurs = Fournisseur::find($fournisseurId);

        $dettes = DetteFournisseur::when($fournisseurId, function ($query) use ($fournisseurId) {
            return $query->where('id_fournisseur', $fournisseurId)
                        ->where('est_soldee', false);
        })->get();

        return view('paiements_fournisseurs.create', compact('fournisseurs', 'dettes'))->with('viewData', $viewData);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dette_fournisseur_id' => 'required|exists:dette_fournisseurs,id',
            'montant' => 'required|numeric|min:0.01',
            'date_paiement' => 'date',
            'mode_paiement' => 'nullable|string|max:50',
            'observation' => 'nullable|string'
        ]);

        $dette = DetteFournisseur::findOrFail($request->dette_fournisseur_id);

        if ($request->montant > $dette->reste_a_payer) {
            return back()->withErrors(['montant' => 'Le montant dépasse la dette restante.']);
        }

        // Enregistrer le paiement
        PaiementFournisseur::create([
            'dette_fournisseur_id' => $dette->id,
            'montant' => $request->montant,
            'mode_paiement' => $request->mode_paiement,
            'observation' => $request->observation,
        ]);

        // Mettre à jour la dette
        $dette->reste_a_payer -= $request->montant;
        if ($dette->reste_a_payer <= 0) {
            $dette->reste_a_payer = 0;
            $dette->est_soldee = true;
        }
        $dette->save();

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
    }

    public function dettesParFournisseur($fournisseurId)
    {
        $dettes = DetteFournisseur::where('id_fournisseur', $fournisseurId)
            ->where('est_soldee', false)
            ->with('achat') // Si tu veux plus d'infos sur l'achat
            ->get();

        return response()->json($dettes);
    }


}
