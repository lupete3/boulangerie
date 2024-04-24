<?php

use App\Http\Controllers\AchatStockMaisonController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\MouvementStockMpController;
use App\Http\Controllers\MouvementStockPfController;
use App\Http\Controllers\PaiementClientController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\StockBoulangerieController;
use App\Http\Controllers\StockMaisonController;
use App\Http\Controllers\StockPfController;
use App\Http\Controllers\StockUsineController;
use App\Http\Controllers\VenteController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'verified', ])->group(function () {
  
    Route::get('/fournisseurs', [FournisseurController::class, 'index'])->name('fournisseurs.index');
    Route::post('/fournisseurs/store', [FournisseurController::class, 'store'])->name('fournisseurs.store');
    Route::get('/fournisseurs/create', [FournisseurController::class, 'create'])->name('fournisseurs.create');
    Route::get('/fournisseurs/{fournisseur}/edit', [FournisseurController::class, 'edit'])->name('fournisseurs.edit');
    Route::put('/fournisseurs/{fournisseur}/update', [FournisseurController::class, 'update'])->name('fournisseurs.update');
    Route::post('/fournisseurs/{fournisseur}/destroy', [FournisseurController::class, 'destroy'])->name('fournisseurs.destroy');
  
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients/store', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}/update', [ClientController::class, 'update'])->name('clients.update');
    Route::post('/clients/{client}/destroy', [ClientController::class, 'destroy'])->name('clients.destroy');
    
    Route::get('/stock-maison', [StockMaisonController::class, 'index'])->name('stock-maison.index');
    Route::post('/stock-maison/store', [StockMaisonController::class, 'store'])->name('stock-maison.store');
    Route::get('/stock-maison/create', [StockMaisonController::class, 'create'])->name('stock-maison.create');
    Route::get('/stock-maison/{stockMaison}/edit', [StockMaisonController::class, 'edit'])->name('stock-maison.edit');
    Route::put('/stock-maison/{stockMaison}/update', [StockMaisonController::class, 'update'])->name('stock-maison.update');
    Route::post('/stock-maison/{stockMaison}/destroy', [StockMaisonController::class, 'destroy'])->name('stock-maison.destroy');
    
    Route::get('/stock-usine', [StockUsineController::class, 'index'])->name('stock-usine.index');
    Route::post('/stock-usine/store', [StockUsineController::class, 'store'])->name('stock-usine.store');
    Route::get('/stock-usine/create', [StockUsineController::class, 'create'])->name('stock-usine.create');
    Route::get('/stock-usine/{stockUsine}/edit', [StockUsineController::class, 'edit'])->name('stock-usine.edit');
    Route::put('/stock-usine/{stockUsine}/update', [StockUsineController::class, 'update'])->name('stock-usine.update');
    Route::post('/stock-usine/{stockUsine}/destroy', [StockUsineController::class, 'destroy'])->name('stock-usine.destroy');
    
    Route::get('/stock-boulangerie', [StockBoulangerieController::class, 'index'])->name('stock-boulangerie.index');
    Route::post('/stock-boulangerie/store', [StockBoulangerieController::class, 'store'])->name('stock-boulangerie.store');
    Route::get('/stock-boulangerie/create', [StockBoulangerieController::class, 'create'])->name('stock-boulangerie.create');
    Route::get('/stock-boulangerie/{stockBoulangerie}/edit', [StockBoulangerieController::class, 'edit'])->name('stock-boulangerie.edit');
    Route::put('/stock-boulangerie/{stockBoulangerie}/update', [StockBoulangerieController::class, 'update'])->name('stock-boulangerie.update');
    Route::post('/stock-boulangerie/{stockBoulangerie}/destroy', [StockBoulangerieController::class, 'destroy'])->name('stock-boulangerie.destroy');
    
    Route::get('/stock-pf', [StockPfController::class, 'index'])->name('stock-pf.index');
    Route::post('/stock-pf/store', [StockPfController::class, 'store'])->name('stock-pf.store');
    Route::get('/stock-pf/create', [StockPfController::class, 'create'])->name('stock-pf.create');
    Route::get('/stock-pf/{stockPf}/edit', [StockPfController::class, 'edit'])->name('stock-pf.edit');
    Route::put('/stock-pf/{stockPf}/update', [StockPfController::class, 'update'])->name('stock-pf.update');
    Route::post('/stock-pf/{stockPf}/destroy', [StockPfController::class, 'destroy'])->name('stock-pf.destroy');
 
    Route::get('/achat-mp', [AchatStockMaisonController::class, 'index'])->name('achat-mp.index');
    Route::post('/achat-mp/store', [AchatStockMaisonController::class, 'store'])->name('achat-mp.store');
    Route::get('/achat-mp/create', [AchatStockMaisonController::class, 'create'])->name('achat-mp.create');
    Route::get('/achat-mp/{achatStockMaison}/edit', [AchatStockMaisonController::class, 'edit'])->name('achat-mp.edit');
    Route::put('/achat-mp/{achatStockMaison}/update', [AchatStockMaisonController::class, 'update'])->name('achat-mp.update');
    Route::post('/achat-mp/{achatStockMaison}/destroy', [AchatStockMaisonController::class, 'destroy'])->name('achat-mp.destroy');
 
    Route::get('/mouvement-stock-mp', [MouvementStockMpController::class, 'index'])->name('mouvement-stock-mp.index');
    Route::get('/mouvement-stock-mp-usine', [MouvementStockMpController::class, 'indexUsine'])->name('mouvement-stock-mp-usine.index');
    Route::post('/mouvement-stock-mp/store', [MouvementStockMpController::class, 'store'])->name('mouvement-stock-mp.store');
    Route::get('/mouvement-stock-mp/create', [MouvementStockMpController::class, 'create'])->name('mouvement-stock-mp.create');
    Route::get('/mouvement-stock-mp/{mouvementStockMp}/edit', [MouvementStockMpController::class, 'edit'])->name('mouvement-stock-mp.edit');
    Route::put('/mouvement-stock-mp/{mouvementStockMp}/update', [MouvementStockMpController::class, 'update'])->name('mouvement-stock-mp.update');
    Route::post('/mouvement-stock-mp/{mouvementStockMp}/destroy', [MouvementStockMpController::class, 'destroy'])->name('mouvement-stock-mp.destroy');

    Route::get('/mouvement-stock-pf', [MouvementStockPfController::class, 'index'])->name('mouvement-stock-pf.index');
    Route::get('/mouvement-stock-pf-boulangerie', [MouvementStockPfController::class, 'indexBoulangerie'])->name('mouvement-stock-pf-boulangerie.index');
    Route::get('/mouvement-stock-pf-entree', [MouvementStockPfController::class, 'indexStockPf'])->name('mouvement-stock-pf-entree.index');
    Route::post('/mouvement-stock-pf/store', [MouvementStockPfController::class, 'store'])->name('mouvement-stock-pf.store');
    Route::get('/mouvement-stock-pf/create', [MouvementStockPfController::class, 'create'])->name('mouvement-stock-pf.create');
    Route::get('/mouvement-stock-pf/{mouvementStockPf}/edit', [MouvementStockPfController::class, 'edit'])->name('mouvement-stock-pf.edit');
    Route::put('/mouvement-stock-pf/{mouvementStockPf}/update', [MouvementStockPfController::class, 'update'])->name('mouvement-stock-pf.update');
    Route::post('/mouvement-stock-pf/{mouvementStockPf}/destroy', [MouvementStockPfController::class, 'destroy'])->name('mouvement-stock-pf.destroy');

    Route::get('/production', [ProductionController::class, 'index'])->name('production.index');
    Route::post('/production/store', [ProductionController::class, 'store'])->name('production.store');
    Route::get('/production/create', [ProductionController::class, 'create'])->name('production.create');
    Route::get('/production/{production}/edit', [ProductionController::class, 'edit'])->name('production.edit');
    Route::post('/production/{production}/update', [ProductionController::class, 'update'])->name('production.update');
    Route::post('/production/{production}/destroy', [ProductionController::class, 'destroy'])->name('production.destroy');
    Route::post('/add-to-cart', [ProductionController::class, 'addToCart'])->name('production.addToCart');
    Route::post('/clear-cart', [ProductionController::class, 'clearCart'])->name('production.clearCart');
    Route::post('/remove-from-cart', [ProductionController::class, 'removeFromCart'])->name('production.removeFromCart');
    Route::post('/remove-from-cart-edit/{composition}/destroy', [ProductionController::class, 'removeFromCartEdit'])->name('production.removeFromCartEdit');
    Route::post('/add-to-cart-edit/{production}', [ProductionController::class, 'addToCartEdit'])->name('production.addToCartEdit');

    Route::get('/ventes', [VenteController::class, 'index'])->name('ventes.index');
    Route::post('/ventes/store', [VenteController::class, 'store'])->name('ventes.store');
    Route::get('/ventes/create', [VenteController::class, 'create'])->name('ventes.create');
    Route::get('/ventes/{vente}/edit', [VenteController::class, 'edit'])->name('ventes.edit');
    Route::put('/ventes/{vente}/update', [VenteController::class, 'update'])->name('ventes.update');
    Route::post('/ventes/{vente}/destroy', [VenteController::class, 'destroy'])->name('ventes.destroy');
    Route::post('/add-to-cart-vente', [VenteController::class, 'addToCart'])->name('ventes.addToCart');
    Route::post('/clear-cart-vente', [VenteController::class, 'clearCart'])->name('ventes.clearCart');
    Route::post('/remove-from-cart-vente', [VenteController::class, 'removeFromCart'])->name('ventes.removeFromCart');
    Route::post('/remove-from-cart-edit-vente/{vente}/destroy', [VenteController::class, 'removeFromCartEdit'])->name('ventes.removeFromCartEdit');
    Route::post('/add-to-cart-edit-vente/{vente}', [VenteController::class, 'addToCartEdit'])->name('ventes.addToCartEdit');

    Route::get('/paiement-clients', [PaiementClientController::class, 'index'])->name('paiements.index');
    Route::get('/dettes-clients', [PaiementClientController::class, 'detteClients'])->name('paiements.detteClients');
    Route::get('/dettes-create/{commandeClient}', [PaiementClientController::class, 'create'])->name('paiements.create');
    Route::post('/dettes-store', [PaiementClientController::class, 'store'])->name('paiements.store');


    Route::get('/depenses', [DepenseController::class, 'index'])->name('depenses.index');
    Route::post('/depenses/store', [DepenseController::class, 'store'])->name('depenses.store');
    Route::get('/depenses/create', [DepenseController::class, 'create'])->name('depenses.create');
    Route::get('/depenses/{depense}/edit', [DepenseController::class, 'edit'])->name('depenses.edit');
    Route::put('/depenses/{depense}/update', [DepenseController::class, 'update'])->name('depenses.update');
    Route::post('/depenses/{depense}/destroy', [DepenseController::class, 'destroy'])->name('depenses.destroy');

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
    Route::get('/rapports/stock-boulangerie', [DashboardController::class, 'stockBoulangerie'])->name('rapports.stockBoulangerie');

    Route::get('/utilisateurs', [DashboardController::class, 'usersIndex'])->name('dashboard.usersIndex');
    Route::get('/utilisateurs/create', [DashboardController::class, 'usersCreate'])->name('dashboard.usersCreate');
    Route::post('/utilisateurs/store', [DashboardController::class, 'usersStore'])->name('dashboard.usersStore');
    Route::get('/utilisateurs/{user}/edit', [DashboardController::class, 'usersEdit'])->name('dashboard.usersEdit');
    Route::post('/utilisateurs/{user}/update', [DashboardController::class, 'usersUpdate'])->name('dashboard.usersUpdate');
    Route::post('/utilisateurs/{user}/supprimer', [DashboardController::class, 'usersDelete'])->name('dashboard.usersDelete');

});

//Route to 404 page not found
Route::fallback(function(){
    $vieData['title'] = 'Erreur 404';
    return view('404')->with('viewData',$vieData);
});

require __DIR__.'/auth.php';
