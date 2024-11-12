<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $viewData['title'] = 'Liste des catégories produits';

        $viewData['categories'] = Category::all();

        return view('categories.index')->with('viewData', $viewData);
    }

    public function create()
    {
        $viewData['title'] = 'Ajouter catégorie';

        return view('categories.create')->with('viewData', $viewData);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,except,id',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(Category $category)
    {
        $viewData['title'] = 'Modifier la catégorie';

        return view('categories.update', compact('category'))->with('viewData', $viewData);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}
