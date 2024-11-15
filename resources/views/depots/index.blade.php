@extends('layouts.backend')

<style>
    table tr {
        font-size: 12px;
    }

</style>

@section('content')
<div class="main-content">
    <section class="section" style="margin: -15px">
        {{-- <div class="section-header">
            <h1>Gestion des Dépôts</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a>
</div>
<div class="breadcrumb-item">Dépôts</div>
</div>
</div> --}}

<div class="section-body">
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

    <div class="card">
        <div class="card-header">
            <h4>Dépôts du {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</h4>

            <div class="card-header-action">
                <a href="{{ route('depots.create')}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Ajouter </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filtrage par date -->
            <form method="GET" action="{{ route('depots.index') }}" id="filterForm">
                <div class="form-group row">
                    <label for="date" class="col-sm-2 col-form-label">Filtrer par Date :</label>
                    <div class="col-sm-4">
                        <input type="date" name="date" id="date" value="{{ $selectedDate }}" class="form-control form-control-sm" onchange="document.getElementById('filterForm').submit();">
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-striped table-sm" id="table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité Reçue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($depots as $produit)
                    <tr>
                        <td>{{ $produit->nom }}</td>
                        <td>{{ $produit->quantity_produite ?? 0 }}</td>
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

