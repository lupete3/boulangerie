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
           <form method="GET" action="{{ route('rapports.commandeAdmin') }}" id="filterForm">
                <div class="form-group row">
                    <label for="date" class="col-sm-2 col-form-label">Date :</label>
                    <div class="col-sm-4">
                        <input type="date" name="date" id="date" value="{{ $selectedDate }}" class="form-control" onchange="document.getElementById('filterForm').submit();">
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <center>
                        <p style="font-weight:bold; font-family:Century Gothic; font-size:1.6em;">
                            Fiche des produits commandés pour le {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
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
                                <th>Quantité totale commandée (kg)</th>
                                <th>Quantité totale entendu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($commandes as $commande)
                                <tr>
                                    <td>{{ $commande->produit->nom }}</td>
                                    <td>{{ number_format($commande->total_kg, 2) }} kg</td>
                                    <td>{{ number_format(($commande->total_kg * $commande->produit->qte_par_kg), 2) }} </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Aucune commande pour aujourd'hui.</td>
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

