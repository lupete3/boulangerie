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
           <!-- Formulaire de filtrage par date -->
           <form method="GET" action="{{ route('rapports.distributionAdmin') }}" id="filterForm" class="valider">
                <div class="form-group row">
                    <label for="date" class="col-sm-2 col-form-label">Date :</label>
                    <div class="col-sm-4">
                        <input type="date" name="date" id="date" value="{{ $date }}" class="form-control" onchange="document.getElementById('filterForm').submit();">
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <center>
                        <p style="font-weight:bold; font-family:Century Gothic; font-size:1.6em;">
                            Fiche Distribution du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                        </p>
                    </center>
                </div>
            </div>

            <div class="row spacer" style="margin-bottom:20px; ">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-bordered table-striped table-sm" style="font-family:Century Gothic; font-size:0.7em;">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité totale vers Sites</th>
                                <th>Quantité totale vers Partenaires</th>
                                <th>Détails des Sites</th>
                                <th>Détails des Partenaires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produits as $produit)
                                <tr>
                                    <td>{{ $produit->nom }}</td>

                                    <!-- Quantité totale vers Sites -->
                                    <td>
                                        @php
                                            $quantiteTotalSite = isset($distributionsSites[$produit->id]) ? $distributionsSites[$produit->id]->sum('quantity') : 0;
                                        @endphp
                                        {{ $quantiteTotalSite }}
                                    </td>

                                    <!-- Quantité totale vers Partenaires -->
                                    <td>
                                        @php
                                            $quantiteTotalPartenaire = isset($distributionsPartenaires[$produit->id]) ? $distributionsPartenaires[$produit->id]->sum('quantity') : 0;
                                        @endphp
                                        {{ $quantiteTotalPartenaire }}
                                    </td>

                                    <!-- Détails des Sites -->
                                    <td>

                                        @forelse ($distributionsSites[$produit->id] ?? [] as $distributionSite)
                                            <span>{{ $distributionSite->site->nom }} : <b>{{ $distributionSite->quantity }}</b> </span> <br>
                                        @empty
                                            <span>Aucune distribution</span>
                                        @endforelse

                                    </td>

                                    <!-- Détails des Partenaires -->
                                    <td>
                                        @forelse ($distributionsPartenaires[$produit->id] ?? [] as $distributionPartenaire)
                                            <span>{{ $distributionPartenaire->partenaire->nom }} : <b>{{ $distributionPartenaire->quantity }}</b> </span><br>
                                        @empty
                                            <span>Aucune distribution</span>
                                        @endforelse
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun produit trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
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

