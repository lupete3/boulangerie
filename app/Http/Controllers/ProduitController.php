<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        $viewData['title'] = 'Liste des produits';

        $viewData['produits'] = Produit::with('category')->get();

        return view('produits.index')->with('viewData', $viewData);
    }

    public function create()
    {
        $viewData['title'] = 'Ajouter produit';

        $viewData['categories'] = Category::all();

        return view('produits.create')->with('viewData', $viewData);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'kg_par_sac' => 'required|numeric|min:0',
            'qte_par_sac' => 'required|numeric|min:0',
        ], [
            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'nom.required' => 'Le nom du produit est obligatoire.',
            'nom.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'nom.max' => 'Le nom du produit ne doit pas dépasser 255 caractères.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix doit être positif.',
            'kg_par_sac.required' => 'Le poids par sac est obligatoire.',
            'kg_par_sac.numeric' => 'Le poids par sac doit être un nombre.',
            'kg_par_sac.min' => 'Le poids par sac doit être positif.',
            'qte_par_sac.required' => 'La quantité produit par sac est obligatoire.',
            'qte_par_sac.numeric' => 'La quantité par sac doit être un nombre.',
            'qte_par_sac.min' => 'La quantité par sac doit être positive.',
        ]);

        Produit::create([
            'category_id' => $request->category_id,
            'nom' => $request->nom,
            'prix' => $request->prix,
            'kg_par_sac' => $request->kg_par_sac,
            'qte_par_sac' => $request->qte_par_sac,
            'qte_par_kg' => $request->qte_par_sac / $request->kg_par_sac,
            'solde' => 0,
        ]);

        return redirect()->route('produits.index')->with('success', 'Produits créée avec succès.');
    }

    public function edit(Produit $produit)
    {
        $viewData['title'] = 'Modifier le produit';

        $viewData['categories'] = Category::all();

        return view('produits.update', compact('produit'))->with('viewData', $viewData);
    }

    public function update(Request $request, Produit $produit)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'kg_par_sac' => 'required|numeric|min:0',
            'qte_par_sac' => 'required|numeric|min:0',
        ], [
            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'nom.required' => 'Le nom du produit est obligatoire.',
            'nom.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'nom.max' => 'Le nom du produit ne doit pas dépasser 255 caractères.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix doit être positif.',
            'kg_par_sac.required' => 'Le poids par sac est obligatoire.',
            'kg_par_sac.numeric' => 'Le poids par sac doit être un nombre.',
            'kg_par_sac.min' => 'Le poids par sac doit être positif.',
            'qte_par_sac.required' => 'La quantité produit par sac est obligatoire.',
            'qte_par_sac.numeric' => 'La quantité par sac doit être un nombre.',
            'qte_par_sac.min' => 'La quantité par sac doit être positive.',
        ]);

        // Récupérer le produit à mettre à jour
        $produit = Produit::findOrFail($produit->id);

        // Mettre à jour les champs du produit
        $produit->update([
            'category_id' => $request->category_id,
            'nom' => $request->nom,
            'prix' => $request->prix,
            'kg_par_sac' => $request->kg_par_sac,
            'qte_par_sac' => $request->qte_par_sac,
            'qte_par_kg' => $request->qte_par_sac / $request->kg_par_sac,
        ]);

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès.');
    }


    public function destroy(Produit $produit)
    {
        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }
}
