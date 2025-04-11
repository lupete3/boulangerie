@extends('layouts.print')

@section('content')

<style>
    th, tr, td {
        font-size: 16px;
    }
    .data-box {
        background-color: #f4f6f9;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    .data-box h4 {
        font-weight: bold;
        color: #2c3e50;
    }
</style>

<div class="container-fluid pt-4">
    <section class="section">
        <div class="section-body">

            <!-- Titre -->
            <div class="text-center mb-4">
                <h3 style="font-family: Century Gothic;">{{ $viewData['title'] }}</h3>
            </div>

            <!-- Formulaire de filtres -->

            <form action="{{ route('rapports.synthese') }}" method="GET" class="row mb-4 valider">
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

            <!-- Résumé synthétique -->
            <div class="row">
                @foreach([
                    'Coût Achat Matières Premières' => $viewData['valeurTotaleEntrees'],
                    'Valeur Stock Matière Première Dépôt' => $viewData['stockMpMaison'],
                    'Valeur Stock Matière Première Usine' => $viewData['valeurTotaleUsine'],
                    'Valeur Stock Produits Finis' => $viewData['stockPf'],
                    'Valeur Stock Points de vente' => $viewData['valeurTotalePointVente'],
                    'Valeur de production' => $viewData['totalValeurProduction'],
                    'Coût de production' => $viewData['totalCoutProduction'],
                    'Bénéfice Brut' => $viewData['totalBenefice']
                ] as $label => $value)
                    <div class="col-md-4">
                        <div class="data-box">
                            <p>{{ $label }}</p>
                            <h4>{{ number_format($value, 2, '.', ' ') }} Fc</h4>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Date & heure -->
            <div class="text-center my-4">
                <p>Date : {{ date('d-m-Y') }}</p>
                <p>Heure : {{ date('H:i') }}</p>
            </div>

            <!-- Boutons -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-primary print mr-2 valider">
                    <span class="fa fa-print"></span> Imprimer
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary valider">
                    <span class="fa fa-arrow-left"></span> Retour
                </a>
            </div>

        </div>
    </section>
</div>

<!-- Script pour gestion dynamique des filtres -->
<script>
    function toggleFiltres() {
        const filtre = document.getElementById('filtre').value;
        document.getElementById('date_unique_div').style.display = (filtre === 'date') ? 'block' : 'none';
        document.getElementById('periode_div').style.display = (filtre === 'periode') ? 'block' : 'none';

        if (filtre === 'jour' || filtre === 'semaine') {
            document.forms[0].submit();
        }
    }

    function validateForm() {
        const filtre = document.getElementById('filtre').value;
        if (filtre === 'date' && !document.querySelector('[name="date_unique"]').value) return false;
        if (filtre === 'periode') {
            const debut = document.querySelector('[name="date_debut"]').value;
            const fin = document.querySelector('[name="date_fin"]').value;
            if (!debut || !fin) return false;
        }
        return true;
    }
</script>

@endsection
