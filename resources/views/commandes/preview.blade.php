@extends('layouts.backend')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Récapitulatif de la commande</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Détails de la commande</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-sm" id="table">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Nombre de kg demandés</th>
                                            <th>Nombre des produits attendu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($produits as $produit)
                                            @php
                                                $quantite = $produitsCommandes[$produit->id]['nbre_kg'];
                                                $totalProduit = $quantite * $produit->qte_par_kg;
                                            @endphp
                                            <tr>
                                                <td>{{ $produit->nom }}</td>
                                                <td>{{ $quantite }} kg</td>
                                                <td>{{ $totalProduit }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-right">
                                <form method="post" action="{{ route('commandes.store') }}" style="display: inline;">
                                    @csrf
                                    @foreach($produitsCommandes as $produitId => $data)
                                        <input type="hidden" name="produits[{{ $produitId }}][produit_id]" value="{{ $data['produit_id'] }}">
                                        <input type="hidden" name="produits[{{ $produitId }}][nbre_kg]" value="{{ $data['nbre_kg'] }}">
                                        <input type="hidden" name="produits[{{ $produitId }}][qte_par_kg]" value="{{ $data['qte_par_kg'] }}">
                                    @endforeach
                                    <button type="submit" class="btn btn-success">Confirmer la commande</button>
                                </form>
                                <a href="{{ route('commandes.create') }}" class="btn btn-danger">Annuler</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
