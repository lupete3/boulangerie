<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsRedirected;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResponseIsRedirected | View
    {
        //Show allFournisseurs

        $viewData = [];

        $viewData['title'] = 'Liste des fournisseurs ';

        $viewData['fournisseurs'] = Fournisseur::orderBy('id', 'DESC')->get();

        return view('fournisseurs.index')->with('viewData', $viewData);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //Afficher le formulaire d'ajout du fournisseur

        $viewData = [];

        $viewData['title'] = 'Ajouter un fournisseur';

        return view('fournisseurs.create')->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse 
    {

        $request->validate([

            'nom' => [
                'required',
            ],

        ],[

            'nom.required' => 'Compléter le champ nom fournisseur',

        ]);

        $fournisseur = Fournisseur::create([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            
        ]);

        return redirect()->back()->with('success','Fournisseur ajouté avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fournisseur $fournisseur): RedirectResponse | View
    {
        //Show Edit Scolar Year With Row Data

        $viewData = [];

        $viewData['title'] = $fournisseur->nom;

        return view('fournisseurs.update', compact('fournisseur'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur): RedirectResponse
    {

        $request->validate([

            'nom' => [
                'required',
            ],

        ],[

            'nom.required' => 'Compléter le champ nom de catégorie',

        ]);

        $fournisseur->update([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email, 
        ]);

        return redirect()->back()->with('success', 'Mise à jour effectuée avec succès !');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        return redirect()->back()->with('success', 'Suppression effectuée avec succès !');
    }
}