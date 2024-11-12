<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChefDistributionController;
use App\Http\Controllers\ChefProductionController;
use App\Http\Controllers\ClotureController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OperationGuichetController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SyntheseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function(){
    return view('auth.login');
});


// Routes pour l'admin
Route::middleware(['auth', 'admin'])->group(function() {

    Route::resource('/sites', SiteController::class);

    Route::resource('/categories', CategoryController::class);

    Route::resource('/partenaires', PartenaireController::class);

    Route::resource('/categories', CategoryController::class);

    Route::resource('/produits', ProduitController::class);

    Route::get('/operation_guichets/admin', [OperationGuichetController::class, 'index'])->name('operation_guichets.indexAdmin');

    Route::resource('/syntheses', SyntheseController::class);

    Route::get('/utilisateurs', [DashboardController::class, 'usersIndex'])->name('dashboard.usersIndex');
    Route::get('/utilisateurs/create', [DashboardController::class, 'usersCreate'])->name('dashboard.usersCreate');
    Route::post('/utilisateurs/store', [DashboardController::class, 'usersStore'])->name('dashboard.usersStore');
    Route::get('/utilisateurs/{user}/edit', [DashboardController::class, 'usersEdit'])->name('dashboard.usersEdit');
    Route::post('/utilisateurs/{user}/update', [DashboardController::class, 'usersUpdate'])->name('dashboard.usersUpdate');
    Route::post('/utilisateurs/{user}/supprimer', [DashboardController::class, 'usersDelete'])->name('dashboard.usersDelete');
});

// Routes pour le chef de production
Route::middleware(['auth', 'chef_production'])->group(function() {

    Route::resource('/productions', ProductionController::class);

});


// Routes pour le chef de districution
Route::middleware(['auth', 'chef_distribution'])->group(function() {

    Route::resource('/commandes', CommandeController::class);
    Route::post('/commandes/preview', [CommandeController::class, 'preview'])->name('commandes.preview');

    Route::resource('/distributions', DistributionController::class);

});

// Routes pour le chef dépôt
Route::middleware(['auth', 'chef_depot'])->group(function() {

    Route::get('/depots', [DepotController::class, 'index'])->name('depots.index');
    Route::get('/depots/create', [DepotController::class, 'create'])->name('depots.create');
    Route::post('/depots/store', [DepotController::class, 'store'])->name('depots.store');

});

Route::middleware(['auth', 'guichetier'])->group(function() {

    Route::get('/operation_guichets/user', [OperationGuichetController::class, 'index'])->name('operation_guichets.index');
    Route::get('/operation_guichets/create', [OperationGuichetController::class, 'create'])->name('operation_guichets.create');
    Route::post('/operation_guichets/store', [OperationGuichetController::class, 'store'])->name('operation_guichets.store');

});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'verified', ])->group(function () {

    Route::get('/rapports/stock-mp', [DashboardController::class, 'stockMpMaison'])->name('rapports.stockMpMaison');
    Route::get('/rapports/achats-mp-all', [DashboardController::class, 'entreeStockMpAll'])->name('rapports.entreeStockMpAll');
    Route::get('/rapports/achats-mp-journalier', [DashboardController::class, 'entreeStockMpJour'])->name('rapports.entreeStockMpJour');
    Route::get('/rapports/achats-mp-hebdomadaire', [DashboardController::class, 'entreeStockMpHebdo'])->name('rapports.entreeStockMpHebdo');
    Route::get('/rapports/achats-mp-annuel', [DashboardController::class, 'entreeStockMpAnnuel'])->name('rapports.entreeStockMpAnnuel');
    Route::post('/rapports/achats-mp-personnalise', [DashboardController::class, 'entreeStockMpDate'])->name('rapports.entreeStockMpDate');

    Route::get('/rapports/productions-all', [DashboardController::class, 'productionAll'])->name('rapports.productionAll');
    Route::get('/rapports/productions-journalier', [DashboardController::class, 'productionJour'])->name('rapports.productionJour');
    Route::get('/rapports/productions-hebdomadaire', [DashboardController::class, 'productionHebdo'])->name('rapports.productionHebdo');
    Route::get('/rapports/productions-annuel', [DashboardController::class, 'productionAnnuel'])->name('rapports.productionAnnuel');
    Route::post('/rapports/productions-personnalise', [DashboardController::class, 'productionDate'])->name('rapports.productionDate');

    Route::get('/rapports/ventes-all', [DashboardController::class, 'venteAll'])->name('rapports.venteAll');
    Route::get('/rapports/ventes-journalier', [DashboardController::class, 'venteJour'])->name('rapports.venteJour');
    Route::get('/rapports/ventes-hebdomadaire', [DashboardController::class, 'venteHebdo'])->name('rapports.venteHebdo');
    Route::get('/rapports/ventes-annuel', [DashboardController::class, 'venteAnnuel'])->name('rapports.venteAnnuel');
    Route::post('/rapports/ventes-personnalise', [DashboardController::class, 'venteDate'])->name('rapports.venteDate');

    Route::get('/rapports/syntheses-all', [DashboardController::class, 'syntheseAll'])->name('rapports.syntheseAll');
    Route::get('/rapports/syntheses-journalier', [DashboardController::class, 'syntheseJour'])->name('rapports.syntheseJour');
    Route::get('/rapports/syntheses-hebdomadaire', [DashboardController::class, 'syntheseHebdo'])->name('rapports.syntheseHebdo');
    Route::get('/rapports/syntheses-mensuel', [DashboardController::class, 'syntheseMensuel'])->name('rapports.syntheseMensuel');
    Route::get('/rapports/syntheses-annuel', [DashboardController::class, 'syntheseAnnuel'])->name('rapports.syntheseAnnuel');
    Route::post('/rapports/syntheses-personnalise', [DashboardController::class, 'syntheseDate'])->name('rapports.syntheseDate');

    Route::get('/rapports/dettes-all', [DashboardController::class, 'dettesAll'])->name('rapports.dettesAll');
    Route::get('/rapports/dettes-journalier', [DashboardController::class, 'dettesJour'])->name('rapports.dettesJour');
    Route::get('/rapports/dettes-hebdomadaire', [DashboardController::class, 'dettesHebdo'])->name('rapports.dettesHebdo');
    Route::get('/rapports/dettes-annuel', [DashboardController::class, 'dettesAnnuel'])->name('rapports.dettesAnnuel');
    Route::post('/rapports/dettes-personnalise', [DashboardController::class, 'dettesDate'])->name('rapports.dettesDate');

    Route::get('/rapports/paiements-all', [DashboardController::class, 'paiementsAll'])->name('rapports.paiementsAll');
    Route::get('/rapports/paiements-journalier', [DashboardController::class, 'paiementsJour'])->name('rapports.paiementsJour');
    Route::get('/rapports/paiements-hebdomadaire', [DashboardController::class, 'paiementsHebdo'])->name('rapports.paiementsHebdo');
    Route::get('/rapports/paiements-annuel', [DashboardController::class, 'paiementsAnnuel'])->name('rapports.paiementsAnnuel');
    Route::post('/rapports/paiements-personnalise', [DashboardController::class, 'paiementsDate'])->name('rapports.paiementsDate');

    Route::get('/rapports/depenses-all', [DashboardController::class, 'depenseAll'])->name('rapports.depenseAll');
    Route::get('/rapports/depenses-journalier', [DashboardController::class, 'depenseJour'])->name('rapports.depenseJour');
    Route::get('/rapports/depenses-hebdomadaire', [DashboardController::class, 'depenseHebdo'])->name('rapports.depenseHebdo');
    Route::get('/rapports/depenses-annuel', [DashboardController::class, 'depenseAnnuel'])->name('rapports.depenseAnnuel');
    Route::post('/rapports/depenses-personnalise', [DashboardController::class, 'depenseDate'])->name('rapports.depenseDate');

    Route::get('/rapports/stock-mp-usine', [DashboardController::class, 'stockMpUsine'])->name('rapports.stockMpUsine');
    Route::get('/rapports/stock-pf-usine', [DashboardController::class, 'stockPf'])->name('rapports.stockPf');
    Route::get('/rapports/stock-boulangerie/{site}', [DashboardController::class, 'stockBoulangerie'])->name('rapports.stockBoulangerie');

    

});

//Route to 404 page not found
Route::fallback(function(){
    $vieData['title'] = 'Erreur 404';
    return view('404')->with('viewData',$vieData);
});

require __DIR__.'/auth.php';
