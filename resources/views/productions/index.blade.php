@extends('layouts.backend')

<style>
    table tr{
        font-size: 12px;
    }
</style>

@section('content')
<div class="main-content">
    <section class="section" style="margin:-20px">


        <div class="section-body">

            <div class="card">
                
                <div class="card-header">
                    <h4>Production du {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</h4>

                    <div class="card-header-action">
                        <a href="{{ route('productions.create')}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Ajouter </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Formulaire de filtrage par date -->
                    <form method="GET" action="{{ route('productions.index') }}" id="filterForm">
                        <div class="form-group row">
                            <label for="date" class="col-sm-2 col-form-label">Filtrer par Date :</label>
                            <div class="col-sm-4">
                                <input type="date" name="date" id="date" value="{{ $selectedDate }}" class="form-control" onchange="document.getElementById('filterForm').submit();">
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped table-sm" id="table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité Demandée (kg)</th>
                                <th>Quantité Demandée </th>
                                <th>Quantité Produite </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productions as $produit)
                            <tr>
                                <td>{{ $produit->nom }}</td>
                                <td>{{ $produit->quantity_kg_demande ?? 0 }} kg</td>
                                <td>{{ $produit->quantity_demande ?? 0 }}</td>
                                <td>{{ $produit->quantity_produced ?? 0 }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
