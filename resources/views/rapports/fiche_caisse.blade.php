@extends('layouts.print')

@section('content')

<style>
    th, td {
        font-size: 15px;
        text-align: center;
    }

    .table thead th {
        background-color: #f2f2f2;
    }

    .signature-zone {
        margin-top: 40px;
        display: flex;
        justify-content: space-between;
    }

    .signature-block {
        text-align: center;
        width: 30%;
    }

    .print-btn {
        margin: 20px 0;
    }
</style>

<div class="container-fluid pt-4">
    <section class="section">

        <div class="section-body">
            <div class="">

                <div class="row">
                    <div class="col-md-12 text-center">
                        <h4>{{ $viewData['title'] ?? 'Livre de caisse' }}</h4>
                        <p>Date : {{ \Carbon\Carbon::now()->format('d/m/Y') }} | Heure : {{ \Carbon\Carbon::now()->format('H:i') }}</p>
                    </div>
                </div>

                <form action="{{ route('rapports.livreCaisse') }}" method="GET" class="row mb-4 valider">
                    @csrf
                    <div class="col-md-3">
                        <select name="filtre" id="filtre" class="form-control" onchange="toggleFiltres()">
                            <option value="">-- Choisir un filtre --</option>
                            <option value="jour">Aujourd'hui</option>
                            <option value="semaine">Cette semaine</option>
                            <option value="date">Une date précise</option>
                            <option value="periode">Période personnalisée</option>
                        </select>
                    </div>

                    <div class="col-md-3" id="date_unique_div" style="display: none;">
                        <input type="date" name="date_unique" class="form-control" placeholder="Date unique">
                    </div>

                    <div class="col-md-3" id="periode_div" style="display: none;">
                        <div class="row">
                            <div class="col-6">
                                <input type="date" name="date_debut" class="form-control" placeholder="Début">
                            </div>
                            <div class="col-6">
                                <input type="date" name="date_fin" class="form-control" placeholder="Fin">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Appliquer</button>
                    </div>
                </form>

                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Date</th>
                            <th>Motif</th>
                            <th>Entrée</th>
                            <th>Sortie</th>
                            <th>Solde</th>
                            <th>Utilisateur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $num = 1;
                            $totalEntree = 0;
                            $totalSortie = 0;
                        @endphp

                        @forelse ($viewData['operations'] as $op)
                            <tr>
                                <td>{{ $num++ }}</td>
                                <td>{{ $op->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $op->motif }}</td>
                                <td>
                                    @if($op->type_operation === 'entree')
                                        {{ number_format($op->montant, 2, ',', ' ') }}
                                        @php $totalEntree += $op->montant; @endphp
                                    @endif
                                </td>
                                <td>
                                    @if($op->type_operation === 'sortie')
                                        {{ number_format($op->montant, 2, ',', ' ') }}
                                        @php $totalSortie += $op->montant; @endphp
                                    @endif
                                </td>
                                <td><strong>{{ number_format($op->solde_apres_operation, 2, ',', ' ') }}</strong></td>
                                <td>{{ $op->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Aucune opération trouvée</td>
                            </tr>
                        @endforelse

                        <tr style="background-color: #f1f1f1">
                            <td colspan="3"><strong>TOTAL</strong></td>
                            <td><strong>{{ number_format($totalEntree, 2, ',', ' ') }} Fc</strong></td>
                            <td><strong>{{ number_format($totalSortie, 2, ',', ' ') }} Fc</strong></td>
                            <td colspan="2"> <strong>{{ number_format(($totalEntree - $totalSortie), 2, ',', ' ') }} Fc</strong></td>
                        </tr>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-md-3 offset-3">
                      <button type="button" class="btn btn-primary print pull-right valider"><span class="fa fa-print"></span> Imprimer</button>
                      <a href="{{ url()->previous() }}" class="btn btn-secondary valider">
                        <span class="fa fa-arrow-left"></span> Retour
                      </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

@endsection

<script>
    function toggleFiltres() {
        let filtre = document.getElementById('filtre').value;
        document.getElementById('date_unique_div').style.display = (filtre === 'date') ? 'block' : 'none';
        document.getElementById('periode_div').style.display = (filtre === 'periode') ? 'block' : 'none';
    }
</script>

