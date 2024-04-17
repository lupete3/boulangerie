<?php

namespace App\Http\Controllers;

use App\Models\MouvementStockPf;
use App\Models\Production;
use App\Models\StockPf;
use App\Models\StockBoulangerie;
use Illuminate\Http\Request;

class MouvementStockPfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Afficher les sorties des matères premières
        $viewData = [];

        $viewData['title'] = 'Liste des sorties produits finis';

        $viewData['sorties'] = MouvementStockPf::orderBy('id', 'DESC')->with('StockPf')->get();

        return view('mouvements-pf.index')->with('viewData', $viewData);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function indexBoulangerie()
    {
        //Afficher les sorties des matères premières
        $viewData = [];

        $viewData['title'] = 'Liste des entrées produits finis';

        $viewData['entrees'] = MouvementStockPf::orderBy('id', 'DESC')->with('StockBoulangerie')->get();

        return view('mouvements-pf.indexBoulangerie')->with('viewData', $viewData);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function indexStockPf()
    {
        //Afficher les sorties des matères premières
        $viewData = [];

        $viewData['title'] = 'Liste des entrées produits finis';

        $viewData['entrees'] = Production::orderBy('id', 'DESC')->with('produitFinis')->get();

        return view('mouvements-pf.indexStockPf')->with('viewData', $viewData);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Afficher le formulaire de sortie matière première
        $viewData = [];

        $viewData['title'] = 'Ajouter une sortie produit finis';

        $viewData['produits'] = StockPf::orderBy('designation', 'ASC')->get();

        return view('mouvements-pf.create')->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Sauvegarde dans la base de donnees

        $request->validate([

            'quantite' => 'required|numeric',
            'produit_finis_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'produit_finis_id.required' => 'Choisir un produit finis ',

        ]);

        $stockPf = StockPf::find($request->produit_finis_id);

        $stockBoulangerie = StockBoulangerie::where('stock_pf_id', $request->produit_finis_id)->first();
        
        if ($stockPf->solde < $request->quantite) {
            return redirect()->back()->with('error','Cette quantité est supérieur que le solde actuel');
        }

        $stockPf->solde = $stockPf->solde - $request->quantite;

        $stockPf->save();
        
        $mouvementStockPf = MouvementStockPf::create([
            'stock_pf_id' => $request->produit_finis_id,
            'quantite' => $request->quantite,
            'reste_stock_pf' => $stockPf->solde,
            'reste_boulangerie' => $stockBoulangerie->solde + $request->quantite
        ]);

        if ($mouvementStockPf) {
            
            $stockBoulangerie = StockBoulangerie::where('stock_pf_id',$stockPf->id)->first();

            $stockBoulangerie->solde = $stockBoulangerie->solde + $request->quantite;

            $stockBoulangerie->save();

            return redirect()->back()->with('success','Sortie effectuée avec succès');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MouvementStockPf $mouvementStockPf)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MouvementStockPf $mouvementStockPf)
    {
        //Afficher le formulaire de modification sortie matière première
        $viewData = [];

        $viewData['title'] = 'Mettre à jour le mouvent de sortie produit finis';

        $viewData['produits'] = StockPf::orderBy('designation', 'ASC')->get();

        return view('mouvements-pf.update', compact('mouvementStockPf'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MouvementStockPf $mouvementStockPf)
    {
        //Sauvegarde dans la base de donnees

        $request->validate([

            'quantite' => 'required|numeric',
            'produit_finis_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'produit_finis_id.required' => 'Choisir une matière première ',

        ]);

        $stockPf = StockPf::find($request->produit_finis_id);
        
        $qnteInitMvt = $mouvementStockPf->quantite;
        $qnteInitStockPf = $mouvementStockPf->reste_stock_pf - $mouvementStockPf->quantite;
        $qnteInitStockBoulangerie = $mouvementStockPf->reste_boulangerie - $mouvementStockPf->quantite;

        if ($stockPf->solde < $request->quantite) {
            return redirect()->back()->with('error','Cette quantité est supérieur au solde actuel');
        }

        $stockPf->solde = $stockPf->solde + $mouvementStockPf->quantite - $request->quantite;

        $stockPf->save();
        
        $mouvementStockPf->update([
            'stock_pf_id' => $request->produit_finis_id,
            'quantite' => $request->quantite,
            'reste_stock_pf' => $qnteInitStockPf + $request->quantite,
            'reste_boulangerie' => $qnteInitStockBoulangerie + $request->quantite
        ]);

        if ($mouvementStockPf) {
            $stockBoulangerie = StockBoulangerie::where('stock_pf_id', $request->produit_finis_id)->first();

            $newSolde = ($stockBoulangerie->solde - $qnteInitMvt) + $request->quantite;

            $stockBoulangerie->solde = $newSolde;

            $stockBoulangerie->save();

            return redirect()->back()->with('success','Mise à jour effectuée avec succès');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MouvementStockPf $mouvementStockPf)
    {
        //Sauvegarde dans la base de donnees

        $stockPf = StockPf::find($mouvementStockPf->stock_pf_id);
        
        $qnteInitMvt = $mouvementStockPf->quantite;

        $stockPf->solde = $stockPf->solde + $qnteInitMvt;

        $stockPf->save();
        
        
        $stockBoulangerie = StockBoulangerie::where('stock_pf_id', $mouvementStockPf->stock_pf_id)->first();

        $newSolde = ($stockBoulangerie->solde - $qnteInitMvt);

        $stockBoulangerie->solde = $newSolde;

        $stockBoulangerie->save();

        $mouvementStockPf->delete();

        return redirect()->back()->with('success','Suppression effectuée avec succès');
    }
}
