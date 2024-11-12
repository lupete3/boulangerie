@extends('layouts.backend')

<style>
    table tr{
        font-size: 12px;
    }
</style>

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Commandes du {{ $selectedDate }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                    <div class="breadcrumb-item">Commandes</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @if($errors->any())
                            @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible" id="msg" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h6>
                                {{ $error }}
                                </h6>
                            </div>
                            @endforeach
                        @endif
                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible" id="msg" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h6>
                                {{ Session::get('success') }}
                            </h6>
                            </div>
                        @endif
                        <!-- Formulaire de sélection de date -->
                        <form method="GET" action="{{ route('commandes.index') }}" id="filterForm">
                            <div class="form-group row">
                                <label for="date" class="col-sm-2 col-form-label">Date :</label>
                                <div class="col-sm-4">
                                    <input type="date" name="date" id="date" value="{{ $selectedDate }}" class="form-control" onchange="document.getElementById('filterForm').submit();">
                                </div>
                            </div>
                        </form>

                        <!-- Tableau des commandes -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Liste des produits commandés pour le {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</h4>

                                <div class="card-header-action">
                                    <a href="{{ route('commandes.create')}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Ajouter </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-sm" id="table">
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
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

