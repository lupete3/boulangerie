<?php

namespace App\Http\Controllers;

use App\Models\Composition;
use App\Models\Production;
use App\Models\StockPf;
use App\Models\StockUsine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Show all productions

        $viewData = [];

        $viewData['title'] = 'Liste des productions ';

        $viewData['productions'] = Production::orderBy('id', 'DESC')->with('produitFinis')->get();

        return view('productions.index')->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Afficher le formulaire d'ajout achat matiere premiere

        $viewData = [];

        $viewData['title'] = 'Ajouter une production';

        $viewData['produitsFinis'] = StockPf::orderBy('designation', 'ASC')->get();

        $viewData['matieresPremieres'] = StockUsine::with('stockMaison')->get();

        return view('productions.create')->with('viewData', $viewData);
    }
    

    public function addToCart(Request $request)
    {
        // Récupérer l'ID du produit depuis la requête
        $request->validate([
            'article_id' => 'required|exists:stock_usines,id',
            'quantite' => 'required|integer|min:1'
        ]);

        $productId = $request->article_id;

        // Rechercher le produit correspondant dans la base de données
        $products = StockUsine::where('id', $productId)->with('stockMaison')->get();

        foreach ($products as $product) {
            $id = $product->id;
            $prix = $product->stockMaison->prix;
            $solde = $product->solde;
        }

        // Récupérer le panier de la session ou créer un nouveau panier
        $cart = session()->get('cart', []);

        if ($solde < $request->quantite ) {

            return redirect()->route('production.create')->with('error','Cette quantité est supérieur au solde actuel');
        }

        // Vérifier si le produit est déjà dans le panier
        if (isset($cart[$id])) {
            // Augmenter la quantité si le produit est déjà dans le panier
            $cart[$id]['quantity'] += $request->quantite;

        } else {
            // Ajouter le produit au panier
            $cart[$id] = [
                'id' => $id,
                'name' => $product->stockMaison->designation,
                'quantity' => $request->quantite,
                'price' => $prix,
            ];
        }

        // Mettre à jour le panier dans la session
        session()->put('cart', $cart);

        return redirect()->route('production.create')->with('success', 'Matière première ajoutée à la composition avec succès.');

    }

    /**
     * Retirer un article dans le panier
     * public function removeFromCart(Request $request)
    **/
    public function removeFromCart(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'article_id' => 'required|exists:stock_maisons,id',
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

            return redirect()->back()->with('success', 'La matière première a été supprimée de la composition avec succès.');
        }

        return redirect()->back()->with('error', 'La matière première n\'est pas dans la composition.');
    }

    /**
     * Vider le pqnier
     */
    public function clearCart()
    {
        // Supprimer le panier de la session
        Session::forget('cart');

        return redirect()->route('production.create')->with('success', 'La composition a été vidée avec succès.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
        $request->validate([

            'produit_finis_id' => 'required',
            'quantite' => 'required|numeric',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'produit_finis_id.required' => 'Choisir un produit finis ',

        ]);

        //Mise a jour de la quantite produit finis
        $produitFinis = StockPf::find($request->produit_finis_id);

        $solde = $produitFinis->solde;

        $produitFinis->solde = $solde + $request->quantite;

        $produitFinis->save();

        //Recuperation du panier
        $cart = Session::get('cart', []);

        // Enregistrer les désignations dans la table Production
        $designations = implode(', ', array_column($cart, 'name'));

        Production::create([
            'designation' => $designations,
            'quantite' => $request->quantite,
            'stock_pf_id' => $request->produit_finis_id,
        ]);


        $productionId = Production::latest()->value('id');

        $newProductionId = ($productionId == null) ? 1 : $productionId ;

        // Enregistrer les articles du panier dans la table Composition
        foreach ($cart as $composition) {
            //Mise a jour de la quantite matiere premiere
            $stockUsine = StockUsine::where('id_stock_maisons', $composition['id'])->get();

            foreach ($stockUsine as $stockUsin) {
                $idStockUsine = $stockUsin->id;
            }

            $matierePremiere = StockUsine::find($idStockUsine);

            $solde = $matierePremiere->solde;

            $matierePremiere->solde = $solde - $composition['quantity'];

            $matierePremiere->save();

            Composition::create([
                'stock_usine_id' => $composition['id'],
                'designation' => $composition['name'], 
                'quantite' => $composition['quantity'], 
                'prix' => $composition['price'], 
                'production_id' => $newProductionId

            ]);
        }

        // Supprimer le panier de la session
        Session::forget('cart');

        return redirect()->route('production.index')->with('success', 'La production a été crée avec succès.');
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

        $viewData = [];

        $viewData['title'] = 'Mise à jour de la production';

        $viewData['produitsFinis'] = StockPf::orderBy('designation', 'ASC')->get();

        $viewData['matieresPremieres'] = StockUsine::with('stockMaison')->get();

        return view('productions.update', compact('production'))->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Production $production)
    {

        $request->validate([

            'quantite' => 'required|numeric',
            'produit_finis_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'produit_finis_id.required' => 'Choisir un produit finis ',

        ]);

        $designations = Production::find($production)->pluck('designation')->toArray();

        $designationList = implode(', ',$designations);

        //Mise a jour de la quantite produit finis
        $produitFinis = StockPf::find($production->stock_pf_id);

        $solde = $produitFinis->solde;

        $produitFinis->solde = $solde - $production->quantite;

        $produitFinis->save();

        //Mise a jour de la quantite produit finis
        $produitFinis = StockPf::find($request->produit_finis_id);

        $solde = $produitFinis->solde;

        $produitFinis->solde = $solde + $production->quantite;

        $produitFinis->save();

        //Sauvegarder les donnees liees à l'achat matieres premiere
        $production->update([

            'designation' => $designationList,
            'quantite' => $request->quantite,
            'stock_pf_id' => $request->produit_finis_id,

        ]);

        return redirect()->route('production.index')->with('success','Mise à jour effectuée avec succès !');

    }

    /**
     * Update the specified resource in storage.
     */
    public function addToCartEdit(Request $request, Production $production)
    {

        $request->validate([

            'quantite' => 'required|numeric',
            'article_id' => 'required',

        ],[

            'quantite.required' => 'Compléter le champ quantité',
            'quantite.numeric' => 'La quantité doit être un nombre ',
            'article_id.required' => 'Choisir une matière première ',

        ]);

        $productionId = $production->id;

        $productionString = Production::find($production)->pluck('designation')->toArray();

        $designationList = implode(', ',$productionString);

        $articleId = $request->article_id;

        // Rechercher le produit correspondant dans la base de données
        $products = StockUsine::where('id', $articleId)->with('stockMaison')->get();

        foreach ($products as $product) {
            $id = $product->id;
            $prix = $product->stockMaison->prix;
            $designation = $product->stockMaison->designation;
            $solde = $product->solde;
        }

        $composition = Composition::where('stock_usine_id', $articleId)->where('production_id', $productionId)->first();

        if($composition){

            $composition->quantite =+ $composition->quantite + $request->quantite;
            $composition->save();

            $matierePremiere = StockUsine::find($id);

            $solde = $matierePremiere->solde;

            $matierePremiere->solde = $solde - $request->quantite;

            $matierePremiere->save();

        }else{
            $matierePremiere = StockUsine::find($id);

            $solde = $matierePremiere->solde;

            $matierePremiere->solde = $solde - $request->quantite;

            $matierePremiere->save();

            Composition::create([
                'stock_usine_id' => $id,
                'designation' => $designation, 
                'quantite' => $request->quantite, 
                'prix' => $prix, 
                'production_id' => $productionId

            ]);

            $production->designation = $designationList;

            $production->save();
        }

        return redirect()->back()->with('success','Mise à jour effectuée avec succès !');

    }
    /**
     * Update the specified resource in storage.
     */
    public function removeFromCartEdit(Request $request, Composition $composition)
    {

        $productionId = $composition->production_id;

        $productionString = Production::find($productionId)->pluck('designation')->toArray();
        $production = Production::find($productionId);

        // Rechercher le produit correspondant dans la base de données
        $products = StockUsine::where('id', $composition->stock_usine_id)->with('stockMaison')->first();

        $products->solde = $products->solde + $composition->quantite;

        $products->save();

        $composition->delete();  
        
        $designationList = implode(', ',$productionString);

        $production->designation = $designationList;

        $production->save();

        return redirect()->back()->with('success','Mise à jour effectuée avec succès !');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Production $production)
    {
        //
    }
}
