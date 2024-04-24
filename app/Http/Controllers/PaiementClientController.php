<?php

namespace App\Http\Controllers;

use App\Models\CommandeClient;
use App\Models\PaiementClient;
use Illuminate\Http\Request;

class PaiementClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //
        $viewData = [];

        $viewData['title'] = 'Historique des paiements clients';

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->with('paiements')->get();

        return view('paiements_clients.index')->with('viewData', $viewData);
    }

    /**
     * Display a listing of the resource.
     */
    public function detteClients()
    {
        //
        $viewData = [];

        $viewData['title'] = 'Liste des dettes clients';

        $viewData['commandes'] = CommandeClient::where('reste', '>', 0)->orderBy('id', 'DESC')->with('ventes')->get();

        return view('dettes_clients.index')->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CommandeClient $commandeClient)
    {
        //
        $viewData = [];

        $viewData['title'] = 'Coammande client '.$commandeClient->client->nom ;

        return view('dettes_clients.create', compact('commandeClient'))->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'commandeClient' => 'required',
            'montant' => 'required|numeric'
        ],[
            'commandeClient.requires' => 'Commande non valide',
            'montant.required' => 'Completer le montant',
            'montant.numeric' => 'Le montant n\'est pas un nombre',
        ]);

        $commandeClient = CommandeClient::find($request->commandeClient);

        $commandeClient->update([
            'reste' => $commandeClient->reste - $request->montant,
            'paye' => $commandeClient->paye + $request->montant
        ]);

        PaiementClient::create([
            'montant' => $request->montant,
            'reste' => $commandeClient->reste,
            'commande_client_id' => $commandeClient->id,
            'client_id' => $commandeClient->client_id
        ]);

        return redirect()->back()->with('success','Paiement effectué avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaiementClient $paiementClient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaiementClient $paiementClient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaiementClient $paiementClient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaiementClient $paiementClient)
    {
        //
    }
}
