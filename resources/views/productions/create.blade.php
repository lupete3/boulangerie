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
            <h1>Enregistrer une Production</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                <div class="breadcrumb-item">Productions</div>
                <div class="breadcrumb-item">Enregistrer</div>
            </div>
        </div>

        <div class="section-body">
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible" id="msg" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h6>{{ Session::get('error') }}</h6>
                </div> 
            @endif
            <form method="post" action="{{ route('productions.store') }}">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>Production de la journée</h4>

                        <div class="card-header-action">
                            <a href="{{ route('productions.index')}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-list-alt"></i> Liste productions</a>
                        </div>
                    </div>
                    <div class="card-body">
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
                                @foreach ($produits as $produit)
                                <tr>
                                    <td>{{ $produit->nom }}</td>
                                    <td>
                                        <input type="number" min="0" step="0.01" class="form-control"
                                               value="{{ $produit->quantity_demande ?? 0 }}"
                                               disabled>
                                        <input type="hidden" name="productions[{{ $produit->id }}][quantity_demande]"
                                               value="{{ $produit->quantity_demande ?? 0 }}">
                                    </td>
                                    <td><input type="number" min="0" step="0.01" class="form-control"
                                        value="{{ $produit->quantity_demande_prod ?? 0 }}"
                                        disabled></td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control"
                                               name="productions[{{ $produit->id }}][quantity]"
                                               required min="0" placeholder="Quantité produite"
                                               value="0" {{ $produit->quantity_demande ?? 'disabled' }}>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($produit->quantity_demande)
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Enregistrer la Production</button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
