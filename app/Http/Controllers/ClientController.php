<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsRedirected;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResponseIsRedirected | View
    {
        //Show allclients

        $viewData = [];

        $viewData['title'] = 'Liste des clients ';

        $viewData['clients'] = Client::orderBy('id', 'DESC')->get();

        return view('clients.index')->with('viewData', $viewData);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //Afficher le formulaire d'ajout du client

        $viewData = [];

        $viewData['title'] = 'Ajouter un client';

        return view('clients.create')->with('viewData', $viewData);
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

            'nom.required' => 'Compléter le champ nom client',

        ]);

        $client = Client::create([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            
        ]);

        return redirect()->back()->with('success','client ajouté avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(client $client): RedirectResponse | View
    {
        //Show Edit Scolar Year With Row Data

        $viewData = [];

        $viewData['title'] = $client->nom;

        return view('clients.update', compact('client'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, client $client): RedirectResponse
    {

        $request->validate([

            'nom' => [
                'required',
            ],

        ],[

            'nom.required' => 'Compléter le champ nom de catégorie',

        ]);

        $client->update([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse, 
        ]);

        return redirect()->back()->with('success', 'Mise à jour effectuée avec succès !');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(client $client)
    {
        $client->delete();

        return redirect()->back()->with('success', 'Suppression effectuée avec succès !');
    }
}