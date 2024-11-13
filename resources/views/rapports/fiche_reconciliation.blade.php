@extends('layouts.backend')


@section('content')

<style>
    th,
    tr,
    td {
        font-size: 10px
    }

</style>


<!-- Main Content -->
<div class="main-content">

    <section class="section">
        <div class="section-header valider">

        </div>

        <div class="section-body ">
            <div class="ro">
                <form action="{{ route('rapports.renconciliationAdmin') }}" class="valider" method="GET" class="mb-4">
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
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <center>
                        <p style="font-weight:bold; font-family:Century Gothic; font-size:1.6em;">
                            {{ $viewData['title'] }}
                        </p>
                    </center>
                </div>
            </div>

            <div class="row spacer" style="margin-bottom:20px; ">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-bordered table-striped table-sm" style="font-family:Century Gothic; font-size:0.7em;">
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
                                @if ($loop->first)
                                    <td rowspan="{{ $category->produits->count() }}"><b>{{ $category->name }}</b></td>
                                @endif

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
                                <td>{{ number_format($produit->kg_reel , 2) }}</td>
                                <td>{{ number_format($produit->prix, 0) }} Fc</td>
                                <td>{{ number_format($produit->perte_production_valorisee, 0) }} Fc</td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">Total</td>
                                <td><b>{{ number_format($total_kg_demanded, 2) }}Kg</b></td>
                                <td><b>{{ number_format($total_kg_par_sac, 2) }}Sacs</b></td>
                                <td colspan="@php echo count($sites) + count($partenaires) + 6 @endphp"></td>
                                <td><b>{{ number_format($total_kg_reel, 2) }}Kg</b></td>
                                <td><b>{{ number_format($total_prix, 2) }}Sacs</b></td>
                                <td><b>{{ number_format($total_perte_production_valorisee, 0) }}Fc</b></td>
                            </tr>
                            @foreach($category_kg_reels as $category_name => $kg_reel_total)
                            <tr>
                                <td colspan="@php echo count($sites) + count($partenaires) + 13 @endphp">{{ $category_name }}</td>
                                <td><b>{{ number_format($kg_reel_total, 2) }}Kg</b></td>
                                <td><b>{{ number_format($kg_reel_total / 25, 2) }}Sacs</b></td>
                            </tr>
                            @endforeach
                        </tfoot>
                    </table>
                </div>

            </div>

            <div class="row spacer" style="margin-bottom: 1.3em;">

                <table class="container-fluid">
                    <p style="font-family:Century Gothic; font-size:1em; margin-left:20px; ">
                        Date : {{ now()->format('d-m-Y') }}
                        <br>
                        <span>Heure : {{ now()->format('H:i') }}</span>
                        <br>

                    </p>

                </table>
            </div>

            <div class="row">
                <div class="col-md-3 offset-3">
                    <button type="button" class="btn btn-primary print pull-right valider"><span class="fa fa-print"></span> Imprimer</button>
                </div>
            </div>
        </div>
    </section>
</div>


@endsection

