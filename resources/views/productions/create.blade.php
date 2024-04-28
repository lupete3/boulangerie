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
                  <div class="breadcrumb-item"><a href="{{ route('production.index')}}">Liste des productions</a></div>
                  <div class="breadcrumb-item">{{ $viewData['title'] }}</div>
                </div>
            </div>

            <div class="section-body ">
            
              <div class="row">
                <!-- Colonne des articles -->
                <div class="col-md-5">
                  <h4>Production</h4>
                  <!-- Ajoutez les articles de la catégorie sélectionnée -->
                  <div class="card">
                    <div class="card-body">
                      <form method="post" action="{{ route('production.addToCart')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                          
                          <label>Choisir une matière première*</label>
                          <select name="article_id" class="form-control selectpicker" id="article_id" data-show-subtext="true" data-live-search="true" required>

                            @foreach ($viewData['matieresPremieres'] as $article)

                              <option value="{{ $article->id }}">{{ $article->stockMaison->designation }} disponible : {{ $article->solde }} {{ $article->stockMaison->unite }}</option>

                            @endforeach
                          
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Quantité Utilisée en ({{ $article->stockMaison->unite }})*</label>
                          <input type="text" class="form-control" name="quantite" value="{{ old('quantite') }}" placeholder="" required="">
                        </div>
                    
                        <div class="card-footer text-right">
                          <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Enregistrer</button>
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
                          <th>Matière première</th>
                          <th>Quantité utilisée en ({{ $article->stockMaison->unite }})</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach(session('cart', []) as $productId => $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                               
                                <td>
                                  <form action="{{ route('production.removeFromCart') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="article_id" value="{{ $productId }}">
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i></button>
                                  </form>
                                </td>
                              </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="row" style="margin-top: 50px">
                    <button  class="btn btn-success col-md-3 mt-2" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-credit-card"></i> Clôturer la production</button>
                    <form action="{{ route('production.clearCart') }}" class="col-md-3 mt-2" method="post">
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
            <form method="post" class="row" action="{{ route('production.store') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group col-12 col-md-12 col-lg-12">
                <select name="produit_finis_id" class="form-control selectpicker" id="produit_finis_id" data-live-search="true" required>
                  <option value="" selected disabled>Choisir un produit finis*</option>
                  @foreach ($viewData['produitsFinis'] as $produit)

                    <option data-tokens="{{ $produit->designation }}" value="{{ $produit->id }}">{{ $produit->designation }}</option>

                  @endforeach
                          
                </select>
                
              </div> 
              <div class="form-group col-12 col-md-12 col-lg-12">
                <label for="">Quantité produite*</label>
                <input type="text" name="quantite" class="form-control" required>
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