@extends('layouts.backend')

@section('content')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ $viewData['title'] }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de bord</a></div>
                <div class="breadcrumb-item"><a href="{{ route('caisses.index') }}">Opérations de caisse</a></div>
                <div class="breadcrumb-item">{{ $viewData['title'] }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

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
                        <form method="post" action="{{ route('caisses.store') }}">
                            @csrf
                            <div class="card-header">
                                <h4>{{ $viewData['title'] }}</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('caisses.index') }}" class="btn btn-icon icon-left btn-info">
                                        <i class="fas fa-list-alt"></i> Afficher les opérations
                                    </a>
                                </div> 
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Type d’opération *</label>
                                    <select name="type_operation" class="form-control" required>
                                        <option value="">-- Choisir --</option>
                                        <option value="entree" {{ old('type_operation') == 'entree' ? 'selected' : '' }}>Entrée</option>
                                        <option value="sortie" {{ old('type_operation') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Montant *</label>
                                    <input type="number" class="form-control" name="montant" value="{{ old('montant') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Motif *</label>
                                    <input type="text" class="form-control" name="motif" value="{{ old('motif') }}" required>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check"></i> Enregistrer
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