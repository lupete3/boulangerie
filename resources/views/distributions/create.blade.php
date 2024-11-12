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
            <h1>Distribution des Produits</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                <div class="breadcrumb-item">Distribution des Produits</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Distribuer les Produits aux Sites et Partenaires</h4>
                </div>
                <form action="{{ route('distributions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th colspan="{{ count($sites) }}">Distribution par Sites</th>
                                    <th colspan="{{ count($partenaires) }}">Distribution par Partenaires</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    @foreach($sites as $site)
                                        <th>{{ $site->nom }}</th>
                                    @endforeach
                                    @foreach($partenaires as $partenaire)
                                        <th>{{ $partenaire->nom }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produits as $produit)
                                    <tr>
                                        <td>{{ $produit->nom }}</td>
                                        
                                        <!-- Champs de quantité pour les Sites -->
                                        @foreach($sites as $site)
                                            <td>
                                                <input type="hidden" name="produits[{{ $produit->id }}][site_ids][]" value="{{ $site->id }}">
                                                <input type="number" name="produits[{{ $produit->id }}][site_quantities][]" class="form-control" placeholder="Quantité" value="0" min="0" step="0.01">
                                            </td>
                                        @endforeach
                                        
                                        <!-- Champs de quantité pour les Partenaires -->
                                        @foreach($partenaires as $partenaire)
                                            <td>
                                                <input type="hidden" name="produits[{{ $produit->id }}][partenaire_ids][]" value="{{ $partenaire->id }}">
                                                <input type="number" name="produits[{{ $produit->id }}][partenaire_quantities][]" class="form-control" placeholder="Quantité" value="0" min="0" step="0.01">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Enregistrer la Distribution</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
