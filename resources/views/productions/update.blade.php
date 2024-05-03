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
                  <div class="breadcrumb-item"><a href="{{ route('production.index')}}">Productions</a></div>
                  <div class="breadcrumb-item">{{ $viewData['title'] }}</div>
                </div>
            </div>

            <div class="section-body ">
            
              <div class="row">
                <div class="col-md-12">
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
                </div>
                <!-- Colonne des articles -->
                <div class="col-md-5">
                  <h4>Production</h4>
                  <!-- Ajoutez les articles de la catégorie sélectionnée -->
                  <div class="card">
                    <div class="card-body">
                      <form method="post" action="{{ route('production.update', $production->id)}}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                          
                          <select name="produit_finis_id" class="form-control selectpicker" id="produit_finis_id" data-live-search="true" required>
                            
                            @foreach ($viewData['produitsFinis'] as $produit)
                            
                              <option @selected(old('produit_finis_id', $production->stock_pf_id) == $produit->id) value="{{ $produit->id }}" >{{ $produit->designation }}</option>

                            @endforeach
                                    
                          </select>

                        </div>
                        <div class="form-group">
                          <label>Quantité Produite*</label>
                          <input type="text" class="form-control" name="quantite" value="{{ $production->quantite }}" placeholder="" required="">
                        </div>
                        <div class="form-group">
                          <label>Charge personnel*</label>
                          <input type="text" class="form-control" name="charge_personnel" value="{{ $production->charge_personnel }}" placeholder="" required="">
                        </div>
                        <div class="form-group">
                          <label>Autres Charges*</label>
                          <input type="text" class="form-control" name="autres_charges" value="{{ $production->autres_charges }}" placeholder="" required="">
                        </div>
                    
                        <div class="card-footer text-right">
                          <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Mettre à jour</button>
                        </div>
                      </form>
                    </div>
                  </div>
                  <!-- Ajoutez plus d'articles selon le même modèle -->
                  
                </div>

                <!-- Colonne des catégories -->
                <div class="col-md-7">
                  <h4>Liste des compositions</h4>
                  <div class="row">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Matière prémière</th>
                          <th>Quantité utilisée</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach($production->compositions as $item)
                            <tr>
                              <td>{{ $i++ }}</td>
                              <td>{{ $item->designation }}</td>
                              <td>{{ $item->quantite }}</td>
                               
                                <td>
                                  <form action="{{ route('production.removeFromCartEdit', $item->id ) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="article_id" value="{{ $item->id }}">
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Retirer</button>
                                  </form>
                                </td>
                              </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="row" style="margin-top: 50px">
                    <button  class="btn btn-success col-md-3 mt-2" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> Ajouter une composition</button>
                    
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
            <h5 class="modal-title" id="exampleModalLabel">Choisir une matière prémière</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" class="row" action="{{ route('production.addToCartEdit', $production->id) }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group col-12 col-md-12 col-lg-12">
                <label>Choisir une matière prémière</label>
                <select name="article_id" class="form-control selectpicker" id="article_id" data-show-subtext="true" data-live-search="true" required>

                  @foreach ($viewData['matieresPremieres'] as $article)

                    <option value="{{ $article->id }}">{{ $article->stockMaison->designation }} {{ $article->stockMaison->unite }}</option>

                  @endforeach
                          
                </select>
                
              </div> 
              <div class="form-group col-12 col-md-12 col-lg-12">
                <label for="">Quantité produite</label>
                <input type="text" name="quantite" class="form-control" required>
              </div>     
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary  valider">Ajouter</button>
          </div>
        </div>
      </form>
      </div>
    </div>

@endsection