<?php

namespace App\Http\Controllers;

use App\Models\Argent;
use App\Models\Category;
use App\Models\Commande;
use App\Models\DepenseGuichet;
use App\Models\DistributionPartenaire;
use App\Models\DistributionSite;
use App\Models\OperationGuichet;
use App\Models\Partenaire;
use App\Models\Produit;
use App\Models\Site;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RapportsController extends Controller
{
    //Ventes Admin
    public function venteAdmin(Request $request)
    {
        $viewData['title'] = 'Historique de ventes';

        $date = $request->input('date', date('Y-m-d'));
        $userId = Auth::user()->role == 'guichetier' ? Auth::user()->id : $request->input('user_id');
        $siteId = $request->input('site_id');
        $shift = $request->input('shift');

        // Requête pour les opérations de guichet
        $operationsQuery = OperationGuichet::whereDate('created_at', $date)
            ->with(['produit', 'user', 'site']);

        if ($userId) {
            $operationsQuery->where('user_id', $userId);
        }
        if ($siteId) {
            $operationsQuery->where('site_id', $siteId);
        }
        if ($shift) {
            $operationsQuery->where('shift', $shift);
        }

        $operations = $operationsQuery->get();

        // Requête pour les enregistrements d'argent
        $argentsQuery = Argent::whereDate('created_at', $date)
            ->with(['user', 'site']);

        if ($userId) {
            $argentsQuery->where('user_id', $userId);
        }
        if ($siteId) {
            $argentsQuery->where('site_id', $siteId);
        }
        if ($shift) {
            $argentsQuery->where('shift', $shift);
        }

        $argents = $argentsQuery->get();

        // Requête pour les dépenses
        $depensesQuery = DepenseGuichet::whereDate('created_at', $date)
            ->with(['user', 'site']);

        if ($userId) {
            $depensesQuery->where('user_id', $userId);
        }
        if ($siteId) {
            $depensesQuery->where('site_id', $siteId);
        }
        if ($shift) {
            $depensesQuery->where('shift', $shift);
        }

        $depenses = $depensesQuery->get();

        $users = User::all();

        $sites = Site::all();

        return view('rapports.fiche_ventes_admin', compact('operations', 'argents', 'depenses', 'users', 'sites', 'date', 'userId', 'siteId', 'shift'))
            ->with('viewData', $viewData);

    }

    //Reconciliation Admin
    public function renconciliationAdmin(Request $request)
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
        return view('rapports.fiche_reconciliation',
        compact('sites', 'partenaires', 'categories', 'date_debut', 'date_fin', 'total_kg_demanded', 'total_produits_demanded', 'total_kg_reel',
        'total_kg_par_sac', 'total_prix', 'total_perte_production_valorisee', 'category_kg_reels'))
            ->with('viewData', $viewData);
    }

    //Produits
    public function produits()
    {
        $viewData['title'] = 'Liste des produits';

        $viewData['categories'] = Category::with('produits')->get();

        return view('rapports.fiche_produits')->with('viewData', $viewData);
    }

    //Commande production
    public function commande(Request $request)
    {

        // Récupérer la date demandée, ou la date du jour par défaut
        $date = $request->input('date', now()->toDateString());

        $viewData['title'] = 'Liste des commandes du '.$date;

        $commandes = Commande::select('produit_id', DB::raw('SUM(nbre_kg) as total_kg'))
            ->whereDate('created_at', $date)
            ->groupBy('produit_id')
            ->with('produit') // Charger les informations du produit pour chaque commande
            ->get();

        return view('rapports.fiche_commandes', [
            'commandes' => $commandes,
            'selectedDate' => $date,
        ])->with('viewData', $viewData);
    }

    //Production
    public function production(Request $request)
    {
        $viewData = [];

        $viewData['title'] = 'Fiche des productions ';

        // Par défaut, la date est celle du jour, sinon on prend la date passée dans la requête
        $date = $request->input('date', Carbon::now()->toDateString());

        // Récupérer toutes les productions de la date sélectionnée, groupées par produit
        $productions = Produit::with(['productions' => function($query) use ($date) {
            $query->whereDate('created_at', $date);
        }])->withSum(['productions as quantity_produced' => function ($query) use ($date) {
            $query->whereDate('created_at', $date);
        }], 'quantity')
        ->withSum(['commandes as quantity_demande' => function ($query) use ($date) {
            $query->whereDate('created_at', $date);
        }], 'nbre_produit')
        ->withSum(['commandes as quantity_kg_demande' => function ($query) use ($date) {
            $query->whereDate('created_at', $date);
        }], 'nbre_kg')
        ->get();

        return view('rapports.fiche_productions', [
            'productions' => $productions,
            'selectedDate' => $date,
        ])->with('viewData', $viewData);
    }

    //Entrée dépôt
    public function depot(Request $request)
    {
        $viewData['title'] = 'Fiche Entrée Dépôt';

        $date = $request->input('date', Carbon::now()->toDateString());

        $viewData['categories'] = Category::with('produits')->get();

        // Récupérer tous les dépôts pour la date donnée, groupés par produit
        $categories = Category::with(['produits' => function ($query) use ($date) {
            $query->withSum(['depots as quantity_produite' => function ($q) use ($date) {
                $q->whereDate('created_at', $date);
            }], 'quantity_produite');
                
        }])->get();            

        return view('rapports.fiche_depot', [
            'categories' => $categories,
            'selectedDate' => $date,
        ])->with('viewData', $viewData);
    }

    //Dustribution
    public function distribution(Request $request)
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

        return view('rapports.fiche_distribution', compact('distributionsSites', 'distributionsPartenaires', 'produits', 'date'))
        ->with('viewData', $viewData);
    }
}
