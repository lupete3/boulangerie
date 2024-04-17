<?php

namespace App\Http\Controllers;

use App\Models\StockBoulangerie;
use Illuminate\Http\Request;

class StockBoulangerieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Liste des produits

        $viewData = [];

        $viewData['title'] = 'Liste des produits ';

        $viewData['produits'] = StockBoulangerie::with('stockProduitFinis')->get();

        return view('stock_boulangerie.index')->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StockBoulangerie $stockBoulangerie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockBoulangerie $stockBoulangerie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockBoulangerie $stockBoulangerie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockBoulangerie $stockBoulangerie)
    {
        //
    }
}
