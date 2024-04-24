


@extends('layouts.backend')

@php
  use App\Models\Vente;
@endphp

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('ventes.index')}}">Liste des ventes</a></div>
                  <div class="breadcrumb-item">{{ $viewData['title'] }}</div>
                </div>
            </div>

            <div class="section-body ">
              <div class="row">
                <div class="col-md-12">
                  @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible" id="msg" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h6>
                                {{ Session::get('success') }}
                            </h6>
                            </div> 
                        @endif
                        @if(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible" id="msg" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h6>
                                {{ Session::get('error') }}
                            </h6>
                            </div> 
                        @endif
                </div>
              </div>
            
              <div class="row">
                <!-- Colonne des articles -->
                <div class="col-md-5">
                  <!-- Ajoutez les articles de la catégorie sélectionnée -->
                  <div class="card">
                    <div class="card-body">
                      <form method="post" action="{{ route('ventes.addToCart')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                          <label>Choisir un produit*</label>
                          <select name="produit_id" class="form-control selectpicker" id="produit_id" data-show-subtext="true" data-live-search="true" required>

                            @foreach ($viewData['produits'] as $produit)

                              <option value="{{ $produit->stock_pf_id }}">{{ $produit->stockProduitFinis->designation }} - Solde: {{ $produit->solde }}</option>

                            @endforeach
                           
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Quantité vendue*</label>
                          <input type="number" class="form-control" name="quantite" value="{{ old('quantite') }}" placeholder="" required="">
                        </div>
                    
                        <div class="card-footer text-right">
                          <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter au panier</button>
                        </div>
                      </form>
                    </div>
                  </div>
                  <!-- Ajoutez plus d'articles selon le même modèle -->
                  
                </div>

                <!-- Colonne des catégories -->
                <div class="col-md-7">
                  <div class="row">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Article</th>
                          <th>Pu</th>
                          <th>Quantité</th>
                          <th>Total</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                            $tot = 0;
                        @endphp
                        @foreach(session('cart', []) as $productId => $item)
                          @php
                              $tot = $tot + ($item['price'] * $item['quantity']);
                          @endphp
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['price'] }} Fc</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ $item['price'] * $item['quantity'] }} Fc</td>
                               
                                <td>
                                  <form action="{{ route('ventes.removeFromCart') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="article_id" value="{{ $productId }}">
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i></button>
                                  </form>
                                </td>
                              </tr>
                          @endforeach
                            <tr>
                              <td colspan="3"><b>Total</b></td>
                              <td><b>{{ $tot }} Fc</b></td>
                            </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="row" style="margin-top: 50px">
                    <button  class="btn btn-success col-md-3 mt-2" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-credit-card"></i> Clôturer la vente</button>
                    <form action="{{ route('ventes.clearCart') }}" class="col-md-3 mt-2" method="post">
                        @csrf
                        <button class="btn btn-danger col-md-12 "><i class="fas fa-trash"> </i> Supprimer tout</button>
                    </form>
                  </div>
                </div>
              </div>
                
            </div>
        </section>
    </div>

    <!-- Critere selon vehicule -->
    <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Choisir un produit finis</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" class="row" action="{{ route('ventes.store') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group col-12 col-md-12 col-lg-12">
                <select name="client_id" class="form-control selectpicker" id="client_id" data-live-search="true" required>
                  <option value="" selected disabled>Choisir un client*</option>
                  @foreach ($viewData['clients'] as $client)

                    <option data-tokens="{{ $client->nom }}" value="{{ $client->id }}">{{ $client->nom }}</option>

                  @endforeach
                          
                </select>
                
              </div> 
              <div class="form-group col-12 col-md-12 col-lg-12">
                <label for="">Montant Payé*</label>
                <input type="text" name="montant" class="form-control" required>
              </div>     
              
              <div class="form-group col-12 col-md-12 col-lg-12">
                <label>Observation</label>
                <textarea name="observation" id="observation" class="form-control" cols="30" rows="10">{{ old('observation') }}</textarea>
              </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary  valider">Clôturer</button>
          </div>
        </div>
      </form>
      </div>
    </div>

@endsection