<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaisseController extends Controller
{
    public function index(): View
    {
        $viewData = [];
        $viewData['title'] = 'Historique des opérations de caisse';
        $viewData['caisses'] = Caisse::orderBy('id', 'DESC')->get();

        return view('caisses.index')->with('viewData', $viewData);
    }

    public function create(): View
    {
        $viewData = [];
        $viewData['title'] = 'Nouvelle opération de caisse';

        return view('caisses.create')->with('viewData', $viewData);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type_operation' => 'required|in:entree,sortie',
            'montant' => 'required|numeric|min:0',
            'motif' => 'nullable|string',
        ], [
            'type_operation.required' => 'Sélectionner le type d\'opération',
            'montant.required' => 'Saisir le montant',
        ]);

        // Récupérer le dernier solde
        $dernierSolde = Caisse::latest()->first()?->solde_apres_operation ?? 0;

        // Calculer le nouveau solde
        $montant = $request->montant;
        $nouveauSolde = $request->type_operation === 'entree'
            ? $dernierSolde + $montant
            : $dernierSolde - $montant;

        // Créer l'opération
        Caisse::create([
            'type_operation' => $request->type_operation,
            'montant' => $montant,
            'motif' => $request->motif,
            'solde_apres_operation' => $nouveauSolde,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Opération de caisse enregistrée avec succès');
    }

    public function edit(Caisse $caisse): View|RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Action non autorisée');
        }

        $viewData['title'] = 'Modifier une opération de caisse';
        return view('caisses.update', compact('caisse'))->with('viewData', $viewData);
    }

    public function update(Request $request, Caisse $caisse): RedirectResponse
    {
        $this->authorize('update', $caisse);

        // Seul l'admin peut modifier
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $request->validate([
            'montant' => 'required|numeric|min:0',
            'motif' => 'nullable|string',
        ], [
            'montant.required' => 'Saisir le montant',
        ]);

        // Sauvegarder l'ancien montant
        $ancienMontant = $caisse->montant;

        // Mettre à jour les valeurs
        $caisse->montant = $request->montant;
        $caisse->motif = $request->motif;
        $caisse->save();

        // Recalculer les soldes à partir de cette opération
        $this->recalculerSoldes();

        return redirect()->route('caisses.index')->with('success', 'Opération de caisse mise à jour avec succès');
    }

    private function recalculerSoldes(): void
    {
        $solde = 0;

        // Recalculer toutes les opérations dans l'ordre
        $operations = Caisse::orderBy('created_at')->get();

        foreach ($operations as $operation) {
            $montant = $operation->montant;

            if ($operation->type_operation === 'entree') {
                $solde += $montant;
            } else {
                $solde -= $montant;
            }

            $operation->solde_apres_operation = $solde;
            $operation->save();
        }
    }

    public function destroy(Caisse $caisse): RedirectResponse
    {
        $this->authorize('delete', $caisse);

        // Seul l'admin peut supprimer
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès refusé');
        }

        $caisse->delete();

        // Recalcul des soldes
        $this->recalculerSoldes();

        return redirect()->route('caisses.index')->with('success', 'Opération supprimée avec succès');
    }
}
