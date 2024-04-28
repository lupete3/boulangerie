<?php

namespace App\Http\Controllers;

use App\Models\StockMaison;
use App\Models\StockUsine;
use Illuminate\Http\Request;

class StockMaisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Liste des matieres premiers

        $viewData = [];

        $viewData['title'] = 'Liste des matières premières ';

        $viewData['matiresPremieres'] = StockMaison::orderBy('id', 'DESC')->get();

        return view('stock_maison.index')->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Afficher le formulaire d'ajout de la matière première

        $viewData = [];

        $viewData['title'] = 'Ajouter une matière première';

        return view('stock_maison.create')->with('viewData', $viewData);
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
            ],
            'unite' => 'required',
            'prix' => 'required|numeric',
            'solde' => 'numeric'

        ],[

            'designation.required' => 'Compléter le nom',
            'designation.required' => 'Compléter le champ unité de mesure',
            'prix.required' => 'Compléter le prix d\'achat',
            'prix.numeric' => 'Le prix doit être un nombre',
            'designation.numeric' => 'La quantité en stock doit être un nombre',

        ]);

        $stockMaison = new StockMaison();

        $stockMaison->designation = $request->designation;
        $stockMaison->unite = $request->unite;
        $stockMaison->prix = $request->prix;
        $stockMaison->solde = $request->solde;

        $stockMaison->save();

        if($stockMaison){
            
            $stockUsine = new StockUsine();

            $stockUsine->id_stock_maisons = $stockMaison->id;

            $stockUsine->save();
    
        }

        return redirect()->back()->with('success','Matière première ajoutée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockMaison $stockMaison)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockMaison $stockMaison)
    {
        //Afficher la matiere premiere

        $viewData = [];

        $viewData['title'] = $stockMaison->designation;

        return view('stock_maison.update', compact('stockMaison'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockMaison $stockMaison)
    {
        //Mettre a jour la matiere premiere

        $request->validate([

            'designation' => [
                'required',
            ],
            'unite' => 'required',
            'prix' => 'required|numeric',
            'solde' => 'numeric'

        ],[

            'designation.required' => 'Compléter le nom',
            'designation.required' => 'Compléter le champ unité de mesure',
            'prix.required' => 'Compléter le prix d\'achat',
            'prix.numeric' => 'Le prix doit être un nombre',
            'designation.numeric' => 'La quantité en stock doit être un nombre',

        ]);

        $stockMaison->update([
            'designation' => $request->designation,
            'unite' => $request->unite,
            'prix' => $request->prix,
            'solde' => $request->solde,
            
        ]);

        return redirect()->back()->with('success','Mise à jour effectuée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockMaison $stockMaison)
    {
        // Suppression de la matière première
        $stockMaison->delete();

        return redirect()->back()->with('success', 'Suppression effectuée avec succès !');
    }
}
