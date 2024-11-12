<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Partenaire;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SyntheseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData['title'] = 'Tableau de réconscialiation';

        // Récupération de la plage de dates
        $date_debut = $request->input('date_debut', Carbon::now()->startOfMonth()->toDateString());
        $date_fin = $request->input('date_fin', Carbon::now()->endOfMonth()->toDateString());

        // Charger les catégories avec leurs produits et calculs
        $categories = Category::with(['produits' => function ($query) use ($date_debut, $date_fin) {
            $query->withSum(['commandes as total_kg_demanded' => function ($q) use ($date_debut, $date_fin) {
                        $q->whereBetween('created_at', [$date_debut, $date_fin]);
                    }], 'nbre_kg')
                ->withSum(['commandes as total_produits_demanded' => function ($q) use ($date_debut, $date_fin) {
                        $q->whereBetween('created_at', [$date_debut, $date_fin]);
                    }], 'nbre_produit')
                ->withSum(['productions as total_quantity_produite' => function ($q) use ($date_debut, $date_fin) {
                        $q->whereBetween('created_at', [$date_debut, $date_fin]);
                    }], 'quantity')
                ->withSum(['depots as total_quantity_depot' => function ($q) use ($date_debut, $date_fin) {
                        $q->whereBetween('created_at', [$date_debut, $date_fin]);
                    }], 'quantity_produite')
                ->with(['distributionSites' => function ($q) use ($date_debut, $date_fin) {
                        $q->whereBetween('created_at', [$date_debut, $date_fin]);
                    },
                    'distributionPartenaires' => function ($q) use ($date_debut, $date_fin) {
                        $q->whereBetween('created_at', [$date_debut, $date_fin]);
                    }]);
        }])->get();

        // Initialisation des totaux globaux
        $total_kg_demanded = 0;
        $total_produits_demanded = 0;
        $total_kg_reel = 0;
        $total_perte_production_valorisee = 0;

        // Accumuler les totaux par catégorie
        $category_kg_reels = [];

        foreach ($categories as $category) {
            $category_kg_reel = 0;
            foreach ($category->produits as $produit) {
                $produit->perte_production = $produit->total_quantity_produite - $produit->total_quantity_depot;
                $produit->distribution = $produit->distributionSites->sum('quantity') + $produit->distributionPartenaires->sum('quantity');
                $produit->perte_depot = $produit->total_quantity_depot - $produit->distribution;
                $produit->bon_produit = $produit->distribution;
                $produit->kg_reel = $produit->qte_par_kg > 0 ? $produit->bon_produit / $produit->qte_par_kg : 0;
                $produit->perte_production_valorisee = $produit->perte_production * $produit->prix;

                // Accumuler les totaux
                $total_kg_demanded += $produit->total_kg_demanded;
                $total_produits_demanded += $produit->total_produits_demanded;
                $total_kg_reel += $produit->kg_reel;
                $total_perte_production_valorisee += $produit->perte_production_valorisee;
                $category_kg_reel += $produit->kg_reel;
            }
            $category_kg_reels[$category->name] = $category_kg_reel;
        }

        // Calculs finaux pour la ligne des totaux
        $total_kg_par_sac = $total_kg_demanded / 25;
        $total_prix = $total_kg_reel / 25;

        // Charger tous les sites pour les colonnes dynamiques
        $sites = Site::all();

        // Charger tous les partenaires pour les colonnes dynamiques
        $partenaires = Partenaire::all();

        // Passer les données à la vue, incluant les dates
        return view('rapports.fiche_tableau_synthese',
        compact('sites', 'partenaires', 'categories', 'date_debut', 'date_fin', 'total_kg_demanded', 'total_produits_demanded', 'total_kg_reel',
        'total_kg_par_sac', 'total_prix', 'total_perte_production_valorisee', 'category_kg_reels'))
            ->with('viewData', $viewData);
    }
}
