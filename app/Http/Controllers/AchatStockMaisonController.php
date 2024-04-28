<?php

namespace App\Http\Controllers;

use App\Models\AchatStockMaison;
use App\Models\Fournisseur;
use App\Models\StockMaison;
use Illuminate\Http\Request;

class AchatStockMaisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Liste des achats matières premières

        $viewData = [];

        $viewData['title'] = 'Liste des achats matières premières ';

        $viewData['achatsMP'] = AchatStockMaison::with('fournisseur','stockMaison')->get();

        return view('achat-mp.index')->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Afficher le formulaire d'ajout achat matiere premiere

        $viewData = [];

        $viewData['title'] = 'Ajouter achat matière première';

        $viewData['fournisseurs'] = Fournisseur::orderBy('nom', 'ASC')->get();

        $viewData['stockMaisons'] = StockMaison::orderBy('designation', 'ASC')->get();

        return view('achat-mp.create')->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([

            'quantite' => 'required|numeric',
            'prix' => 'required|numeric',
            'fournisseur_id' => 'required',
            'stock_maison_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'prix.required' => 'Compléter le champ prix unitaire',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'prix.numeric' => 'La quantité doit être un nombre ',
            'fournisseur_id.required' => 'Choisir un fournisseur ',
            'stock_maison_id.required' => 'Choisir une matière première'

        ]);

        //Mise a jour de la quantite pièce
        $matierePremiere = StockMaison::find($request->stock_maison_id);

        $solde = $matierePremiere->solde;
        $matierePremiere->prix = $request->prix;

        $matierePremiere->solde = $solde + $request->quantite;

        $matierePremiere->save();

        //Sauvegarder les donnees liees à l'entrée stock
        $achatMP = AchatStockMaison::create([

            'prix_achat' => $request->prix,
            'quantite' => $request->quantite,
            'id_fournisseur' => $request->fournisseur_id,
            'id_stock_maisons' => $request->stock_maison_id,

        ]);

        return redirect()->back()->with('success','Achat effectué avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(AchatStockMaison $achatStockMaison)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AchatStockMaison $achatStockMaison)
    {
        //Affichier un achat stock maison

        $viewData = [];

        $viewData['title'] = 'Détail Achat matière première';

        $viewData['fournisseurs'] = Fournisseur::orderBy('nom', 'ASC')->get();

        $viewData['stockMaisons'] = StockMaison::orderBy('designation', 'ASC')->get();

        return view('achat-mp.update', compact('achatStockMaison'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AchatStockMaison $achatStockMaison)
    {

        $request->validate([

            'quantite' => 'required|numeric',
            'prix' => 'required|numeric',
            'fournisseur_id' => 'required',
            'stock_maison_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'prix.required' => 'Compléter le champ prix unitaire',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'fournisseur_id.required' => 'Choisir un fournisseur ',
            'stock_maison_id.required' => 'Choisir une matière première'

        ]);

        //Mise a jour de la quantite pièce
        $matierePremiere = StockMaison::find($achatStockMaison->id_stock_maisons);

        $solde = $matierePremiere->solde;
        $matierePremiere->prix = $request->prix;

        $matierePremiere->solde = $solde - $achatStockMaison->quantite + $request->quantite;

        $matierePremiere->save();

        //Sauvegarder les donnees liees à l'achat matieres premiere
        $achatStockMaison->update([

            'prix_achat' => $request->prix,
            'quantite' => $request->quantite,
            'id_fournisseur' => $request->fournisseur_id,
            'id_stock_maisons' => $request->stock_maison_id,

        ]);

        return redirect()->back()->with('success','Mise à jour effectuée avec succès !');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AchatStockMaison $achatStockMaison)
    {
        //SUppression de l'enregistrement
        //Mise a jour de la quantite produit
        $matierePremiere = StockMaison::find($achatStockMaison->id_stock_maisons);

        $solde = $matierePremiere->solde;

        $matierePremiere->solde = $solde - $achatStockMaison->quantite;

        $matierePremiere->save();

        $achatStockMaison->delete();

        return redirect()->back()->with('success', 'Suppression effectuée avec succès !');
    }
}
