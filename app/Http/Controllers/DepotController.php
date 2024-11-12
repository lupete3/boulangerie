<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Produit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Afficher tous les dépôts du jour par défaut, avec possibilité de filtrer par date
    public function index(Request $request)
    {
        $viewData['title'] = 'Mouvements Dépôt';

        $date = $request->input('date', Carbon::now()->toDateString());

        // Récupérer tous les dépôts pour la date donnée, groupés par produit
        $depots = Produit::with(['depots' => function($query) use ($date) {
            $query->whereDate('created_at', $date);
        }])->withSum(['depots as quantity_produite' => function ($query) use ($date) {
            $query->whereDate('created_at', $date);
        }], 'quantity_produite')
        ->get();

        return view('depots.index', [
            'depots' => $depots,
            'selectedDate' => $date,
        ])->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $viewData['title'] = 'Ajouter un mouvement';

        $produits = Produit::all();

        return view('depots.create', compact('produits'))->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données de dépôt
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'nullable|numeric|min:0',
        ], [
            'quantities.required' => 'Vous devez fournir les quantités reçues.',
            'quantities.*.numeric' => 'Chaque quantité doit être un nombre.',
            'quantities.*.min' => 'Chaque quantité doit être positive.',
        ]);

        $userId = Auth::id();  // ID du chef de dépôt connecté

        foreach ($request->input('quantities') as $produitId => $quantityProduite) {
            if ($quantityProduite !== null) {
                Depot::create([
                    'produit_id' => $produitId,
                    'user_id' => $userId,
                    'quantity_produite' => $quantityProduite,
                ]);
            }
        }

        return redirect()->route('depots.index')->with('success', 'Les quantités reçues ont été enregistrées avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Depot $depot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Depot $depot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Depot $depot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Depot $depot)
    {
        //
    }
}
