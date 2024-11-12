@extends('layouts.backend')

<style>
    table tr {
        font-size: 12px;
    }
</style>

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Historique des Opérations de Guichet</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                <div class="breadcrumb-item active">Opérations de Guichet</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Formulaire de filtrage -->
            <form action="@if(Auth::user()->role == 'admin') {{ route('operation_guichets.indexAdmin') }} @else {{ route('operation_guichets.index') }} @endif" method="GET" class="mb-4">
                <div class="form-row">
                    <div class="col-md-3">
                        <label for="date">Date :</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ request('date', now()->toDateString()) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="site_id">Site :</label>
                        <select name="site_id" id="site_id" class="form-control">
                            <option value="">Tous les sites</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(Auth::user()->role == 'admin')
                        
                        <div class="col-md-3">
                            <label for="user_id">Guichetier :</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">Tous les guichetiers</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    @endif
                    <div class="col-md-2">
                        <label for="shift">Shift :</label>
                        <select name="shift" id="shift" class="form-control">
                            <option value="">Tous les shifts</option>
                            <option value="Jour" {{ request('shift') == 'Jour' ? 'selected' : '' }}>Jour</option>
                            <option value="Soir" {{ request('shift') == 'Soir' ? 'selected' : '' }}>Soir</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">Filtrer</button>
                    </div>
                </div>
            </form>

            <!-- Tableau des Opérations de Guichet -->
            <div class="card table-responsive">
                <div class="p-2">
                    <h6>Liste des Opérations de Guichet</h6>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Produit</th>
                            <th>Guichetier</th>
                            <th>Site</th>
                            <th>Shift</th>
                            <th>Qté Trouvée</th>
                            <th>Qté Reçue</th>
                            <th>Qté Restante</th>
                            <th>Qté Vendue</th>
                            <th>Prix</th>
                            <th>Prix Tot</th>
                            <th>Abîmés</th>
                            <th>Consommés</th>
                            <th>Dette</th>
                            <th>Tot Vendue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalQuantityTrouvee = 0;
                            $totalQuantityRecue = 0;
                            $totalQuantityRestante = 0;
                            $totalQuantityVente = 0;
                            $totalPrixTot = 0;
                            $totalAbime = 0;
                            $totalConsomme = 0;
                            $totalDette = 0;
                            $totalVendu = 0;
                        @endphp

                        @forelse($operations as $operation)
                            @php
                                $totalQuantityTrouvee += $operation->quantity_trouvee;
                                $totalQuantityRecue += $operation->quantity_recue;
                                $totalQuantityRestante += $operation->quantity_restante;
                                $totalQuantityVente += $operation->quantity_vente;
                                $prixTot = $operation->prix * $operation->quantity_vente;
                                $totalPrixTot += $prixTot;
                                $abime = $operation->quantity_abimee * $operation->prix;
                                $totalAbime += $abime;
                                $consomme = $operation->quantity_consomme * $operation->prix;
                                $totalConsomme += $consomme;
                                $totalDette += $operation->quantity_dette;
                                $vendu = ($operation->quantity_vente - $operation->quantity_abimee - $operation->quantity_consomme) * $operation->prix - $operation->quantity_dette;
                                $totalVendu += $vendu;
                            @endphp
                            <tr>
                                <td>{{ $operation->created_at->format('d/m/Y') }}</td>
                                <td>{{ $operation->produit->nom }}</td>
                                <td>{{ $operation->user->name }}</td>
                                <td>{{ $operation->site->nom }}</td>
                                <td>{{ $operation->shift }}</td>
                                <td>{{ number_format($operation->quantity_trouvee,0) }}</td>
                                <td>{{ number_format($operation->quantity_recue,0) }}</td>
                                <td>{{ number_format($operation->quantity_restante,0) }}</td>
                                <td>{{ number_format($operation->quantity_vente,0) }}</td>
                                <td>{{ number_format($operation->prix,0) }}Fc</td>
                                <td>{{ number_format($prixTot, 0) }}Fc</td>
                                <td>{{ number_format($abime,0) }}Fc</td>
                                <td>{{ number_format($consomme,0) }}Fc</td>
                                <td>{{ number_format($operation->quantity_dette,0) }}Fc</td>
                                <td>{{ number_format($vendu,0) }}Fc</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center">Aucune opération trouvée pour cette date, ce site et ce shift.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">Total</th>
                            <th>{{ number_format($totalQuantityTrouvee,0) }}</th>
                            <th>{{ number_format($totalQuantityRecue,0) }}</th>
                            <th>{{ number_format($totalQuantityRestante,0) }}</th>
                            <th>{{ number_format($totalQuantityVente,0) }}</th>
                            <th>-</th>
                            <th>{{ number_format($totalPrixTot,0) }}Fc</th>
                            <th>{{ number_format($totalAbime,0) }}Fc</th>
                            <th>{{ number_format($totalConsomme,0) }}Fc</th>
                            <th>{{ number_format($totalDette,0) }}Fc</th>
                            <th>{{ number_format($totalVendu,0) }}Fc</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <!-- Tableau des Enregistrements d'Argent -->
                    <div class="card table-responsive">
                        <div class="p-2">
                            <h6>Dépôt d'Argent en Espèce</h6>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Guichetier</th>
                                    <th>Site</th>
                                    <th>Shift</th>
                                    <th>Montant Physique</th>
                                    <th>Montant de Change</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalMontantPhysique = 0;
                                    $totalMontantChange = 0;
                                @endphp
                                @forelse($argents as $argent)
                                    @php
                                        $totalMontantPhysique += $argent->montant_physique;
                                        $totalMontantChange += $argent->montant_change;
                                    @endphp
                                    <tr>
                                        <td>{{ $argent->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $argent->user->name }}</td>
                                        <td>{{ $argent->site->nom }}</td>
                                        <td>{{ $argent->shift }}</td>
                                        <td>{{ $argent->montant_physique }}</td>
                                        <td>{{ $argent->montant_change }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucun enregistrement d'argent trouvé pour cette date, ce site et ce shift.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">Total</th>
                                    <th>{{ number_format($totalMontantPhysique,0) }}Fc</th>
                                    <th>{{ number_format($totalMontantChange,0) }}Fc</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Tableau des Dépenses -->
                    <div class="card table-responsive">
                        <div class="p-2">
                            <h6>Historique des Dépenses</h6>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Guichetier</th>
                                    <th>Site</th>
                                    <th>Shift</th>
                                    <th>Montant</th>
                                    <th>Motif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalDepense = 0;
                                @endphp
                                @forelse($depenses as $depense)
                                    @php
                                        $totalDepense += $depense->montant;
                                    @endphp
                                    <tr>
                                        <td>{{ $depense->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $depense->user->name }}</td>
                                        <td>{{ $depense->site->nom }}</td>
                                        <td>{{ $depense->shift }}</td>
                                        <td>{{ $depense->montant }}</td>
                                        <td>{{ $depense->motif }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune dépense trouvée pour cette date, ce site et ce shift.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">Total</th>
                                    <th>{{ number_format($totalDepense,0) }}Fc</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tableau de Synthèse -->
            <div class="card table-responsive">
                <div class="p-2">
                    <h6>Synthèse ventes</h6>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Prix Tot</th>
                            <th>Abîmés</th>
                            <th>Consommés</th>
                            <th>Dette</th>
                            <th>Tot Vendue</th>
                            <th>Montant Physique</th>
                            <th>Montant de Change</th>
                            <th>Manquant</th>
                            <th>Excédent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Calcul du manquant et de l'excédent
                            $totalManquant = max(0, $totalVendu - $totalMontantPhysique);
                            $totalExcedent = max(0, $totalMontantPhysique - $totalVendu);
                        @endphp
                        <tr>
                            <td>{{ number_format($totalPrixTot,0) }}Fc</td>
                            <td>{{ number_format($totalAbime,0) }}Fc</td>
                            <td>{{ number_format($totalConsomme,0) }}Fc</td>
                            <td>{{ number_format($totalDette,0) }}Fc</td>
                            <td>{{ number_format($totalVendu,0) }}Fc</td>
                            <td>{{ number_format($totalMontantPhysique,0) }}Fc</td>
                            <td>{{ number_format($totalMontantChange,0) }}Fc</td>
                            <td>{{ number_format($totalManquant,0) }}Fc</td>
                            <td>{{ number_format($totalExcedent,0) }}Fc</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
