<?php

namespace App\Http\Controllers;

use App\Models\AchatStockMaison;
use App\Models\DetteFournisseur;
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
            'montant_paye' => 'required|numeric|min:0',
            'fournisseur_id' => 'required',
            'stock_maison_id' => 'required',
        ], [
            'quantite.required' => 'Compléter le champ quantité',
            'prix.required' => 'Compléter le champ prix unitaire',
            'montant_paye.required' => 'Compléter le montant payé',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'prix.numeric' => 'Le prix doit être un nombre ',
            'montant_paye.numeric' => 'Le montant payé doit être un nombre ',
            'fournisseur_id.required' => 'Choisir un fournisseur ',
            'stock_maison_id.required' => 'Choisir une matière première'
        ]);

        // Mise à jour du stock
        $matierePremiere = StockMaison::find($request->stock_maison_id);
        $matierePremiere->prix = $request->prix;
        $matierePremiere->solde += $request->quantite;
        $matierePremiere->save();

        // Création de l'achat
        $achatMP = AchatStockMaison::create([
            'prix_achat' => $request->prix,
            'quantite' => $request->quantite,
            'montant_paye' => $request->montant_paye,
            'id_fournisseur' => $request->fournisseur_id,
            'id_stock_maisons' => $request->stock_maison_id,
        ]);

        // Vérifier si une dette existe
        $totalAchat = $request->prix * $request->quantite;
        if ($request->montant_paye < $totalAchat) {
            $montantDette = $totalAchat - $request->montant_paye;

            DetteFournisseur::create([
                'id_fournisseur' => $request->fournisseur_id,
                'id_achat' => $achatMP->id,
                'montant_dette' => $montantDette,
                'reste_a_payer' => $montantDette,
                'est_soldee' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Achat effectué avec succès');
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantite' => 'required|numeric',
            'prix' => 'required|numeric',
            'montant_paye' => 'required|numeric|min:0',
            'fournisseur_id' => 'required',
            'stock_maison_id' => 'required',
        ], [
            'quantite.required' => 'Compléter le champ quantité',
            'prix.required' => 'Compléter le champ prix unitaire',
            'montant_paye.required' => 'Compléter le montant payé',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'prix.numeric' => 'Le prix doit être un nombre ',
            'montant_paye.numeric' => 'Le montant payé doit être un nombre ',
            'fournisseur_id.required' => 'Choisir un fournisseur ',
            'stock_maison_id.required' => 'Choisir une matière première'
        ]);

        $achat = AchatStockMaison::findOrFail($id);
        $ancienneQuantite = $achat->quantite;

        // Mise à jour du stock
        $matierePremiere = StockMaison::find($request->stock_maison_id);

        // Ajustement du stock : retirer l'ancienne quantité puis ajouter la nouvelle
        if ($achat->id_stock_maisons == $request->stock_maison_id) {
            // Même matière première
            $matierePremiere->solde = $matierePremiere->solde - $ancienneQuantite + $request->quantite;
        } else {
            // Si la matière première a changé, on ajuste l’ancien et le nouveau stock
            $ancienneMP = StockMaison::find($achat->id_stock_maisons);
            $ancienneMP->solde -= $ancienneQuantite;
            $ancienneMP->save();

            $matierePremiere->solde += $request->quantite;
        }

        // Mettre à jour le prix
        $matierePremiere->prix = $request->prix;
        $matierePremiere->save();

        // Mise à jour de l'achat
        $achat->update([
            'prix_achat' => $request->prix,
            'quantite' => $request->quantite,
            'montant_paye' => $request->montant_paye,
            'id_fournisseur' => $request->fournisseur_id,
            'id_stock_maisons' => $request->stock_maison_id,
        ]);

        // Mise à jour de la dette fournisseur
        $totalAchat = $request->prix * $request->quantite;

        $dette = DetteFournisseur::where('id_achat', $achat->id)->first();

        if ($request->montant_paye < $totalAchat) {
            $montantDette = $totalAchat - $request->montant_paye;

            if ($dette) {
                // Mise à jour de la dette existante
                $dette->montant_dette = $montantDette;
                $dette->reste_a_payer = $montantDette;
                $dette->est_soldee = false;
                $dette->save();
            } else {
                // Créer une nouvelle dette
                DetteFournisseur::create([
                    'id_fournisseur' => $request->fournisseur_id,
                    'id_achat' => $achat->id,
                    'montant_dette' => $montantDette,
                    'reste_a_payer' => $montantDette,
                    'est_soldee' => false,
                ]);
            }
        } else {
            // Si l'achat est entièrement payé, supprimer la dette s'il y en avait une
            if ($dette) {
                $dette->delete();
            }
        }

        return redirect()->back()->with('success', 'Achat modifié avec succès');
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
