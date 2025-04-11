@extends('layouts.backend')

@section('content')

<!-- Main Content -->
<div class="main-content">
    
    <section class="section">
        <div class="section-header">
            <h1>{{ $viewData['title'] }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                <div class="breadcrumb-item"><a href="{{ route('caisses.index') }}">Caisse</a></div>
                <div class="breadcrumb-item">{{ $viewData['title'] }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 align-center">

                    @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible" id="msg" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h6>{{ $error }}</h6>
                            </div>
                        @endforeach
                    @endif

                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible" id="msg" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h6>{{ Session::get('success') }}</h6>
                        </div> 
                    @endif

                    <div class="card">
                        <form method="POST" action="{{ route('caisses.update', $caisse->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="card-header">
                                <h4>{{ $viewData['title'] }}</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('caisses.index') }}" class="btn btn-icon icon-left btn-info">
                                        <i class="fas fa-list-alt"></i> Liste des opérations
                                    </a>
                                </div> 
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Type d'opération</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($caisse->type_operation) }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label>Montant*</label>
                                    <input type="number" step="0.01" class="form-control" name="montant" value="{{ $caisse->montant }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Motif</label>
                                    <input type="text" class="form-control" name="motif" value="{{ $caisse->motif }}">
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check"></i> Mettre à jour
                                </button>
                            </div>

                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
