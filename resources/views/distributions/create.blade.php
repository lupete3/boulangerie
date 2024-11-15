@extends('layouts.backend')

<style>
    table tr {
        font-size: 12px;
    }
</style>

@section('content')

<div class="main-content">
    <section class="section" style="margin:-15px">
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Distribuer les Produits aux Sites et Partenaires</h4>
                    <div class="card-header-action">
                        <a href="{{ route('distributions.index') }}" class="btn btn-icon icon-left btn-success">
                            <i class="fas fa-list-alt"></i> Afficher 
                        </a>
                    </div>
                </div>
                <form id="distributionForm" action="{{ route('distributions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th colspan="{{ count($sites) }}">Distribution par Sites</th>
                                        <th colspan="{{ count($partenaires) }}">Distribution par Partenaires</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        @foreach($sites as $site)
                                            <th>{{ $site->nom }}</th>
                                        @endforeach
                                        @foreach($partenaires as $partenaire)
                                            <th>{{ $partenaire->nom }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produits as $produit)
                                        <tr>
                                            <td>{{ $produit->nom }}</td>
                                            
                                            <!-- Champs de quantité pour les Sites -->
                                            @foreach($sites as $site)
                                                <td>
                                                    <input type="hidden" name="produits[{{ $produit->id }}][site_ids][]" value="{{ $site->id }}">
                                                    <input type="number" 
                                                        name="produits[{{ $produit->id }}][site_quantities][]" 
                                                        class="form-control form-control-sm site-input" 
                                                        placeholder="Quantité" 
                                                        value="0" 
                                                        min="0" 
                                                        step="0.01"
                                                        data-produit="{{ $produit->nom }}" 
                                                        data-destination="{{ $site->nom }}">
                                                </td>
                                            @endforeach
                                            
                                            <!-- Champs de quantité pour les Partenaires -->
                                            @foreach($partenaires as $partenaire)
                                                <td>
                                                    <input type="hidden" name="produits[{{ $produit->id }}][partenaire_ids][]" value="{{ $partenaire->id }}">
                                                    <input type="number" 
                                                        name="produits[{{ $produit->id }}][partenaire_quantities][]" 
                                                        class="form-control form-control-sm partenaire-input" 
                                                        placeholder="Quantité" 
                                                        value="0" 
                                                        min="0" 
                                                        step="0.01"
                                                        data-produit="{{ $produit->nom }}" 
                                                        data-destination="{{ $partenaire->nom }}">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-primary" onclick="showPreview()">
                            Prévisualiser la Distribution
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Modale de Prévisualisation -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Prévisualisation de la Distribution</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Destination</th>
                            <th>Quantité</th>
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

        // Parcourt les champs pour les sites
        const siteInputs = document.querySelectorAll('.site-input');
        siteInputs.forEach(input => {
            const produit = input.getAttribute('data-produit');
            const destination = input.getAttribute('data-destination');
            const valeur = input.value;

            if (valeur > 0) {
                const row = `
                    <tr>
                        <td>${produit}</td>
                        <td>${destination}</td>
                        <td>${valeur}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            }
        });

        // Parcourt les champs pour les partenaires
        const partenaireInputs = document.querySelectorAll('.partenaire-input');
        partenaireInputs.forEach(input => {
            const produit = input.getAttribute('data-produit');
            const destination = input.getAttribute('data-destination');
            const valeur = input.value;

            if (valeur > 0) {
                const row = `
                    <tr>
                        <td>${produit}</td>
                        <td>${destination}</td>
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
        document.getElementById('distributionForm').submit();
    }
</script>

@endsection
