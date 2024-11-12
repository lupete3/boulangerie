@extends('layouts.backend')

<style>
    table tr {
        font-size: 11px;
    }
</style>

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tableau Synthèse des Produits</h1>
        </div>

        <form action="{{ route('syntheses.index') }}" method="GET" class="mb-4">
            <div class="form-row">
                <div class="col-md-3">
                    <label for="date_debut">Date de début :</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut', $date_debut) }}">
                </div>
                <div class="col-md-3">
                    <label for="date_fin">Date de fin :</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin', $date_fin) }}">
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Filtrer</button>
                </div>
            </div>
        </form>

        <div class="section-body">
            <div class="card table-responsive">
                <div class="p-2">
                    <h6>Synthèse des Produits par Catégorie</h6>
                </div>
                <table class="table table-bordered table-striped table-sm" id="table">
                    <thead>
                        <tr>
                            <th>Catégorie</th>
                            <th>Produit</th>
                            <th>Kg par Sac</th>
                            <th>Qté par Sac</th>
                            <th>Qté par KG</th>
                            <th>KG Demandé</th>
                            <th>Produit Demandé</th>
                            <th>Quantité Produite</th>
                            <th>Quantité Dépôt</th>
                            @foreach($sites as $site)
                                <th>Distribué {{ $site->nom }}</th>
                            @endforeach
                            @foreach($partenaires as $partenaire)
                                <th>Distribué {{ $partenaire->nom }}</th>
                            @endforeach
                            <th>Perte Production</th>
                            <th>Distribution</th>
                            <th>Perte Dépôt</th>
                            <th>Bon Produit</th>
                            <th>KG Reel</th>
                            <th>Prix</th>
                            <th>Perte Production Valorisé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            @foreach($category->produits as $produit)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $produit->nom }}</td>
                                    <td>{{ number_format($produit->kg_par_sac, 2) }}</td>
                                    <td>{{ number_format($produit->qte_par_sac, 2) }}</td>
                                    <td>{{ number_format($produit->qte_par_kg, 2) }}</td>
                                    <td>{{ number_format($produit->total_kg_demanded, 2) }}</td>
                                    <td>{{ number_format($produit->total_produits_demanded, 2) }}</td>
                                    <td>{{ number_format($produit->total_quantity_produite, 2) }}</td>
                                    <td>{{ number_format($produit->total_quantity_depot, 2) }}</td>
                                    @foreach($sites as $site)
                                        @php
                                            $siteQuantity = $produit->distributionSites->where('site_id', $site->id)->sum('quantity');
                                        @endphp
                                        <td>{{ number_format($siteQuantity, 2) }}</td>
                                    @endforeach

                                    @foreach($partenaires as $partenaire)
                                        @php
                                            $partenaireQuantity = $produit->distributionPartenaires->where('partenaire_id', $partenaire->id)->sum('quantity');
                                        @endphp
                                        <td>{{ number_format($partenaireQuantity, 2) }}</td>
                                    @endforeach
                                    <td>{{ number_format($produit->perte_production, 2) }}</td>
                                    <td>{{ number_format($produit->distribution, 2) }}</td>
                                    <td>{{ number_format($produit->perte_depot, 2) }}</td>
                                    <td>{{ number_format($produit->bon_produit, 2) }}</td>
                                    <td>{{ number_format($produit->kg_reel, 2) }}</td>
                                    <td>{{ number_format($produit->prix, 0) }} Fc</td>
                                    <td>{{ number_format($produit->perte_production_valorisee, 0) }} Fc</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">Total</td>
                            <td>{{ number_format($total_kg_demanded, 2) }}Kg</td>
                            <td>{{ number_format($total_kg_par_sac, 2) }}Sacs</td>
                            <td colspan="@php echo count($sites) + count($partenaires) + 6 @endphp"></td>
                            <td>{{ number_format($total_kg_reel, 2) }}Kg</td>
                            <td>{{ number_format($total_prix, 2) }}Sacs</td>
                            <td>{{ number_format($total_perte_production_valorisee, 0) }}Fc</td>
                        </tr>
                        @foreach($category_kg_reels as $category_name => $kg_reel_total)
                            <tr>
                                <td colspan="@php echo count($sites) + count($partenaires) + 13 @endphp">{{ $category_name }}</td>
                                <td>{{ number_format($kg_reel_total, 2) }}Kg</td>
                                <td>{{ number_format($kg_reel_total / 25, 2) }}Sacs</td>
                            </tr>
                        @endforeach
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
