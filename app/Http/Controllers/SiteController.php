<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsRedirected;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResponseIsRedirected | View
    {
        //Show all$sites

        $viewData = [];

        $viewData['title'] = 'Liste des points de vente ';

        $viewData['sites'] = Site::orderBy('id', 'DESC')->get();

        return view('sites.index')->with('viewData', $viewData);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //Afficher le formulaire d'ajout du fournisseur

        $viewData = [];

        $viewData['title'] = 'Ajouter une point de vente';

        return view('sites.create')->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse 
    {

        $request->validate([

            'nom' => 'required|unique:sites,nom,except,id',

        ],[

            'nom.required' => 'Compléter le champ nom du point de vente',
            'montant.unique' => 'Ce point de vente existe déjà',

        ]);

        $site = Site::create([
            'nom' => $request->nom,
            
        ]);

        return redirect()->back()->with('success','point de vente ajouté avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site): RedirectResponse | View
    {
        //Show Edit Scolar Year With Row Data

        $viewData = [];

        $viewData['title'] = $site->nom;

        return view('sites.update', compact('site'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site): RedirectResponse
    {

        $request->validate([

            'nom' => 'required',

        ],[

            'motif.required' => 'Compléter le champ nom du point de vente',

        ]);

        $site->update([
            'nom' => $request->nom,
        ]);

        return redirect()->back()->with('success', 'Mise à jour effectuée avec succès !');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        $site->delete();

        return redirect()->back()->with('success', 'Suppression effectuée avec succès !');
    }
}