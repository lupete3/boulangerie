<?php

namespace App\Http\Controllers;

use App\Models\Cloture;
use App\Models\Site;
use App\Models\StockBoulangerie;
use App\Models\synthese;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClotureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function synthese(Site $site)
    {
        //Liste des produits

        $viewData = [];

        $viewData['title'] = 'Synthèse invetaire de '.$site->nom;

        $viewData['syntheses'] = synthese::where('site_id', $site->id)->with('site','user')->get();

        return view('stock_boulangerie.synthese',compact('site'))->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function cloture(Site $site, Request $request)
    {
        $request->validate([
            'entree' => 'required',
            'solde' => 'required',
        ]);

        $produit = StockBoulangerie::where('site_id', $site->id)->where('id',$request->produit_id)->with('stockProduitFinis')->first();

        $cloture = Cloture::create([
            'qnte_entree' => $request->entree,
            'qnte_sortie' => $request->entree - $request->solde,
            'avarie' => $request->avarie,
            'consommation' => $request->consommation,
            'solde' => $request->solde,
            'prix' => $produit->stockProduitFinis->prix,
            'stock_pf_id' => $produit->id,
            'site_id' => $site->id,
            'user_id' => Auth::user()->id,
        ]);

        $produit->update([
            'solde' => $request->solde,
            'updated_at' => Carbon::now()
        ]);

        return redirect()->back()->with('success',$produit->designation.'cloturé avec succès');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function clotureBoulangerie(Site $site, Request $request)
    {

        $total = $request->vente - ($request->avarie + $request->depense + $request->consommation + $request->dette) + $request->change; 

        if($request->espece > $total)
        {
            return redirect()->back()->with('error','Attention! Vueillez revoir vos calculs');
        }
        synthese::create([
            'vente' => $request->vente,
            'avarie' => $request->avarie,
            'depense' => $request->depense,
            'consommation' => $request->consommation,
            'dette' => $request->dette,
            'change' => $request->change,
            'total' => $total,
            'espece' => $request->espece,
            'manquant' => $total - $request->espece,
            'site_id' => $site->id,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success','Cloturé avec succès');
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
    }

    /**
     * Display the specified resource.
     */
    public function show(Cloture $cloture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cloture $cloture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cloture $cloture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cloture $cloture)
    {
        //
    }
}
