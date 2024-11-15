@extends('layouts.backend')

<style>
    table tr {
        font-size: 12px;
    }
</style>

@section('content')
<div class="main-content">
    <section class="section" style="margin: -15px">
        <div class="section-body">
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ Session::get('success') }}
                </div>
            @endif

            <div class="card">
                <form method="POST" id="depotForm" action="{{ route('depots.store') }}">
                    @csrf
                    <div class="card-header">
                        <h4>Stockage dans le dépôt</h4>
                        <div class="card-header-action">
                            <a href="{{ route('depots.index')}}" class="btn btn-icon icon-left btn-primary">
                                <i class="fas fa-list-alt"></i> Afficher
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité Reçue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produits as $produit)
                                    <tr>
                                        <td>{{ $produit->nom }}</td>
                                        <td>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                name="quantities[{{ $produit->id }}]" 
                                                class="form-control form-control-sm quantity-input" 
                                                placeholder="Entrez la quantité reçue" 
                                                min="0"
                                                data-produit="{{ $produit->nom }}"
                                            >
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-primary" onclick="showPreview()">
                            <i class="fas fa-check"></i> Prévisualiser
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
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
            if (valeur) {
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
</script>

<!-- Modale de Prévisualisation -->

<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Prévisualisation des Données</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité Reçue</th>
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
    // Fonction pour soumettre le formulaire après confirmation
    function submitForm() {
        document.getElementById('depotForm').submit();
    }
</script>
@endsection
