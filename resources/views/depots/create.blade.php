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
            <h1>Enregistrement des Quantités Reçues</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                <div class="breadcrumb-item"><a href="{{ route('depots.index') }}">Dépôts</a></div>
                <div class="breadcrumb-item">Enregistrer les quantités reçues</div>
            </div>
        </div>

        <div class="section-body">
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ Session::get('success') }}
                </div>
            @endif

            <div class="card">
                <form method="POST" action="{{ route('depots.store') }}">
                    @csrf
                    <div class="card-header">
                        <h4>Quantités stockés</h4>
                        <div class="card-header-action">
                            <a href="{{ route('depots.index')}}" class="btn btn-icon icon-left btn-primary"><i class="fas fa-list-alt"></i> Afficher</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité Reçue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produits as $produit)
                                    <tr>
                                        <td>{{ $produit->nom }}</td>
                                        <td>
                                            <input type="number" step="0.01" name="quantities[{{ $produit->id }}]" class="form-control" placeholder="Entrez la quantité reçue" min="0">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
