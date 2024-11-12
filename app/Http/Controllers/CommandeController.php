<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Récupérer la date demandée, ou la date du jour par défaut
        $date = $request->input('date', now()->toDateString());

        $viewData['title'] = 'Liste des commandes du '.$date;

        $commandes = Commande::select('produit_id', DB::raw('SUM(nbre_kg) as total_kg'))
            ->whereDate('created_at', $date)
            ->groupBy('produit_id')
            ->with('produit') // Charger les informations du produit pour chaque commande
            ->get();

        return view('commandes.index', [
            'commandes' => $commandes,
            'selectedDate' => $date,
        ])->with('viewData', $viewData);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $viewData['title'] = 'Ajouter commande';

        $viewData['produits'] = Produit::all();

        return view('commandes.create')->with('viewData', $viewData);
    }

    public function preview(Request $request)
    {
        $viewData['title'] = 'Prévisualisation';

        // Valider les données envoyées dans le tableau 'produits'
        $request->validate([
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.nbre_kg' => 'required|numeric|min:0',
        ], [
            'produits.*.produit_id.required' => 'Le produit est obligatoire.',
            'produits.*.produit_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'produits.*.nbre_kg.required' => 'Le nombre de kg est obligatoire.',
            'produits.*.nbre_kg.numeric' => 'Le nombre de kg doit être un nombre.',
            'produits.*.nbre_kg.min' => 'Le nombre de kg doit être positif.',
        ]);

        // Filtrer les produits avec une quantité de kg supérieure à zéro
        $produitsCommandes = collect($request->produits)->filter(function ($produit) {
            return $produit['nbre_kg'] > 0;
        });

        // Récupérer les informations des produits commandés depuis la base de données
        $produits = Produit::whereIn('id', $produitsCommandes->pluck('produit_id'))->get();

        return view('commandes.preview', [
            'produitsCommandes' => $produitsCommandes,
            'produits' => $produits,
        ])->with('viewData', $viewData);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Valider les données envoyées dans le tableau 'produits'
        $request->validate([
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.nbre_kg' => 'required|numeric|min:0',
        ], [
            'produits.*.produit_id.required' => 'Le produit est obligatoire.',
            'produits.*.produit_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'produits.*.nbre_kg.required' => 'Le nombre de kg est obligatoire.',
            'produits.*.nbre_kg.numeric' => 'Le nombre de kg doit être un nombre.',
            'produits.*.nbre_kg.min' => 'Le nombre de kg doit être positif.',
        ]);

        // Parcourir les produits et créer une commande pour chaque produit avec un nombre de kg supérieur à zéro
        foreach ($request->produits as $produitData) {
            if ($produitData['nbre_kg'] > 0) {
                Commande::create([
                    'produit_id' => $produitData['produit_id'],
                    'user_id' => Auth::id(),
                    'nbre_kg' => $produitData['nbre_kg'],
                    'nbre_produit' => $produitData['qte_par_kg'] * $produitData['nbre_kg'], // Assurez-vous de calculer cette valeur selon vos besoins
                    'status' => 0, // Statut initial de la commande (par défaut : 0)
                ]);
            }
        }

        // Redirection avec un message de succès
        return redirect()->route('commandes.index')->with('success', 'Commandes enregistrées avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commande $commande)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commande $commande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commande $commande)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commande $commande)
    {
        //
    }
}
