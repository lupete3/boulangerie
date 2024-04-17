<?php

namespace App\Http\Controllers;

use App\Models\StockUsine;
use Illuminate\Http\Request;

class StockUsineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Liste des matieres premiers

        $viewData = [];

        $viewData['title'] = 'Liste des matières premières ';

        $viewData['matiresPremieres'] = StockUsine::with('stockMaison')->get();

        return view('stock_usine.index')->with('viewData', $viewData);
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
    public function show(StockUsine $stockUsine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockUsine $stockUsine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockUsine $stockUsine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockUsine $stockUsine)
    {
        //
    }
}
