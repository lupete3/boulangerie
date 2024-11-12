<?php

namespace App\Http\Controllers;

use App\Models\DistributionPartenaire;
use App\Models\DistributionSite;
use App\Models\Produit;
use App\Models\Site;
use App\Models\Partenaire;
use App\Models\SiteDistribution;
use App\Models\PartenaireDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionController extends Controller
{
    /**
     * Affiche le formulaire de distribution des produits.
     */
    public function create()
    {
        $viewData['title'] = 'Effectuer une distribution';

        $produits = Produit::all();
        $sites = Site::all();
        $partenaires = Partenaire::all();

        return view('distributions.create', compact('produits', 'sites', 'partenaires'))->with('viewData', $viewData);
    }

    /**
     * Stocke les informations de distribution pour chaque produit vers les sites et les partenaires.
     */
    public function store(Request $request)
    {
        $request->validate([
            'produits' => 'required|array',
        ], [
            'produits.required' => 'Veuillez sélectionner au moins un produit pour la distribution.',
        ]);

        // Parcours des produits pour chaque distribution
        foreach ($request->produits as $produitId => $distributionData) {
            
            // Distribution vers les Sites
            if (!empty($distributionData['site_ids']) && !empty($distributionData['site_quantities'])) {
                foreach ($distributionData['site_ids'] as $index => $siteId) {
                    $quantity = $distributionData['site_quantities'][$index];
                    
                    if ($quantity > 0) {  // Enregistre seulement si la quantité est positive
                        DistributionSite::create([
                            'produit_id' => $produitId,
                            'site_id' => $siteId,
                            'quantity' => $quantity,
                            'user_id' => Auth::user()->id
                        ]);
                    }
                }
            }

            // Distribution vers les Partenaires
            if (!empty($distributionData['partenaire_ids']) && !empty($distributionData['partenaire_quantities'])) {
                foreach ($distributionData['partenaire_ids'] as $index => $partenaireId) {
                    $quantity = $distributionData['partenaire_quantities'][$index];
                    
                    if ($quantity > 0) {  // Enregistre seulement si la quantité est positive
                        DistributionPartenaire::create([
                            'produit_id' => $produitId,
                            'partenaire_id' => $partenaireId,
                            'quantity' => $quantity,
                            'user_id' => Auth::user()->id
                        ]);
                    }
                }
            }
        }

        return redirect()->route('distributions.index')->with('success', 'Distribution des produits enregistrée avec succès.');
    }

    /**
     * Affiche un tableau de toutes les distributions enregistrées.
     */
    public function index(Request $request)
    {
        $viewData['title'] = 'Liste des distributions';

        // Option de filtrage par date, avec la date du jour par défaut
        $date = $request->input('date', now()->toDateString());

        $distributionsSites = DistributionSite::whereDate('created_at', $date)
            ->with(['produit', 'site'])
            ->get()
            ->groupBy('produit_id');

        $distributionsPartenaires = DistributionPartenaire::whereDate('created_at', $date)
            ->with(['produit', 'partenaire'])
            ->get()
            ->groupBy('produit_id');

        $produits = Produit::all();

        return view('distributions.index', compact('distributionsSites', 'distributionsPartenaires', 'produits', 'date'))
        ->with('viewData', $viewData);
    }
}

