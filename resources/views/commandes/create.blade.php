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
                <h1>Enregistrer une commande</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('commandes.index') }}">Commandes</a></div>
                    <div class="breadcrumb-item">Enregistrer une commande</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible" id="msg" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-header">
                                <h4>Enregistrer une nouvelle commande</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('commandes.index')}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-list-alt"></i> Liste commandes</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post" action="{{ route('commandes.preview') }}">
                                    @csrf
                                    <table class="table table-bordered table-striped table-sm" id="table">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Nombre de kg demandés</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($viewData['produits'] as $produit)
                                                <tr>
                                                    <td>{{ $produit->nom }}</td>
                                                    <td>
                                                        <input type="hidden" name="produits[{{ $produit->id }}][produit_id]" value="{{ $produit->id }}">
                                                        <input type="hidden" name="produits[{{ $produit->id }}][qte_par_kg]" value="{{ $produit->qte_par_kg }}">
                                                        <input type="number" step="0.01" min="0" name="produits[{{ $produit->id }}][nbre_kg]" value="0" class="form-control" placeholder="Quantité en kg">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Prévisualiser la commande</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
