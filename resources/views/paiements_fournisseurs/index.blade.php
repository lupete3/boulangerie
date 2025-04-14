@extends('layouts.backend')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Historique des paiements</h1>
        </div>

        <div class="section-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Fournisseur</th>
                            <th>Achat</th>
                            <th>Montant payé</th>
                            <th>Mode de paiement</th>
                            <th>Observation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paiements as $paiement)
                            <tr>
                                <td>{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $paiement->dette->fournisseur->nom ?? '—' }}</td>
                                <td>#{{ $paiement->dette->id_achat ?? '—' }}</td>
                                <td>{{ number_format($paiement->montant, 2) }} FC</td>
                                <td>{{ $paiement->mode_paiement }}</td>
                                <td>{{ $paiement->observation ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucun paiement enregistré</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
