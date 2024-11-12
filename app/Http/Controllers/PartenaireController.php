<?php

namespace App\Http\Controllers;

use App\Models\Partenaire;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsRedirected;

class PartenaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResponseIsRedirected | View
    {
        //Show all partenaires

        $viewData = [];

        $viewData['title'] = 'Liste des partenaires ';

        $viewData['partenaires'] = Partenaire::orderBy('id', 'DESC')->get();

        return view('partenaires.index')->with('viewData', $viewData);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //Afficher le formulaire d'ajout du fournisseur

        $viewData = [];

        $viewData['title'] = 'Ajouter partenaire';

        return view('partenaires.create')->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse 
    {

        $request->validate([

            'nom' => 'required|unique:partenaires,nom,except,id',

        ],[

            'nom.required' => 'Compléter le champ nom du partenaire',
            'montant.unique' => 'Ce partenaire existe déjà',

        ]);

        $partenaire = Partenaire::create([
            'nom' => $request->nom,
            
        ]);

        return redirect()->route('partenaires.index')->with('success','partenaire ajouté avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(Partenaire $partenaire)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partenaire $partenaire): RedirectResponse | View
    {
        //Show Edit Scolar Year With Row Data

        $viewData = [];

        $viewData['title'] = $partenaire->nom;

        return view('partenaires.update', compact('partenaire'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partenaire $partenaire): RedirectResponse
    {

        $request->validate([

            'nom' => 'required',

        ],[

            'motif.required' => 'Compléter le champ nom du point de vente',

        ]);

        $partenaire->update([
            'nom' => $request->nom,
        ]);

        return redirect()->route('partenaires.index')->with('success', 'Mise à jour effectuée avec succès !');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partenaire $partenaire)
    {
        $partenaire->delete();

        return redirect()->route('partenaires.index')->with('success', 'Suppression effectuée avec succès !');
    }
}