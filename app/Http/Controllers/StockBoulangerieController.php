<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\StockBoulangerie;
use App\Models\StockPf;
use Illuminate\Http\Request;

class StockBoulangerieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Site $site)
    {
        //Liste des produits

        $viewData = [];

        $viewData['title'] = 'Liste des produits du point de vente '.$site->nom;

        $viewData['produits'] = StockBoulangerie::where('site_id', $site->id)->with('stockProduitFinis')->get();

        $viewData['produits_finis'] = StockPf::orderBy('designation', 'ASC')->get();

        return view('stock_boulangerie.index',compact('site'))->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([

            'solde' => 'required|numeric',

        ],[

            'solde.required' => 'Compléter le champ solde disponible',
            'solde.numeric' => 'Le solde n\'est pas un nombre',

        ]);

        $produitExist = StockBoulangerie::where('site_id', $request->site_id)->where('stock_pf_id',$request->produit_finis_id)->with('stockProduitFinis')->get();

        if(count($produitExist)){
            return redirect()->back()->with('error','Ce produit existe déjà dans le stock');
        }
        $produit = StockBoulangerie::create([
            'solde' => $request->solde,
            'stock_pf_id' => $request->produit_finis_id,
            'site_id' => $request->site_id
        ]);

        return redirect()->back()->with('success','Produit ajouté avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockBoulangerie $stockBoulangerie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockBoulangerie $stockBoulangerie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockBoulangerie $stockBoulangerie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockBoulangerie $stockBoulangerie)
    {
        //
    }
}
