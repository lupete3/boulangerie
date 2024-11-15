@extends('layouts.backend')

<style>
    table tr {
        font-size: 12px;
    }
</style>

@section('content')
<div class="main-content">
    <section class="section" style="margin:-20px">
        <div class="section-body">
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible" id="msg" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h6>{{ Session::get('error') }}</h6>
                </div>
            @endif
            <form id="productionForm" method="post" action="{{ route('productions.store') }}">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>Production de la journée</h4>
                        <div class="card-header-action">
                            <a href="{{ route('productions.index')}}" class="btn btn-icon icon-left btn-success">
                                <i class="fas fa-list-alt"></i> Liste productions
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm" id="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité Demandée (kg)</th>
                                    <th>Quantité Demandée</th>
                                    <th>Quantité Produite</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produits as $produit)
                                    <tr>
                                        <td>{{ $produit->nom }}</td>
                                        <td>
                                            <input type="number" min="0" step="0.01" class="form-control form-control-sm"
                                                   value="{{ $produit->quantity_demande ?? 0 }}" disabled>
                                            <input type="hidden" name="productions[{{ $produit->id }}][quantity_demande]"
                                                   value="{{ $produit->quantity_demande ?? 0 }}">
                                        </td>
                                        <td>
                                            <input type="number" min="0" step="0.01" class="form-control form-control-sm"
                                                   value="{{ $produit->quantity_demande_prod ?? 0 }}" disabled>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm produit-input"
                                                   name="productions[{{ $produit->id }}][quantity]"
                                                   required min="0" placeholder="Quantité produite"
                                                   value="0" {{ $produit->quantity_demande ?? 'disabled' }}
                                                   data-produit="{{ $produit->nom }}"
                                                   data-demande="{{ $produit->quantity_demande_prod ?? 0 }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($produit->quantity_demande)
                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary" onclick="showPreview()">
                                Prévisualiser la Production
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </section>
</div>

<!-- Modale de Prévisualisation -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Prévisualisation de la Production</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité Demandée</th>
                            <th>Quantité Produite</th>
                        </tr>
                    </thead>
                    <tbody id="previewTableBody">
                        <!-- Les données seront insérées ici par JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="submitForm()">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showPreview() {
        const tableBody = document.getElementById('previewTableBody');
        tableBody.innerHTML = ''; // Réinitialise le contenu

        // Parcourt les champs de production
        const inputs = document.querySelectorAll('.produit-input');
        inputs.forEach(input => {
            const produit = input.getAttribute('data-produit');
            const demande = input.getAttribute('data-demande');
            const valeur = input.value;

            if (valeur > 0) {
                const row = `
                    <tr>
                        <td>${produit}</td>
                        <td>${demande}</td>
                        <td>${valeur}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            }
        });

        // Affiche la modale
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    }

    function submitForm() {
        document.getElementById('productionForm').submit();
    }
</script>

@endsection
