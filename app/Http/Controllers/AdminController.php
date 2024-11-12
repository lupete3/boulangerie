<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Partner;
use App\Models\ProductionConfiguration;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Gérer les produits
    public function indexProducts()
    {
        $viewData['title'] = 'Liste des produits';
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'))->with('viewData', $viewData);
    }

    public function createProduct()
    {
        $viewData['title'] = 'Ajouter produit';
        $categories = Category::all();
        return view('admin.products.create', compact('categories'))->with('viewData', $viewData);
    }

    public function storeProduct(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity_per_kg' => 'required'
        ]);

        Product::create($request->all());
        return redirect()->route('admin.products')->with('success', 'Action réussie.');

    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'quantity_per_kg' => 'required'
        ]);

        $product->update($request->all());
        return redirect()->route('admin.products.index')->with('success', 'Action réussie.');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Action réussie.');
    }

    // Gérer les guichetiers et partenaires
    public function indexPartners()
    {
        $viewData['title'] = 'Liste des partenaires';
        $partners = Partner::all();
        return view('admin.partners.index', compact('partners'))->with('viewData', $viewData);
    }

    public function createPartner()
    {
        $viewData['title'] = 'Ajouter un partenaire';
        return view('admin.partners.create')->with('viewData', $viewData);
    }

    public function storePartner(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'contact' => 'required'
        ]);

        Partner::create($request->all());
        return redirect()->route('admin.partners')->with('success', 'Action réussie.');
    }

    // Configurer la production des produits
    public function configureProduction()
    {
        $viewData['title'] = 'Configuration la peoduction';
        $products = Product::all();
        return view('admin.production.configure', compact('products'))->with('viewData', $viewData);
    }

    public function storeProductionConfiguration(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'kg_per_sac' => 'required',
            'products_per_sac' => 'required'
        ]);

        ProductionConfiguration::create($request->all());
        return redirect()->route('admin.configure_production')->with('success', 'Action réussie.');
    }
}
