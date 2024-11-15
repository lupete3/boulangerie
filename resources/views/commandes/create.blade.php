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
                <div class="row">
                    <div class="col-12">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible" id="msg" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-header">
                                <h4>Enregistrer une nouvelle commande</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('commandes.index') }}" class="btn btn-icon icon-left btn-success">
                                        <i class="fas fa-list-alt"></i> Liste commandes
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post" id="commandeForm" action="{{ route('commandes.store') }}">
                                    @csrf
                                    <table class="table table-bordered table-striped table-sm" id="table">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Nombre de kg demandés</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($viewData['produits'] as $produit)
                                                <tr>
                                                    <td>{{ $produit->nom }}</td>
                                                    <td>
                                                        <input type="hidden" name="produits[{{ $produit->id }}][produit_id]" value="{{ $produit->id }}">
                                                        <input type="hidden" name="produits[{{ $produit->id }}][qte_par_kg]" value="{{ $produit->qte_par_kg }}">
                                                        <input 
                                                            type="number" 
                                                            step="0.01" 
                                                            min="0" 
                                                            name="produits[{{ $produit->id }}][nbre_kg]" 
                                                            value="0" 
                                                            class="form-control form-control-sm quantity-input" 
                                                            placeholder="Quantité en kg" 
                                                            data-produit="{{ $produit->nom }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="form-group text-right">
                                        <button type="button" class="btn btn-primary" onclick="showPreview()">
                                            <i class="fas fa-check"></i> Prévisualiser la commande
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modale de Prévisualisation -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Prévisualisation de la commande</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité en kg</th>
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
        // Fonction pour afficher la prévisualisation
        function showPreview() {
            const tableBody = document.getElementById('previewTableBody');
            tableBody.innerHTML = ''; // Réinitialise le contenu

            // Parcourt tous les champs de quantité
            const inputs = document.querySelectorAll('.quantity-input');
            inputs.forEach(input => {
                const produit = input.getAttribute('data-produit');
                const valeur = input.value;

                // Ajoute une ligne si une quantité est renseignée
                if (valeur && valeur > 0) {
                    const row = `
                        <tr>
                            <td>${produit}</td>
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

        // Fonction pour soumettre le formulaire après confirmation
        function submitForm() {
            document.getElementById('commandeForm').submit();
        }
    </script>
@endsection
