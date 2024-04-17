@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('achat-mp.index')}}">Achats matières premières</a></div>
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
                      <div class="card ">
                        <form method="post" action="{{ route('achat-mp.store')}}" enctype="multipart/form-data">
                            @csrf
                          <div class="card-header">
                            <h4>{{$viewData['title']}}</h4>
                            <div class="card-header-action">
                                <a href="{{ route('achat-mp.index')}}" class="btn btn-icon icon-left btn-info"><i class="fas fa-list-alt"></i> Afficher achats matières premières</a>
                            </div> 
                          </div>
                          <div class="card-body">
                            <div class="form-group">
                              <label>Choisir un fournisseur*</label>
                              <select name="fournisseur_id" class="form-control selectpicker" id="fournisseur_id" data-show-subtext="true" data-live-search="true" required>

                                @foreach ($viewData['fournisseurs'] as $fournisseur)

                                  <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>

                                @endforeach
                               
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Choisir une matière première*</label>
                              <select name="stock_maison_id" class="form-control selectpicker" id="stock_maison_id" data-show-subtext="true" data-live-search="true" required>

                                @foreach ($viewData['stockMaisons'] as $stockMaison)

                                  <option value="{{ $stockMaison->id }}">{{ $stockMaison->designation }}</option>

                                @endforeach
                               
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Quantité Entrée*</label>
                              <input type="number" class="form-control" name="quantite" value="{{ old('quantite') }}" placeholder="" required="">
                            </div>
                            <div class="form-group">
                              <label>Prix d'achat*</label>
                              <input type="text" class="form-control" name="prix" value="{{ old('prix') }}" placeholder="" required="">
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