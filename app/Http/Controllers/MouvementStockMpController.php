<?php

namespace App\Http\Controllers;

use App\Models\MouvementStockMp;
use App\Models\StockMaison;
use App\Models\StockUsine;
use Illuminate\Http\Request;

class MouvementStockMpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Afficher les sorties des matères prémières
        $viewData = [];

        $viewData['title'] = 'Liste des sorties matières premières';

        $viewData['sorties'] = MouvementStockMp::orderBy('id', 'DESC')->with('stockMaison')->get();

        return view('moUvements-mp.index')->with('viewData', $viewData);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function indexUsine()
    {
        //Afficher les sorties des matères prémières
        $viewData = [];

        $viewData['title'] = 'Liste des entrées matières premières';

        $viewData['entrees'] = MouvementStockMp::orderBy('id', 'DESC')->with('stockUsine')->get();

        return view('moUvements-mp.indexUsine')->with('viewData', $viewData);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Afficher le formulaire de sortie matière prémière
        $viewData = [];

        $viewData['title'] = 'Ajouter une sortie matière prémière';

        $viewData['matieres'] = StockMaison::orderBy('designation', 'ASC')->get();

        return view('moUvements-mp.create')->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Sauvegarde dans la base de donnees

        $request->validate([

            'quantite' => 'required|numeric',
            'matiere_premiere_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'matiere_premiere_id.required' => 'Choisir une matière prémière ',

        ]);

        $stockMaison = StockMaison::find($request->matiere_premiere_id);

        $stockUsine = StockUsine::where('id_stock_maisons', $request->matiere_premiere_id)->get();

        foreach ($stockUsine as $stockUsin) {
            $idStockUsine = $stockUsin->id;
            $soldeUsine = $stockUsin->solde;
        }
        
        $soldeMaison = $stockMaison->solde;

        if ($soldeMaison < $request->quantite) {
            return redirect()->back()->with('error','Cette quantité est est supérieur que le solde actuel');
        }

        $stockMaison->solde = $soldeMaison - $request->quantite;

        $stockMaison->save();
        
        $mouvementStockMp = MouvementStockMp::create([
            'id_stock_mp' => $request->matiere_premiere_id,
            'quantite' => $request->quantite,
            'reste_maison' => $soldeMaison - $request->quantite,
            'reste_usine' => $soldeUsine + $request->quantite
        ]);

        if ($mouvementStockMp) {
            
            $stockUsine = StockUsine::find($idStockUsine);

            $stockUsine->solde = $stockUsine->solde + $request->quantite;

            $stockUsine->save();

            return redirect()->back()->with('success','Sortie effectuée avec succès');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MouvementStockMp $mouvementStockMp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MouvementStockMp $mouvementStockMp)
    {
        //Afficher le formulaire de modification sortie matière prémière
        $viewData = [];

        $viewData['title'] = 'Mettre à jour le mouvent de sortie matière prémière';

        $viewData['matieres'] = StockMaison::orderBy('designation', 'ASC')->get();

        return view('moUvements-mp.update', compact('mouvementStockMp'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MouvementStockMp $mouvementStockMp)
    {
        //Sauvegarde dans la base de donnees

        $request->validate([

            'quantite' => 'required|numeric',
            'matiere_premiere_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'matiere_premiere_id.required' => 'Choisir une matière prémière ',

        ]);

        $stockMaison = StockMaison::find($request->matiere_premiere_id);
        
        $soldeMaison = $stockMaison->solde;

        $qnteInitMvt = $mouvementStockMp->quantite;

        if ($soldeMaison < $request->quantite) {
            return redirect()->back()->with('error','Cette quantité est est supérieur que le solde actuel');
        }

        $stockMaison->solde = $soldeMaison + $mouvementStockMp->quantite - $request->quantite;

        $stockMaison->save();
        
        $mouvementStockMp->update([
            'id_stock_mp' => $request->matiere_premiere_id,
            'quantite' => $request->quantite,
            'reste' => $soldeMaison + $mouvementStockMp->quantite - $request->quantite
        ]);

        if ($mouvementStockMp) {
            $stockUsine = StockUsine::where('id_stock_maisons', $request->matiere_premiere_id)->get();

            foreach ($stockUsine as $stockUsin) {
                $idStockUsine = $stockUsin->id;
            }

            $stockUsine = StockUsine::find($idStockUsine);

            $newSolde = ($stockUsine->solde - $qnteInitMvt) + $request->quantite;

            $stockUsine->solde = $newSolde;

            $stockUsine->save();

            return redirect()->back()->with('success','Mise à jour effectuée avec succès');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MouvementStockMp $mouvementStockMp)
    {
        //Sauvegarde dans la base de donnees

        $stockMaison = StockMaison::find($mouvementStockMp->id_stock_mp);
        
        $soldeMaison = $stockMaison->solde;

        $qnteInitMvt = $mouvementStockMp->quantite;

        $stockMaison->solde = $soldeMaison + $qnteInitMvt;

        $stockMaison->save();
        
        
        $stockUsine = StockUsine::where('id_stock_maisons', $mouvementStockMp->id_stock_mp)->get();

        foreach ($stockUsine as $stockUsin) {
            $idStockUsine = $stockUsin->id;
        }

        $stockUsine = StockUsine::find($idStockUsine);

        $newSolde = ($stockUsine->solde - $qnteInitMvt);

        $stockUsine->solde = $newSolde;

        $stockUsine->save();

        $mouvementStockMp->delete();

        return redirect()->back()->with('success','Suppression effectuée avec succès');
    }
}
