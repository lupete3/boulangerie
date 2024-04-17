<?php

namespace App\Http\Controllers;

use App\Models\AchatStockMaison;
use App\Models\Depense;
use App\Models\Fournisseur;
use App\Models\Production;
use App\Models\StockBoulangerie;
use Carbon\Carbon;
use App\Models\StockMaison;
use App\Models\StockPf;
use App\Models\StockUsine;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    //Main DashboardController

    public function dashboard(): View
    {

        $viewData = [];

        $viewData['title'] = 'Tableau de bord';

        $viewData['stockMaisons'] = StockMaison::all();

        $viewData['stockUsines'] = StockUsine::with('stockMaison')->get();

        $viewData['stockPfs'] = StockPf::all();

        $viewData['stockBoulangerie'] = StockBoulangerie::with('stockProduitFinis')->get();

        $viewData['achats'] = AchatStockMaison::whereDate('created_at', Carbon::today())->with('stockMaison','fournisseur')->get();

        $viewData['ventes'] = Vente::whereDate('created_at', Carbon::today())->get();
        
        $viewData['depenses'] = Depense::whereDate('created_at', Carbon::today())->get();
        
        $viewData['fournisseurs'] = Fournisseur::all();
        
        // // Vérifier si l'utilisateur est un administrateur
        // if (Auth::user()->role !== 'admin') {

        //     Auth::guard('web')->logout();

        //     abort(403, 'Vous n\'êtes pas autorisés à accéder à cette page');
        // }

        return view('dashboard')->with('viewData',$viewData);
        
    }

    //Gestion des utilisateurs
    public function usersIndex(): View
    {

        $viewData['title'] = 'Liste des utilisateurs';

        $viewData['users'] = User::all();
        
        return view('users.index')->with('viewData',$viewData);
    }

    //Gestion des utilisateurs
    public function usersCreate(): View
    {

        $viewData['title'] = 'Ajouter un utilisateur';
        
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
            'password' => Hash::make($request->password),
            
        ]);

        // Vérifier si c'est le premier utilisateur inscrit
        if (User::count() === 1) {
            $user->role = 'admin';
            $user->save();
        }

        return redirect()->back()->with('success','Utilisateur ajouté avec succès');
    }

    //Gestion des utilisateurs
    public function usersEdit(User $user): View
    {

        $viewData['title'] = $user->name;
        
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

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->back()->with('success','Mise à jour effectuée avec succès');
    }


    public function usersDelete(User $user)
    {

        $user->delete();
        
        return redirect()->back()->with('success', 'Compte utilisateur supprimé');
    }


    public function stockMpMaison(): View
    {

        $viewData['title'] = 'Liste des matières premières disponibles en stock';

        $viewData['stockMpMaison'] = StockMaison::orderBy('designation', 'ASC')->get();
        
        return view('rapports.fiche_stock_mp_maison')->with('viewData',$viewData);
    }

   
    // Fiche des entrées journalieres
    public function entreeStockMpJour(): View
    {

        $viewData['title'] = 'Liste des achats des matières premières du '. date('d-m-Y');

        $viewData['entrees'] = AchatStockMaison::whereDate('created_at', Carbon::today())->with('fournisseur','stockMaison')->get();
        
        return view('rapports.fiche_achats_mp')->with('viewData',$viewData);
    }

    // Fiche des entrées hebdomadaires
    public function entreeStockMpHebdo(): View
    {

        $viewData['title'] = 'Liste des achats matières premières de la semaine';
         
        $debutSemaine = Carbon::now()->startOfWeek();
        $finSemaine = Carbon::now()->endOfWeek();

        $viewData['entrees'] = AchatStockMaison::whereBetween('created_at', [$debutSemaine, $finSemaine])->with('fournisseur','stockMaison')->get();
        
        return view('rapports.fiche_achats_mp')->with('viewData',$viewData);
    }

    // Fiche des entrées hebdomadaires
    public function entreeStockMpAnnuel(): View
    {

        $viewData['title'] = 'Liste des achats matières premières de l\'année';
         
        $debutAnnee = Carbon::now()->startOfYear();
        $finAnnee = Carbon::now()->endOfYear();

        $viewData['entrees'] = AchatStockMaison::whereBetween('created_at', [$debutAnnee, $finAnnee])->with('fournisseur','stockMaison')->get();
        
        return view('rapports.fiche_achats_mp')->with('viewData',$viewData);
    }

    // Fiche des entrées personnalisées
    public function entreeStockMpDate(Request $request): View
    {

        $viewData['title'] = 'Liste des achats matières premières du '.$request->debut.' au '.$request->fin;
         
        $dateDebut = $request->input('debut');
        $dateFin = $request->input('fin');

        $viewData['entrees'] = AchatStockMaison::whereBetween('created_at', [$dateDebut, $dateFin])->with('fournisseur','stockMaison')->get();
        
        return view('rapports.fiche_achats_mp')->with('viewData',$viewData);
    }

    public function entreeStockMpAll(): View
    {

        $viewData['title'] = 'Liste des achats matières premières stock' ;

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
    public function stockBoulangerie(): View
    {

        $viewData['title'] = 'Liste des produits disponibles en boulangerie';

        $viewData['stockBoulangerie'] = StockBoulangerie::with('stockProduitFinis')->get();
        
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


    // Fiche des ventes journalieres
    public function venteJour(): View
    {

        $viewData['title'] = 'Liste des ventes du '. date('d-m-Y');

        $viewData['ventes'] = Vente::whereDate('created_at', Carbon::today())->with('stockBoulangerie')->get();
        
        return view('rapports.fiche_ventes')->with('viewData',$viewData);
    }

    // Fiche des ventes hebdomadaires
    public function venteHebdo(): View
    {

        $viewData['title'] = 'Liste des ventes de la semaine';
         
        $debutSemaine = Carbon::now()->startOfWeek();
        $finSemaine = Carbon::now()->endOfWeek();

        $viewData['ventes'] = Vente::whereBetween('created_at', [$debutSemaine, $finSemaine])->with('stockBoulangerie')->get();
        
        return view('rapports.fiche_ventes')->with('viewData',$viewData);
    }

    // Fiche des ventes annuelles
    public function venteAnnuel(): View
    {

        $viewData['title'] = 'Liste des ventes de l\'année';
         
        $debutAnnee = Carbon::now()->startOfYear();
        $finAnnee = Carbon::now()->endOfYear();

        $viewData['ventes'] = Vente::whereBetween('created_at', [$debutAnnee, $finAnnee])->with('stockBoulangerie')->get();
        
        return view('rapports.fiche_ventes')->with('viewData',$viewData);
    }

    // Fiche des ventes personnalise
    public function venteDate(Request $request): View
    {

        $viewData['title'] = 'Liste des ventes du '.$request->debut.' au '.$request->fin;
         
        $dateDebut = $request->input('debut');
        $dateFin = $request->input('fin');

        $viewData['ventes'] = Vente::whereBetween('created_at', [$dateDebut, $dateFin])->with('stockBoulangerie')->get();
        
        return view('rapports.fiche_ventes')->with('viewData',$viewData);
    }

    //Fiche de toutes les ventes
    public function venteAll(): View
    {

        $viewData['title'] = 'Liste des ventes ';

        $viewData['ventes'] = Vente::with('stockBoulangerie')->get();
        
        return view('rapports.fiche_ventes')->with('viewData',$viewData);
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
