@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">

        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de bord</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('produits.index')}}">Produits</a></div>
                    <div class="breadcrumb-item">{{ $viewData['title'] }}</div>
                </div>
            </div>

            <div class="section-body ">

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

                        <div class="card ">
                            <form method="post" action="{{ route('produits.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-header">
                                    <h4>{{ $viewData['title'] }}</h4>
                                    <div class="card-header-action">
                                        <a href="{{ route('produits.index') }}" class="btn btn-icon icon-left btn-info">
                                            <i class="fas fa-list-alt"></i> Afficher les Produits
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <!-- Sélection de la catégorie -->
                                    <div class="form-group">
                                        <label>Catégorie*</label>
                                        <select name="category_id" class="form-control form-control-sm select2" id="role" data-show-subtext="true" data-live-search="true" required>
                                            <option value="">Sélectionnez une catégorie</option>
                                            @foreach($viewData['categories'] as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Nom du produit -->
                                    <div class="form-group">
                                        <label>Nom du produit*</label>
                                        <input type="text" class="form-control form-control-sm" name="nom" value="{{ old('nom') }}" placeholder="Nom du produit" required>
                                    </div>

                                    <!-- Prix -->
                                    <div class="form-group">
                                        <label>Prix*</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm" name="prix" value="{{ old('prix') }}" placeholder="Prix du produit" required>
                                    </div>

                                    <!-- Kg par sac -->
                                    <div class="form-group">
                                        <label>Kg par sac*</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm" name="kg_par_sac" value="{{ old('kg_par_sac', 25) }}" placeholder="Kg par sac" required>
                                    </div>

                                    <!-- Quantité par sac -->
                                    <div class="form-group">
                                        <label>Quantité par sac </label>
                                        <input type="number" step="0.01" class="form-control form-control-sm" name="qte_par_sac" value="{{ old('qte_par_sac', 0) }}" placeholder="Quantité par sac" required>
                                    </div>
                                </div>

                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
