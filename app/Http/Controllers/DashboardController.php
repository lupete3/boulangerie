<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Site;
use App\Models\User;
use App\Models\Vente;
use App\Models\Depense;
use App\Models\StockPf;
use App\Models\Production;
use App\Models\StockUsine;
use App\Models\Fournisseur;
use App\Models\StockMaison;
use Illuminate\Http\Request;
use App\Models\CommandeClient;
use Illuminate\Validation\Rule;
use App\Models\AchatStockMaison;
use App\Models\Category;
use App\Models\OperationGuichet;
use App\Models\StockBoulangerie;
use App\Models\Synthese;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    //Main DashboardController

    public function dashboard(Request $request)
    {
        $viewData['title'] = 'Tableau de réconciliation - Synthèse des ventes';

        // Récupération de la plage de dates
        $date_debut = $request->input('date_debut', Carbon::now()->startOfMonth()->toDateString());
        $date_fin = $request->input('date_fin', Carbon::now()->endOfMonth()->toDateString());

        // Charger les catégories avec leurs produits et les calculs
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
        $total_quantite_depot = 0;
        $total_distribution = 0;
        $total_perte_depot = 0;
        $total_bon_produit = 0;

        foreach ($categories as $category) {
            foreach ($category->produits as $produit) {
                $produit->perte_production = $produit->total_quantity_produite - $produit->total_quantity_depot;
                $produit->distribution = $produit->distributionSites->sum('quantity') + $produit->distributionPartenaires->sum('quantity');
                $produit->perte_depot = $produit->total_quantity_depot - $produit->distribution;
                $produit->bon_produit = $produit->distribution;
                $produit->kg_reel = $produit->qte_par_kg > 0 ? $produit->bon_produit / $produit->qte_par_kg : 0;
                $produit->perte_production_valorisee = $produit->perte_production * $produit->prix;

                // Accumuler les totaux globaux
                $total_kg_demanded += $produit->total_kg_demanded;
                $total_produits_demanded += $produit->total_produits_demanded;
                $total_kg_reel += $produit->kg_reel;
                $total_perte_production_valorisee += $produit->perte_production_valorisee;
                $total_quantite_depot += $produit->total_quantity_depot;
                $total_distribution += $produit->distribution;
                $total_perte_depot += $produit->perte_depot;
                $total_bon_produit += $produit->bon_produit;
            }
        }

        // Calcul des statistiques des opérations de guichet pour le jour
        $date = $request->input('date', date('Y-m-d'));
        $operations = OperationGuichet::whereDate('created_at', $date)->get();
        $montant_physique = $operations->sum('montant_physique');
        $montant_change = $operations->sum('montant_change');
        $montant_manquant = $operations->sum('montant_manquant');
        $montant_excedent = $operations->sum('montant_excedent');
        $total_bon_sac = $total_kg_reel / 25; // Hypothèse de conversion

        switch (Auth::user()->role) {
            case 'chef_distribution':
                return redirect()->route('commandes.index');
                break;

            case 'chef_production':
                return redirect()->route('productions.index');
                break;

            case 'chef_depot':
                return redirect()->route('depots.index');
                break;

            case 'guichetier':
                return redirect()->route('operation_guichets.create');
                break;

            default:
                // Passer les données à la vue
                return view('dashboard', compact(
                    'categories',
                    'total_kg_demanded',
                    'total_produits_demanded',
                    'total_kg_reel',
                    'total_perte_production_valorisee',
                    'total_quantite_depot',
                    'total_distribution',
                    'total_perte_depot',
                    'total_bon_produit',
                    'montant_physique',
                    'montant_change',
                    'montant_manquant',
                    'montant_excedent',
                    'total_bon_sac',
                    'date_debut',
                    'date_fin'
                ))->with('viewData', $viewData);
                break;
        }


    }


    //Gestion des utilisateurs
    public function usersIndex(): View
    {

        $viewData['title'] = 'Liste des utilisateurs';

        $viewData['users'] = User::with('site')->get();

        return view('users.index')->with('viewData',$viewData);
    }

    //Gestion des utilisateurs
    public function usersCreate(): View
    {

        $viewData['title'] = 'Ajouter un utilisateur';

        $viewData['sites'] = Site::all();

        return view('users.create')->with('viewData',$viewData);
    }

    public function usersStore(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required'],
            'password' => ['required'],
        ],[

            'name.required' => 'Complétez le nom',
            'email.required' => 'Complétez email',
            'email.email' => "L'adresse mail n'est pas valide",
            'email.unique' => "Cette adresse mail est déjà utilisée",
            'role.required' => 'Choisir un rôle',
            'password.required' => 'Compléter le champ mot de passe',

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'site_id' => $request->site_id,
            'password' => Hash::make($request->password),

        ]);

        // Vérifier si c'est le premier utilisateur inscrit
        if (User::count() === 1) {
            $user->role = 'admin';
            $user->save();
        }

        return redirect()->route('dashboard.usersIndex')->with('success','Utilisateur ajouté avec succès');
    }

    //Gestion des utilisateurs
    public function usersEdit(User $user): View
    {

        $viewData['title'] = $user->name;

        $viewData['sites'] = Site::all();

        return view('users.edit', compact('user'))->with('viewData',$viewData);
    }

    public function usersUpdate(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'role' => ['required'],
        ],[

            'name.required' => 'Complétez le nom',
            'email.required' => 'Complétez email',
            'email.email' => "L'adresse mail n'est pas valide",
            'email.unique' => "Cette adresse mail est déjà utilisée",
            'role.required' => 'Choisir un rôle',

        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->site_id = $request->site_id;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if($request->password)
        {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('dashboard.usersIndex')->with('success','Mise à jour effectuée avec succès');
    }


    public function usersDelete(User $user)
    {

        $user->delete();

        return redirect()->route('dashboard.usersIndex')->with('success', 'Compte utilisateur supprimé');
    }


    public function stockMpMaison(): View
    {

        $viewData['title'] = 'Liste des matières premières disponibles en stock dépôt';

        $viewData['stockMpMaison'] = StockMaison::orderBy('designation', 'ASC')->get();

        return view('rapports.fiche_stock_mp_maison')->with('viewData',$viewData);
    }


    // Fiche des entrées journalieres
    public function entreeStockMpJour(): View
    {

        $viewData['title'] = 'Liste des achats des matières premières en stock dépôt du '. date('d-m-Y');

        $viewData['entrees'] = AchatStockMaison::whereDate('created_at', Carbon::today())->with('fournisseur','stockMaison')->get();

        return view('rapports.fiche_achats_mp')->with('viewData',$viewData);
    }

    // Fiche des entrées hebdomadaires
    public function entreeStockMpHebdo(): View
    {

        $viewData['title'] = 'Liste des achats matières premières en stock dépôt de la semaine';

        $debutSemaine = Carbon::now()->startOfWeek();
        $finSemaine = Carbon::now()->endOfWeek();

        $viewData['entrees'] = AchatStockMaison::whereBetween('created_at', [$debutSemaine, $finSemaine])->with('fournisseur','stockMaison')->get();

        return view('rapports.fiche_achats_mp')->with('viewData',$viewData);
    }

    // Fiche des entrées hebdomadaires
    public function entreeStockMpAnnuel(): View
    {

        $viewData['title'] = 'Liste des achats matières premières en stock dépôt de l\'année';

        $debutAnnee = Carbon::now()->startOfYear();
        $finAnnee = Carbon::now()->endOfYear();

        $viewData['entrees'] = AchatStockMaison::whereBetween('created_at', [$debutAnnee, $finAnnee])->with('fournisseur','stockMaison')->get();

        return view('rapports.fiche_achats_mp')->with('viewData',$viewData);
    }

    // Fiche des entrées personnalisées
    public function entreeStockMpDate(Request $request): View
    {

        $viewData['title'] = 'Liste des achats matières premières en stock dépôt du '.$request->debut.' au '.$request->fin;

        $dateDebut = $request->input('debut');
        $dateFin = $request->input('fin');

        $viewData['entrees'] = AchatStockMaison::whereBetween('created_at', [$dateDebut, $dateFin])->with('fournisseur','stockMaison')->get();

        return view('rapports.fiche_achats_mp')->with('viewData',$viewData);
    }

    public function entreeStockMpAll(): View
    {

        $viewData['title'] = 'Liste des achats matières premières en stock dépôt' ;

        $viewData['entrees'] = AchatStockMaison::with('fournisseur','stockMaison')->get();

        return view('rapports.fiche_achats_mp')->with('viewData',$viewData);
    }


    public function stockMpUsine(): View
    {

        $viewData['title'] = 'Liste des matières premières disponibles en usine';

        $viewData['stockMpUsine'] = StockUsine::with('stockMaison')->get();

        return view('rapports.fiche_stock_mp_usine')->with('viewData',$viewData);
    }


    public function stockPf(): View
    {

        $viewData['title'] = 'Liste des produits finis disponibles en stock';

        $viewData['stockPf'] = StockPf::all();

        return view('rapports.fiche_stock_pf')->with('viewData',$viewData);
    }

    //Liste des produits dans le stock
    public function stockBoulangerie(Request $request, $site): View
    {
        $viewData['sites'] = Site::all();

        if($site == 'all'){
            $viewData['title'] = 'Liste des produits disponibles dans tous les points de vente ';
            $viewData['stockBoulangerie'] = StockBoulangerie::with('stockProduitFinis')->get();
        }else{
            $sites = Site::find($site);
            $viewData['title'] = 'Liste des produits disponibles dans le point de vente '.$sites->nom;
            $viewData['stockBoulangerie'] = StockBoulangerie::where('site_id',$sites->id)->with('stockProduitFinis')->get();
        }

        return view('rapports.fiche_stock_boulangerie')->with('viewData',$viewData);
    }

    //Liste des toutes les productions
    public function productionAll(): View
    {

        $viewData['title'] = 'Historique des productions';

        $viewData['productions'] = Production::with('produitFinis')->get();

        return view('rapports.fiche_productions')->with('viewData',$viewData);
    }


    // Fiche des productions journalieres
    public function productionJour(): View
    {

        $viewData['title'] = 'Liste des productions du '. date('d-m-Y');

        $viewData['productions'] = Production::whereDate('created_at', Carbon::today())->with('produitFinis')->get();

        return view('rapports.fiche_productions')->with('viewData',$viewData);
    }

    // Fiche des productions hebdomadaires
    public function productionHebdo(): View
    {

        $viewData['title'] = 'Liste des productions de la semaine';

        $debutSemaine = Carbon::now()->startOfWeek();
        $finSemaine = Carbon::now()->endOfWeek();

        $viewData['productions'] = Production::whereBetween('created_at', [$debutSemaine, $finSemaine])->with('produitFinis')->get();

        return view('rapports.fiche_productions')->with('viewData',$viewData);
    }

    // Fiche des productions annuelles
    public function productionAnnuel(): View
    {

        $viewData['title'] = 'Liste des productions de l\'année';

        $debutAnnee = Carbon::now()->startOfYear();
        $finAnnee = Carbon::now()->endOfYear();

        $viewData['productions'] = Production::whereBetween('created_at', [$debutAnnee, $finAnnee])->with('produitFinis')->get();

        return view('rapports.fiche_productions')->with('viewData',$viewData);
    }

    // Fiche des productions personnalisées
    public function productionDate(Request $request): View
    {

        $viewData['title'] = 'Liste des productions du '.$request->debut.' au '.$request->fin;

        $dateDebut = $request->input('debut');
        $dateFin = $request->input('fin');

        $viewData['productions'] = Production::whereBetween('created_at', [$dateDebut, $dateFin])->with('produitFinis')->get();

        return view('rapports.fiche_productions')->with('viewData',$viewData);
    }


    // Fiche des entrées journalieres
    public function syntheseJour(): View
    {

        $viewData['title'] = 'Tableau Synthèse des inventaires du '. date('d-m-Y');

        $viewData['syntheses'] = Synthese::whereBetween('created_at', Carbon::today())->with('site','user')->get();

        return view('rapports.fiche_synthese')->with('viewData',$viewData);
    }

    // Fiche des entrées hebdomadaires
    public function syntheseHebdo(): View
    {

        $viewData['title'] = 'Tableau Synthèse des inventaires de la semaine';

        $debutSemaine = Carbon::now()->startOfWeek();
        $finSemaine = Carbon::now()->endOfWeek();

        $viewData['syntheses'] = Synthese::whereBetween('created_at', [$debutSemaine, $finSemaine])->with('site','user')->get();

        return view('rapports.fiche_synthese')->with('viewData',$viewData);
    }

    // Fiche des entrées hebdomadaires
    public function syntheseMensuel(): View
    {

        $viewData['title'] = 'Tableau Synthèse des inventaires du mois';

        $debutMois = Carbon::now()->startOfMonth();
        $finMois = Carbon::now()->endOfMonth();

        $viewData['syntheses'] = Synthese::whereBetween('created_at', [$debutMois, $finMois])->with('site','user')->get();

        return view('rapports.fiche_synthese')->with('viewData',$viewData);
    }

    // Fiche des entrées hebdomadaires
    public function syntheseAnnuel(): View
    {

        $viewData['title'] = 'Tableau Synthèse des inventaires de l\'année';

        $debutAnnee = Carbon::now()->startOfYear();
        $finAnnee = Carbon::now()->endOfYear();

        $viewData['syntheses'] = Synthese::whereBetween('created_at', [$debutAnnee, $finAnnee])->with('site','user')->get();

        return view('rapports.fiche_synthese')->with('viewData',$viewData);
    }

    // Fiche des entrées personnalisées
    public function syntheseDate(Request $request): View
    {

        $viewData['title'] = 'Tableau Synthèse des inventaires du '.$request->debut.' au '.$request->fin;

        $dateDebut = $request->input('debut');
        $dateFin = $request->input('fin');

        $viewData['syntheses'] = Synthese::whereBetween('created_at', [$dateDebut, $dateFin])->with('site','user')->get();

        return view('rapports.fiche_synthese')->with('viewData',$viewData);
    }

    public function syntheseAll(): View
    {

        $viewData['title'] = 'Tableau Synthèse des inventaires' ;

        $viewData['syntheses'] = Synthese::with('site','user')->get();

        return view('rapports.fiche_synthese')->with('viewData',$viewData);
    }


    // Fiche des ventes journalieres
    public function venteJour(): View
    {

        $viewData['title'] = 'Liste des ventes du '. date('d-m-Y');

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->whereDate('created_at', Carbon::today())->with('ventes')->get();

        return view('rapports.fiche_ventes')->with('viewData',$viewData);
    }

    // Fiche des ventes hebdomadaires
    public function venteHebdo(): View
    {

        $viewData['title'] = 'Liste des ventes de la semaine';

        $debutSemaine = Carbon::now()->startOfWeek();
        $finSemaine = Carbon::now()->endOfWeek();

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->whereBetween('created_at', [$debutSemaine, $finSemaine])->with('ventes')->get();

        return view('rapports.fiche_ventes')->with('viewData',$viewData);
    }

    // Fiche des ventes annuelles
    public function venteAnnuel(): View
    {

        $viewData['title'] = 'Liste des ventes de l\'année';

        $debutAnnee = Carbon::now()->startOfYear();
        $finAnnee = Carbon::now()->endOfYear();

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->whereBetween('created_at', [$debutAnnee, $finAnnee])->with('ventes')->get();

        return view('rapports.fiche_ventes')->with('viewData',$viewData);
    }

    // Fiche des ventes personnalise
    public function venteDate(Request $request): View
    {

        $viewData['title'] = 'Liste des ventes du '.$request->debut.' au '.$request->fin;

        $dateDebut = $request->input('debut');
        $dateFin = $request->input('fin');

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->whereBetween('created_at', [$dateDebut, $dateFin])->with('ventes')->get();

        return view('rapports.fiche_ventes')->with('viewData',$viewData);
    }

    //Fiche de toutes les ventes
    public function venteAll(): View
    {

        $viewData['title'] = 'Liste des ventes ';

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->get();

        return view('rapports.fiche_ventes')->with('viewData',$viewData);
    }


    // Fiche des ventes journalieres
    public function dettesJour(): View
    {

        $viewData['title'] = 'Liste des dettes du '. date('d-m-Y');

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->where('reste', '>', 0)->whereDate('created_at', Carbon::today())->with('ventes')->get();

        return view('rapports.fiche_dettes_clients')->with('viewData',$viewData);
    }

    // Fiche des ventes hebdomadaires
    public function dettesHebdo(): View
    {

        $viewData['title'] = 'Liste des dettes de la semaine';

        $debutSemaine = Carbon::now()->startOfWeek();
        $finSemaine = Carbon::now()->endOfWeek();

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->where('reste', '>', 0)->whereBetween('created_at', [$debutSemaine, $finSemaine])->with('ventes')->get();

        return view('rapports.fiche_dettes_clients')->with('viewData',$viewData);
    }

    // Fiche des ventes annuelles
    public function dettesAnnuel(): View
    {

        $viewData['title'] = 'Liste des dettes de l\'année';

        $debutAnnee = Carbon::now()->startOfYear();
        $finAnnee = Carbon::now()->endOfYear();

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->where('reste', '>', 0)->whereBetween('created_at', [$debutAnnee, $finAnnee])->with('ventes')->get();

        return view('rapports.fiche_dettes_clients')->with('viewData',$viewData);
    }

    // Fiche des ventes personnalise
    public function dettesDate(Request $request): View
    {

        $viewData['title'] = 'Liste des dettes du '.$request->debut.' au '.$request->fin;

        $dateDebut = $request->input('debut');
        $dateFin = $request->input('fin');

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->where('reste', '>', 0)->whereBetween('created_at', [$dateDebut, $dateFin])->with('ventes')->get();

        return view('rapports.fiche_dettes_clients')->with('viewData',$viewData);
    }

    //Fiche de toutes les ventes
    public function dettesAll(): View
    {

        $viewData['title'] = 'Liste des dettes clients ';

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->where('reste', '>', 0)->get();

        return view('rapports.fiche_dettes_clients')->with('viewData',$viewData);
    }

    // Fiche des ventes journalieres
    public function paiementsJour(): View
    {

        $viewData['title'] = 'Liste des paiements du '. date('d-m-Y');

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->whereDate('created_at', Carbon::today())->with('paiements')->get();

        return view('rapports.fiche_paiements_clients')->with('viewData',$viewData);
    }

    // Fiche des ventes hebdomadaires
    public function paiementsHebdo(): View
    {

        $viewData['title'] = 'Liste des paiements de la semaine';

        $debutSemaine = Carbon::now()->startOfWeek();
        $finSemaine = Carbon::now()->endOfWeek();

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->whereBetween('created_at', [$debutSemaine, $finSemaine])->with('paiements')->get();

        return view('rapports.fiche_paiements_clients')->with('viewData',$viewData);
    }

    // Fiche des ventes annuelles
    public function paiementsAnnuel(): View
    {

        $viewData['title'] = 'Liste des paiements de l\'année';

        $debutAnnee = Carbon::now()->startOfYear();
        $finAnnee = Carbon::now()->endOfYear();

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->whereBetween('created_at', [$debutAnnee, $finAnnee])->with('paiements')->get();

        return view('rapports.fiche_paiements_clients')->with('viewData',$viewData);
    }

    // Fiche des ventes personnalise
    public function paiementsDate(Request $request): View
    {

        $viewData['title'] = 'Liste des paiements du '.$request->debut.' au '.$request->fin;

        $dateDebut = $request->input('debut');
        $dateFin = $request->input('fin');

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->whereBetween('created_at', [$dateDebut, $dateFin])->with('paiements')->get();

        return view('rapports.fiche_paiements_clients')->with('viewData',$viewData);
    }

    //Fiche de toutes les ventes
    public function paiementsAll(): View
    {

        $viewData['title'] = 'Liste des paiements clients ';

        $viewData['commandes'] = CommandeClient::orderBy('id', 'DESC')->with('paiements')->get();

        return view('rapports.fiche_paiements_clients')->with('viewData',$viewData);
    }


    // Fiche des ventes journalieres
    public function depenseJour(): View
    {

        $viewData['title'] = 'Liste des dépenses du '. date('d-m-Y');

        $viewData['depenses'] = Depense::whereDate('created_at', Carbon::today())->get();

        return view('rapports.fiche_depenses')->with('viewData',$viewData);
    }

    // Fiche des ventes hebdomadaires
    public function depenseHebdo(): View
    {

        $viewData['title'] = 'Liste des dépenses de la semaine';

        $debutSemaine = Carbon::now()->startOfWeek();
        $finSemaine = Carbon::now()->endOfWeek();

        $viewData['depenses'] = Depense::whereBetween('created_at', [$debutSemaine, $finSemaine])->get();

        return view('rapports.fiche_depenses')->with('viewData',$viewData);
    }

    // Fiche des ventes annuelles
    public function depenseAnnuel(): View
    {

        $viewData['title'] = 'Liste des dépenses de l\'année';

        $debutAnnee = Carbon::now()->startOfYear();
        $finAnnee = Carbon::now()->endOfYear();

        $viewData['depenses'] = Depense::whereBetween('created_at', [$debutAnnee, $finAnnee])->get();

        return view('rapports.fiche_depenses')->with('viewData',$viewData);
    }

    // Fiche des ventes personnalise
    public function depenseDate(Request $request): View
    {

        $viewData['title'] = 'Liste des dépenses du '.$request->debut.' au '.$request->fin;

        $dateDebut = $request->input('debut');
        $dateFin = $request->input('fin');

        $viewData['depenses'] = Depense::whereBetween('created_at', [$dateDebut, $dateFin])->get();

        return view('rapports.fiche_depenses')->with('viewData',$viewData);
    }

    //Fiche de toutes les ventes
    public function depenseAll(): View
    {

        $viewData['title'] = 'Liste des dépenses ';

        $viewData['depenses'] = Depense::all();

        return view('rapports.fiche_depenses')->with('viewData',$viewData);
    }
}
