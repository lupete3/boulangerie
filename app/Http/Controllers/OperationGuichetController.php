<?php

namespace App\Http\Controllers;

use App\Models\Argent;
use App\Models\DepenseGuichet;
use App\Models\OperationGuichet;
use App\Models\Produit;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationGuichetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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

        return view('operation_guichets.index', compact('operations', 'argents', 'depenses', 'users', 'sites', 'date', 'userId', 'siteId', 'shift'))
            ->with('viewData', $viewData);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $viewData['title'] = 'Cloturer la vente';

        $produits = Produit::all();
        $sites = Site::all();

        return view('operation_guichets.create', compact('produits', 'sites'))->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:sites,id',
            'shift' => 'required|in:Jour,Soir',
            'operations' => 'required|array',
            'operations.*.produit_id' => 'required|exists:produits,id',
            'operations.*.quantity_trouvee' => 'required|numeric|min:0',
            'operations.*.quantity_recue' => 'required|numeric|min:0',
            'operations.*.quantity_restante' => 'required|numeric|min:0',
            'operations.*.quantity_abimee' => 'required|numeric|min:0',
            'operations.*.quantity_consomme' => 'required|numeric|min:0',
            'operations.*.quantity_dette' => 'required|numeric|min:0',
            'montant_physique' => 'required|numeric|min:0',
            'montant_change' => 'required|numeric|min:0',
            'depenses' => 'array',
            'depenses.*.montant' => 'nullable|numeric|min:0',
            'depenses.*.motif' => 'nullable|string|max:255',
        ]);

        // Calcul du total des ventes (quantity_vente * prix)
        $totalVentes = 0;
        foreach ($request->input('operations') as $operation) {
            $produit = Produit::find($operation['produit_id']);
            $totalVentes += ($operation['quantity_trouvee'] + $operation['quantity_recue'] - $operation['quantity_restante']) * $produit->prix;
        }

        // Vérification que le montant déposé ne dépasse pas la somme totale des ventes
        $montantDepose = $request->input('montant_physique');
        if ($montantDepose > $totalVentes) {
            return redirect()->back()->withErrors(['montant_physique' => 'Le montant déposé ne peut pas dépasser la valeur totale des ventes. Veuillez vérifier les informations saisies.']);
        }

        // Enregistrement des opérations de guichet pour chaque produit
        foreach ($request->operations as $data) {
            $produit = Produit::find($data['produit_id']);
            $quantity_vente = $data['quantity_trouvee'] + $data['quantity_recue'] - $data['quantity_restante'];

            OperationGuichet::create([
                'produit_id' => $data['produit_id'],
                'user_id' => Auth::id(),
                'site_id' => $request->site_id,
                'shift' => $request->shift,
                'quantity_trouvee' => $data['quantity_trouvee'],
                'quantity_recue' => $data['quantity_recue'],
                'quantity_restante' => $data['quantity_restante'],
                'quantity_abimee' => $data['quantity_abimee'],
                'quantity_consomme' => $data['quantity_consomme'],
                'quantity_dette' => $data['quantity_dette'],
                'quantity_vente' => $quantity_vente,
                'prix' => $produit->prix,
            ]);
        }

        // Enregistrement des montants d'argent (physique et change)
        Argent::create([
            'user_id' => Auth::id(),
            'site_id' => $request->site_id,
            'shift' => $request->shift,
            'montant_physique' => $request->montant_physique,
            'montant_change' => $request->montant_change,
        ]);

        // Enregistrement des dépenses associées
        if($request->depenses){
            foreach ($request->depenses as $depense) {
                if (!empty($depense['montant']) && !empty($depense['motif'])) {
                    DepenseGuichet::create([
                        'user_id' => Auth::id(),
                        'site_id' => $request->site_id,
                        'shift' => $request->shift,
                        'montant' => $depense['montant'],
                        'motif' => $depense['motif'],
                    ]);
                }
            }
        }

        return redirect()->route('operation_guichets.index')->with('success', 'Opérations de guichet enregistrées avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OperationGuichet $operationGuichet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OperationGuichet $operationGuichet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OperationGuichet $operationGuichet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OperationGuichet $operationGuichet)
    {
        //
    }
}
