<?php

namespace App\Http\Controllers;

use App\Models\Boisson;
use App\Models\Client;
use App\Models\CommandeClient;
use App\Models\Nourriture;
use App\Models\PaiementClient;
use App\Models\Site;
use App\Models\StockBoulangerie;
use App\Models\StockPf;
use App\Models\Table;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Site $site)
    {
        $viewData = [];

        $viewData['title'] = 'Historique des ventes du point de vente '.$site->nom;

        $viewData['commandes'] = CommandeClient::where('site_id', $site->id)->orderBy('id', 'DESC')->with('ventes','site')->get();

        return view('ventes.index', compact('site'))->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Site $site)
    {
        $viewData = [];

        $viewData['title'] = 'Ajouter une vente';

        $viewData['clients'] = Client::all();

        $viewData['produits'] = StockBoulangerie::with('stockProduitFinis')->where('site_id', $site->id)->get();

        return view('ventes.create', compact('site'))->with('viewData', $viewData);
    }

    
    public function addToCart(Request $request)
    {
        // Récupérer l'ID du produit depuis la requête
        $request->validate([
            'produit_id' => 'required|exists:stock_boulangeries,id',
            'quantite' => 'required|integer|min:1'
        ]);

        $productId = $request->produit_id;

        // Rechercher le produit correspondant dans la base de données

        $product = StockBoulangerie::where('id', $productId)->where('site_id',$request->site_id)->with('stockProduitFinis')->first();

        // Récupérer le panier de la session ou créer un nouveau panier
        $cart = session()->get('cart', []);

        if ($product->solde < $request->quantite ) {

            return redirect()->back()->with('error','Cette quantité est supérieur au solde actuel');
        }

        // Vérifier si le produit est déjà dans le panier
        if (isset($cart[$product->id])) {
            // Augmenter la quantité si le produit est déjà dans le panier
            $cart[$product->id]['quantity'] += $request->quantite;

        } else {
            // Ajouter le produit au panier
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->stockProduitFinis->designation,
                'quantity' => $request->quantite,
                'price' => $product->stockProduitFinis->prix,
            ];
        }

        // Mettre à jour le panier dans la session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produit ajouté au panier avec succès.');

    }

    /**
     * Retirer un article dans le panier
     * public function removeFromCart(Request $request)
    **/
    public function removeFromCart(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'article_id' => 'required|exists:stock_boulangeries,stock_pf_id',
        ]);

        // Récupérer le produit à partir de la base de données
        $productId = $request->article_id;

        // Récupérer le panier de la session
        $cart = Session::get('cart', []);

        // Vérifier si le produit est présent dans le panier
        if (isset($cart[$productId])) {
            // Supprimer le produit du panier
            unset($cart[$productId]);

            // Mettre à jour le panier dans la session
            Session::put('cart', $cart);

            return redirect()->back()->with('success', 'Le produit a été supprimé du panier avec succès.');
        }

        return redirect()->back()->with('error', 'Le produit n\'est pas dans le panier.');
    }

    /**
     * Vider le pqnier
     */
    public function clearCart()
    {
        // Supprimer le panier de la session
        Session::forget('cart');

        return redirect()->route('ventes.create')->with('success', 'Le panier a été vidé avec succès.');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'montant' => 'required|numeric',
            'client_id' => 'required',
            'site_id' => 'required',

        ],[

            'montant.required' => 'Compléter le champ montant payé',
            'montant.numeric' => 'Entrer un nombre pour le montant',
            'client_id.required' => 'Choisir un client à facturer',
            'site_id.required' => 'Choisir un point de vente',

        ]);

        $tot = 0;

        foreach(session('cart', []) as $productId => $item)
        {
            $tot = $tot + ($item['price'] * $item['quantity']);
        }

        $commandeClient = CommandeClient::create([
            'montant' => $tot,
            'paye' => $request->montant,
            'reste' => $tot - $request->montant,
            'client_id' => $request->client_id,
            'observation' => $request->observation,
            'site_id' => $request->site_id,
        ]);

        PaiementClient::create([
            'montant' => $request->montant,
            'reste' => $tot - $request->montant,
            'commande_client_id' => $commandeClient->id,
            'client_id' => $commandeClient->client_id,
            'site_id' => $request->site_id
        ]);

        foreach(session('cart', []) as $productId => $item)
        {

            $produit = StockBoulangerie::where('id',$productId)->where('site_id', $request->site_id)->with('stockProduitFinis')->first();

            Vente::create([
                'designation' => $item['name'],
                'quantite' => $item['quantity'],
                'prix' => $item['price'],
                'reste' => $produit->solde - $item['quantity'],
                'stock_pf_id' => $productId,
                'commande_client_id' => $commandeClient->id, 
            ]);

            $produit->update([
                'solde' => $produit->solde - $item['quantity']
            ]);
        }

        Session::forget('cart');

        return redirect()->back()->with('success','Vente effectuée avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(Vente $vente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vente $vente)
    {
        $viewData = [];

        $viewData['title'] = 'Mise à jour de la vente';

        $viewData['produits'] = StockBoulangerie::with('stockProduitFinis')->where('site_id',$vente->commandeClient->site_id)->get();

        return view('ventes.update', compact('vente'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vente $vente)
    {
        //Mise a jour des donnes dans la base de donnees
        $request->validate([

            'quantite' => 'required|numeric',
            'produit_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'quantite.numeric' => 'Entrer un nombre ',
            'produit_id.required' => 'Choisir un produit',

        ]);

        $produit = StockBoulangerie::where('stock_pf_id',$vente->stock_pf_id)->whith('site_id', $vente->commandeClient->site_id)->with('stockProduitFinis')->first();

        $produit->update([
            'solde' => $produit->solde + $vente->quantite
        ]);

        $newProduit = StockBoulangerie::where('stock_pf_id',$vente->stock_pf_id)->whith('site_id', $vente->commandeClient->site_id)->with('stockProduitFinis')->first();

        $newProduit->update([
            'solde' => $newProduit->solde - $request->quantite
        ]);

        $vente->update([
            'designation' => $newProduit->stockProduitFinis->designation,
            'quantite' => $request->quantite,
            'prix' => $newProduit->stockProduitFinis->prix,
            'reste' => $newProduit->solde,
            'stock_pf_id' => $request->produit_id,
            'observation' => $request->observation
        ]);

        return redirect()->back()->with('success', 'Mise à jour effectuée avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vente $vente)
    {
        //Suppression de la vente
        $produit = StockBoulangerie::where('stock_pf_id',$vente->stock_pf_id)->whith('site_id', $vente->commandeClient->site_id)->with('stockProduitFinis')->first();
        
        $produit->update([
            'solde' => $produit->solde + $vente->quantite
        ]);

        $vente->delete();

        return redirect()->back()->with('success', 'Suppression effectuée avec succès !');
    }
}
