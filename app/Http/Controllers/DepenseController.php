<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsRedirected;

class DepenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResponseIsRedirected | View
    {
        //Show alldepenses

        $viewData = [];

        $viewData['title'] = 'Liste des dépenses ';

        $viewData['depenses'] = Depense::orderBy('id', 'DESC')->get();

        return view('depenses.index')->with('viewData', $viewData);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //Afficher le formulaire d'ajout du fournisseur

        $viewData = [];

        $viewData['title'] = 'Ajouter une dépense';

        return view('depenses.create')->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse 
    {

        $request->validate([

            'motif' => 'required',
            'montant' => 'required|numeric',
            'personne' => 'required'

        ],[

            'motif.required' => 'Compléter le champ motif dépense',
            'montant.required' => 'Compléter le champ montant de dépense',
            'montant.numeric' => 'Le montant n\'est pas valide, il doit être un nombre',
            'personne.required' => 'Compléter le champ personne concernée',

        ]);

        $depense = Depense::create([
            'motif' => $request->motif,
            'montant' => $request->montant,
            'personne' => $request->personne,
            
        ]);

        return redirect()->back()->with('success','Dépense ajouteé avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(Depense $depense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Depense $depense): RedirectResponse | View
    {
        //Show Edit Scolar Year With Row Data

        $viewData = [];

        $viewData['title'] = $depense->motif;

        return view('depenses.update', compact('depense'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Depense $depense): RedirectResponse
    {

        $request->validate([

            'motif' => 'required',
            'montant' => 'required|numeric',
            'personne' => 'required'

        ],[

            'motif.required' => 'Compléter le champ motif dépense',
            'montant.required' => 'Compléter le champ montant de dépense',
            'montant.numeric' => 'Le montant n\'est pas valide, il doit être un nombre',
            'personne.required' => 'Compléter le champ personne concernée',

        ]);

        $depense->update([
            'motif' => $request->motif,
            'montant' => $request->montant,
            'personne' => $request->personne, 
        ]);

        return redirect()->back()->with('success', 'Mise à jour effectuée avec succès !');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Depense $depense)
    {
        $depense->delete();

        return redirect()->back()->with('success', 'Suppression effectuée avec succès !');
    }
}