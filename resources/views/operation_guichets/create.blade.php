@extends('layouts.backend')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Créer une Opération de Guichet</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                    <div class="breadcrumb-item active">Créer une Opération</div>
                </div>
            </div>

            <div class="section-body">
                <!-- Affichage des messages d'erreur -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <form id="operationForm" action="{{ route('operation_guichets.store') }}" method="POST" onsubmit="return confirmSubmission()">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4>Opérations de Guichet</h4>
                        </div>
                        <div class="card-body row">
                            <div class="form-group col-md-6">
                                <label for="site_id">Sélectionner le Site</label>
                                <select name="site_id" class="form-control">
                                    <option value="">-- Choisir un site --</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="shift">Sélectionner le Shift</label>
                                <select name="shift" class="form-control">
                                    <option value="">-- Choisir un shift --</option>
                                    <option value="Jour" {{ old('shift') == 'Jour' ? 'selected' : '' }}>Jour</option>
                                    <option value="Soir" {{ old('shift') == 'Soir' ? 'selected' : '' }}>Soir</option>
                                </select>
                            </div>

                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité Trouvée</th>
                                            <th>Quantité Reçue</th>
                                            <th>Quantité Restante</th>
                                            <th>Quantité Abîmée</th>
                                            <th>Quantité Consommée</th>
                                            <th>Quantité Partie en Dette</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($produits as $produit)
                                            <tr>
                                                <td>{{ $produit->nom }}</td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_trouvee]" class="form-control" value="{{ old('operations.' . $produit->id . '.quantity_trouvee', 0) }}">
                                                    <input type="hidden" name="operations[{{ $produit->id }}][produit_id]" value="{{ $produit->id }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_recue]" class="form-control" value="{{ old('operations.' . $produit->id . '.quantity_recue', 0) }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_restante]" class="form-control" value="{{ old('operations.' . $produit->id . '.quantity_restante', 0) }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_abimee]" class="form-control" value="{{ old('operations.' . $produit->id . '.quantity_abimee', 0) }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_consomme]" class="form-control" value="{{ old('operations.' . $produit->id . '.quantity_consomme', 0) }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_dette]" class="form-control" value="{{ old('operations.' . $produit->id . '.quantity_dette', 0) }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Section pour le montant physique et le montant de change -->
                            <div class="form-group col-md-6">
                                <label for="montant_physique">Montant Physique Déposé</label>
                                <input type="number" step="0.01" name="montant_physique" class="form-control" value="{{ old('montant_physique', 0) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="montant_change">Montant de Change Resté</label>
                                <input type="number" step="0.01" name="montant_change" class="form-control" value="{{ old('montant_change', 0) }}">
                            </div>

                            <!-- Section Dépenses -->
                            <div id="expenses-section" class="col-md-12">
                                <label>Dépenses</label>
                                @if(old('depenses'))
                                    @foreach(old('depenses') as $index => $depense)
                                        <div class="expense-row form-row">
                                            <input type="number" name="depenses[{{ $index }}][montant]" placeholder="Montant" class="form-control col-md-6" value="{{ $depense['montant'] }}">
                                            <input type="text" name="depenses[{{ $index }}][motif]" placeholder="Motif" class="form-control col-md-6" value="{{ $depense['motif'] }}">
                                        </div><br>
                                    @endforeach
                                @else
                                    <div class="expense-row form-row">
                                        <input type="number" name="depenses[0][montant]" placeholder="Montant" class="form-control col-md-6">
                                        <input type="text" name="depenses[0][motif]" placeholder="Motif" class="form-control col-md-6">
                                    </div><br>
                                @endif
                            </div><br>
                            <button type="button" onclick="addExpense()" class="btn btn-primary ml-2">+ Ajouter une dépense</button>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        let expenseIndex = {{ old('depenses') ? count(old('depenses')) : 1 }};

        function addExpense() {
            const newRow = document.createElement('div');
            newRow.classList.add('expense-row');
            newRow.innerHTML = `<div class="form-row">
                <input type="number" name="depenses[${expenseIndex}][montant]" placeholder="Montant" class="form-control col-md-6" />
                <input type="text" name="depenses[${expenseIndex}][motif]" placeholder="Motif" class="form-control col-md-6" />
            </div><br>`;
            document.getElementById('expenses-section').appendChild(newRow);
            expenseIndex++;
        }

        function confirmSubmission() {
            return confirm("Êtes-vous sûr de vouloir enregistrer ces informations ?");
        }
    </script>
@endsection
