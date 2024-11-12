<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Composition;
use App\Models\Production;
use App\Models\Produit;
use App\Models\StockPf;
use App\Models\StockUsine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $viewData = [];

        $viewData['title'] = 'Liste des productions ';

        // Par défaut, la date est celle du jour, sinon on prend la date passée dans la requête
        $date = $request->input('date', Carbon::now()->toDateString());

        // Récupérer toutes les productions de la date sélectionnée, groupées par produit
        $productions = Produit::with(['productions' => function($query) use ($date) {
            $query->whereDate('created_at', $date);
        }])->withSum(['productions as quantity_produced' => function ($query) use ($date) {
            $query->whereDate('created_at', $date);
        }], 'quantity')
        ->withSum(['commandes as quantity_demande' => function ($query) use ($date) {
            $query->whereDate('created_at', $date);
        }], 'nbre_produit')
        ->withSum(['commandes as quantity_kg_demande' => function ($query) use ($date) {
            $query->whereDate('created_at', $date);
        }], 'nbre_kg')
        ->get();

        return view('productions.index', [
            'productions' => $productions,
            'selectedDate' => $date,
        ])->with('viewData', $viewData);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Afficher le formulaire d'ajout achat matiere premiere

        $viewData = [];

        $viewData['title'] = 'Ajouter une production';

        // Récupérer la date du jour
        $today = Carbon::now()->toDateString();

        // Récupérer tous les produits avec la quantité demandée en fonction des commandes du jour
        $produits = Produit::withSum(['commandes as quantity_demande' => function ($query) use ($today) {
            $query->whereDate('created_at', $today)
            ->where('status', 0);
        }], 'nbre_kg')
        ->withSum(['commandes as quantity_demande_prod' => function ($query) use ($today) {
            $query->whereDate('created_at', $today)
            ->where('status', 0);
        }], 'nbre_produit')->get();

        return view('productions.create', [
            'produits' => $produits,
        ])->with('viewData', $viewData);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'productions' => 'required|array',
            'productions.*.quantity' => 'required|numeric|min:0',
        ], [
            'productions.required' => 'Veuillez compléter les quantités pour chaque produit.',
            'productions.*.quantity.numeric' => 'La quantité produite doit être un nombre.',
            'productions.*.quantity.min' => 'La quantité produite doit être positive.',
        ]);

        foreach ($request->productions as $produitId => $data) {
            if($data['quantity_demande'] == null){
                return redirect()->back()->with('error', 'Aucune commande trouvée.');
            }
            
            // Enregistrer la production
            Production::create([
                'produit_id' => $produitId,
                'user_id' => Auth::id(),
                'quantity_demande' => $data['quantity_demande'],
                'quantity' => $data['quantity'],
            ]);

            // Mettre à jour le statut de la commande liée
            Commande::where('produit_id', $produitId)
                ->where('status', 0) // Assurez-vous de n'affecter que les commandes non traitées
                ->update(['status' => 1]);
        }

        return redirect()->route('productions.index')->with('success', 'Production enregistrée avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Production $production)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Production $production)
    {
        //Afficher le formulaire de modification de production

       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Production $production)
    {


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Production $production)
    {
        //
    }
}
