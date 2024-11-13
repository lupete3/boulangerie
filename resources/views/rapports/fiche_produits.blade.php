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
                                <th>#</th>
                                <th>Nom du produit</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                                <th>Kg par sac</th>
                                <th>Quantité par sac</th>
                                <th>Quantité par kg</th>
                                <th>Date d'enregistrement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($viewData['categories'] as $index => $category) 

                                @foreach ($category->produits as $produit)

                                    <tr>

                                        @if ($loop->first)
                                            <td rowspan="{{ $category->produits->count() }}">{{ $index + 1 }}</td>
                                            <td rowspan="{{ $category->produits->count() }}"><b>{{ $category->name }}</b></td>
                                        @endif
                                    
                                        <td>{{ $produit->nom }}</td>
                                        <td>{{ number_format($produit->prix, 2) }} Fc</td>
                                        <td>{{ number_format($produit->kg_par_sac, 2) }}</td>
                                        <td>{{ number_format($produit->qte_par_sac, 2) }}</td>
                                        <td>{{ number_format($produit->qte_par_kg, 2) }}</td>
                                        <td>{{ $produit->created_at->format('d/m/Y') }}</td> </tr>
                                        
                                    </tr>

                                @endforeach

                            @endforeach
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

