<?php

namespace App\Http\Controllers;

use App\Models\Boisson;
use App\Models\Nourriture;
use App\Models\StockBoulangerie;
use App\Models\StockPf;
use App\Models\Table;
use App\Models\Vente;
use Illuminate\Http\Request;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewData = [];

        $viewData['title'] = 'Historique des ventes';

        $viewData['ventes'] = Vente::orderBy('id', 'desc')->with('stockBoulangerie')->get();

        return view('ventes.index')->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $viewData = [];

        $viewData['title'] = 'Ajouter une vente';

        $viewData['produits'] = StockBoulangerie::with('stockProduitFinis')->get();

        return view('ventes.create')->with('viewData', $viewData);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'quantite' => 'required|numeric',
            'produit_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'quantite.numeric' => 'Entrer un nombre ',
            'produit_id.required' => 'Choisir un produit',

        ]);

        $produit = StockBoulangerie::where('stock_pf_id',$request->produit_id)->with('stockProduitFinis')->first();

        if($produit->solde < $request->quantite)
        {
            return redirect()->back()->with('error','Cette quantité n\'est pas didponible dans le stock');
        }

        $vente = Vente::create([
            'designation' => $produit->stockProduitFinis->designation,
            'quantite' => $request->quantite,
            'prix' => $produit->stockProduitFinis->prix,
            'reste' => $produit->solde - $request->quantite,
            'stock_pf_id' => $request->produit_id,
            'observation' => $request->observation
        ]);

        if($vente)
        {
            $produit->update([
                'solde' => $produit->solde - $request->quantite
            ]);
        }

        return redirect()->back()->with('success','Vente effectuée avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(Vente $vente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vente $vente)
    {
        $viewData = [];

        $viewData['title'] = 'Mise à jour de la vente';

        $viewData['produits'] = StockBoulangerie::with('stockProduitFinis')->get();

        return view('ventes.update', compact('vente'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vente $vente)
    {
        //Mise a jour des donnes dans la base de donnees
        $request->validate([

            'quantite' => 'required|numeric',
            'produit_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'quantite.numeric' => 'Entrer un nombre ',
            'produit_id.required' => 'Choisir un produit',

        ]);

        $produit = StockBoulangerie::where('stock_pf_id',$vente->stock_pf_id)->with('stockProduitFinis')->first();

        $produit->update([
            'solde' => $produit->solde + $vente->quantite
        ]);

        $newProduit = StockBoulangerie::where('stock_pf_id',$request->produit_id)->with('stockProduitFinis')->first();

        $newProduit->update([
            'solde' => $newProduit->solde - $request->quantite
        ]);

        $vente->update([
            'designation' => $newProduit->stockProduitFinis->designation,
            'quantite' => $request->quantite,
            'prix' => $newProduit->stockProduitFinis->prix,
            'reste' => $newProduit->solde,
            'stock_pf_id' => $request->produit_id,
            'observation' => $request->observation
        ]);

        return redirect()->back()->with('success', 'Mise à jour effectuée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vente $vente)
    {
        //Suppression de la vente
        $produit = StockBoulangerie::where('stock_pf_id',$vente->stock_pf_id)->with('stockProduitFinis')->first();

        $produit->update([
            'solde' => $produit->solde + $vente->quantite
        ]);

        $vente->delete();

        return redirect()->back()->with('success', 'Suppression effectuée avec succès !');
    }
}
