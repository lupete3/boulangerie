@extends('layouts.backend')

<style>
    table tr {
        font-size: 11px;
    }
</style>

@section('content')
    <!-- Main Content -->
    <div class="main-content" >
        <section class="section" style="margin:-20px">
            {{-- <div class="section-header">
                <h1>Créer une Opération de Guichet</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                    <div class="breadcrumb-item active">Créer une Opération</div>
                </div>
            </div> --}}

            <div class="section-body" style="margin-top: -10px">
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

                        <div class="card-body row">
                            <div class="form-group col col-md-6">
                                <label for="site_id">Sélectionner le Site</label>
                                <select name="site_id" class="form-control form-control-sm">
                                    <option value="">-- Choisir un site --</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col col-md-6">
                                <label for="shift">Sélectionner le Shift</label>
                                <select name="shift" class="form-control form-control-sm">
                                    <option value="">-- Choisir un shift --</option>
                                    <option value="Jour" {{ old('shift') == 'Jour' ? 'selected' : '' }}>Jour</option>
                                    <option value="Soir" {{ old('shift') == 'Soir' ? 'selected' : '' }}>Soir</option>
                                </select>
                            </div>

                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm" >
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité Trouvée</th>
                                            <th>Quantité Reçue</th>
                                            <th>Quantité Restante</th>
                                            <th>Quantité Abîmée</th>
                                            <th>Quantité Consommée</th>
                                            <th>Montant Parti en Dette</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($produits as $produit)
                                            <tr>
                                                <td>{{ $produit->nom }}</td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_trouvee]" class="form-control form-control-sm" value="{{ old('operations.' . $produit->id . '.quantity_trouvee', 0) }}">
                                                    <input type="hidden" name="operations[{{ $produit->id }}][produit_id]" value="{{ $produit->id }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_recue]" class="form-control form-control-sm" value="{{ old('operations.' . $produit->id . '.quantity_recue', 0) }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_restante]" class="form-control form-control-sm" value="{{ old('operations.' . $produit->id . '.quantity_restante', 0) }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_abimee]" class="form-control form-control-sm" value="{{ old('operations.' . $produit->id . '.quantity_abimee', 0) }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_consomme]" class="form-control form-control-sm" value="{{ old('operations.' . $produit->id . '.quantity_consomme', 0) }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="operations[{{ $produit->id }}][quantity_dette]" class="form-control form-control-sm" value="{{ old('operations.' . $produit->id . '.quantity_dette', 0) }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Section pour le montant physique et le montant de change -->
                            <div class="form-group col col-md-6">
                                <label for="montant_physique">Montant Physique Déposé</label>
                                <input type="number" step="0.01" name="montant_physique" class="form-control form-control-sm" value="{{ old('montant_physique', 0) }}">
                            </div>
                            <div class="form-group col col-md-6">
                                <label for="montant_change">Montant de Change Resté</label>
                                <input type="number" step="0.01" name="montant_change" class="form-control form-control-sm" value="{{ old('montant_change', 0) }}">
                            </div>

                            <!-- Section Dépenses -->
                            <div id="expenses-section" class="col-md-12">
                                <label>Dépenses</label>
                                @if(old('depenses'))
                                    @foreach(old('depenses') as $index => $depense)
                                        <div class="expense-row form-row">
                                            <input type="number" name="depenses[{{ $index }}][montant]" placeholder="Montant" class="form-control form-control-sm col col-md-6" value="{{ $depense['montant'] }}">
                                            <input type="text" name="depenses[{{ $index }}][motif]" placeholder="Motif" class="form-control form-control-sm col col-md-6" value="{{ $depense['motif'] }}">
                                        </div><br>
                                    @endforeach
                                @else
                                    <div class="expense-row form-row">
                                        <input type="number" name="depenses[0][montant]" placeholder="Montant" class="form-control form-control-sm col col-md-6">
                                        <input type="text" name="depenses[0][motif]" placeholder="Motif" class="form-control form-control-sm col col-md-6">
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
                <input type="number" name="depenses[${expenseIndex}][montant]" placeholder="Montant" class="form-control form-control-sm col col-md-6" />
                <input type="text" name="depenses[${expenseIndex}][motif]" placeholder="Motif" class="form-control form-control-sm col col-md-6" />
            </div><br>`;
            document.getElementById('expenses-section').appendChild(newRow);
            expenseIndex++;
        }

        document.getElementById('operationForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Empêcher la soumission directe

        // Récupérer les données du formulaire
        const form = event.target;
        const site = form.site_id.options[form.site_id.selectedIndex].text;
        const shift = form.shift.options[form.shift.selectedIndex].text;
        const produits = [...form.querySelectorAll('tbody tr')].map(row => {
            const produit = row.cells[0].innerText;
            const quantityTrouvee = row.querySelector('input[name*="quantity_trouvee"]').value;
            const quantityRecue = row.querySelector('input[name*="quantity_recue"]').value;
            const quantityRestante = row.querySelector('input[name*="quantity_restante"]').value;
            const quantityAbimee = row.querySelector('input[name*="quantity_abimee"]').value;
            const quantityConsomme = row.querySelector('input[name*="quantity_consomme"]').value;
            const quantityDette = row.querySelector('input[name*="quantity_dette"]').value;
            return {
                produit,
                quantityTrouvee,
                quantityRecue,
                quantityRestante,
                quantityAbimee,
                quantityConsomme,
                quantityDette,
            };
        });
        const montantPhysique = form.montant_physique.value;
        const montantChange = form.montant_change.value;
        const depenses = [...form.querySelectorAll('#expenses-section .expense-row')].map(row => ({
            montant: row.querySelector('input[name*="[montant]"]').value,
            motif: row.querySelector('input[name*="[motif]"]').value,
        }));

        // Insérer les données dans la modale
        document.getElementById('recapSite').innerText = site;
        document.getElementById('recapShift').innerText = shift;

        const recapProduits = document.getElementById('recapProduits');
        recapProduits.innerHTML = produits.map(p => `
            <tr>
                <td>${p.produit}</td>
                <td>${p.quantityTrouvee}</td>
                <td>${p.quantityRecue}</td>
                <td>${p.quantityRestante}</td>
                <td>${p.quantityAbimee}</td>
                <td>${p.quantityConsomme}</td>
                <td>${p.quantityDette}</td>
            </tr>
        `).join('');

        document.getElementById('recapMontantPhysique').innerText = montantPhysique;
        document.getElementById('recapMontantChange').innerText = montantChange;

        const recapDepenses = document.getElementById('recapDepenses');
        recapDepenses.innerHTML = depenses.map(d => `
                <li><strong>${d.montant}:</strong> ${d.motif}</li>
            `).join('');

            // Ouvrir la modale
            $('#recapModal').modal('show');
        });

    </script>

    <div class="modal fade " id="recapModal" tabindex="-1" role="dialog" aria-labelledby="recapModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="recapModalLabel">Récapitulatif de l'Opération</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6><strong>Site :</strong> <span id="recapSite"></span></h6>
                    <h6><strong>Shift :</strong> <span id="recapShift"></span></h6>
                    <h6><strong>Produits :</strong></h6>
                    <div class="table-sm">
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité Trouvée</th>
                                    <th>Quantité Reçue</th>
                                    <th>Quantité Restante</th>
                                    <th>Quantité Abîmée</th>
                                    <th>Quantité Consommée</th>
                                    <th>Montant Parti en Dette</th>
                                </tr>
                            </thead>
                            <tbody id="recapProduits">
                                <!-- Les données des produits seront injectées ici -->
                            </tbody>
                        </table>
                    </div>
                    <h6><strong>Montant Physique :</strong> <span id="recapMontantPhysique"></span></h6>
                    <h6><strong>Montant de Change :</strong> <span id="recapMontantChange"></span></h6>
                    <h6><strong>Dépenses :</strong></h6>
                    <ul id="recapDepenses"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" id="confirmSubmitButton" class="btn btn-success">Confirmer</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Soumettre le formulaire après confirmation

        document.getElementById('confirmSubmitButton').addEventListener('click', function() {
            // Soumet le formulaire principal lorsque "Confirmer" est cliqué
            document.getElementById('operationForm').submit();
        });

    </script>

@endsection
