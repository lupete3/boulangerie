<?php

namespace App\Http\Controllers;

use App\Models\StockBoulangerie;
use App\Models\StockPf;
use Illuminate\Http\Request;

class StockPfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Liste des matieres premiers

        $viewData = [];

        $viewData['title'] = 'Liste des produits finis ';

        $viewData['produits'] = StockPf::orderBy('designation', 'ASC')->get();

        return view('stock_pf.index')->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Afficher le formulaire d'ajout de la matière première

        $viewData = [];

        $viewData['title'] = 'Ajouter un produit finis';

        return view('stock_pf.create')->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Enregistrement d'une matière première

        $request->validate([

            'designation' => [
                'required',
                'unique:stock_pfs,designation',
            ],
            'prix' => 'required',
            'solde' => 'required|numeric',

        ],[

            'designation.required' => 'Compléter le nom',
            'prix.required' => 'Compléter le prix d\'achat',
            'solde.required' => 'Compléter le solde disponible',
            'solde.numeric' => 'Le solde n\'est pas un nombre',
            'designation.unique' => 'Ce produit existe déjà dans le stock',

        ]);

        $stockPf = StockPf::create([
            'designation' => $request->designation,
            'prix' => $request->prix,
            'solde' => $request->solde,
        ]);

        if($stockPf){

            StockBoulangerie::create([
                'stock_pf_id' => $stockPf->id
            ]);
        
        }

        return redirect()->back()->with('success','Produit finis ajouté avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockPf $stockPf)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockPf $stockPf)
    {
        //Afficher le produit fini

        $viewData = [];

        $viewData['title'] = $stockPf->designation;

        return view('stock_pf.update', compact('stockPf'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockPf $stockPf)
    {
        //Mettre a jour la matiere premiere

        $request->validate([

            'designation' => [
                'required',
            ],
            'prix' => 'required',
            'solde' => 'required|numeric',

        ],[

            'designation.required' => 'Compléter le nom',
            'prix.required' => 'Compléter le prix d\'achat',
            'solde.required' => 'Compléter le solde disponible',
            'solde.numeric' => 'Le solde n\'est pas un nombre',

        ]);

        $stockPf->update([
            'designation' => $request->designation,
            'prix' => $request->prix,
            'solde' => $request->solde,
            
        ]);

        return redirect()->back()->with('success','Mise à jour effectuée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockPf $stockPf)
    {
        // Suppression de la matière première
        $stockPf->delete();

        return redirect()->back()->with('success', 'Suppression effectuée avec succès !');
    }
}
