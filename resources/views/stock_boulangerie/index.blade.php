

@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('stock-boulangerie.index',$site->id)}}">Liste des produits</a></div>
                  <div class="breadcrumb-item">{{ $viewData['title'] }}</div>
                </div>
            </div>

            <div class="section-body ">
            
                <div class="row">
                    <div class="col-12">
                        @if(Session::has('error'))
                                <div class="alert alert-danger alert-dismissible" id="msg" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h6>
                                        {{ Session::get('error') }}
                                    </h6>
                                </div>
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
                                <h4>{{ $viewData['title'] }} </h4>
                                <div class="card-header-action">
                                    <button type="button" class="btn btn-icon icon-left btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> AJouter produit</button>
                                </div>
                            </div>
                             
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>                                 
                                        <tr>
                                            <th>#</th>
                                            <th>Produit finis</th>
                                            <th>Prix de vente</th>
                                            <th>Solde</th>
                                            <th>Valeur du stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $tot = 0;
                                        @endphp
                                        @foreach ($viewData['produits'] as $produit) 
                                            @php
                                                $tot+=($produit->stockProduitFinis->prix * $produit->solde)
                                            @endphp
                                            <tr>

                                                <td> {{ $produit->id }} </td>
                                                <td> {{ $produit->stockProduitFinis->designation }} </td>
                                                <td> {{ $produit->stockProduitFinis->prix }} Fc</td>
                                                <td> {{ $produit->solde }} </td>
                                                <td> {{ $produit->stockProduitFinis->prix * $produit->solde }} Fc</td>
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tr>
                                        <td colspan="4"><b>Total Valeur en stock</b></td>
                                        <td colspan=""><b>{{ $tot }} Fc</b></td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

     <!-- Critere selon date -->
     <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Ajouter un produit</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post" class="row" action="{{ route('stock-boulangerie.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group col-12 col-md-12 col-lg-12">
                    <input type="hidden" name="site_id" value="{{ $site->id }}">
                    <label>Choisir un produit finis*</label>
                    <select name="produit_finis_id" class="form-control selectpicker" id="produit_finis_id" data-show-subtext="true" data-live-search="true" required>

                      @foreach ($viewData['produits_finis'] as $produit)

                        <option value="{{ $produit->id }}">{{ $produit->designation }} </option>

                      @endforeach
                     
                    </select>
                </div>
                <div class="form-group col-12 col-md-12 col-lg-12">
                  <label>Stock actuel</label>
                  <input type="text" class="form-control" name="solde" value="{{ old('solde') }}" required="">
                </div>
             
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
              <button type="submit" class="btn btn-primary  valider">Ajouter</button>
            </div>
          </div>
        </form>
        </div>
      </div>

@endsection