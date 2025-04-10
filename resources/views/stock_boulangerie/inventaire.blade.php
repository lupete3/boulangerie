

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
                                </div>
                            </div>
                             
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>                                 
                                        <tr>
                                            <th>#</th>
                                            <th>Produit finis</th>
                                            <th>Quantité entrée</th>
                                            <th>Reste</th>
                                            <th>Quantité abimée</th>
                                            <th>Consommation maison</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @forelse ($viewData['produits'] as $produit) 
                                            
                                            <form method="POST" action="{{ route('stock-boulangerie.cloture', Auth::user()->site_id) }}">
                                                @csrf
                                                <tr>
                                                    <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                                                    <td> {{ $produit->id }} </td>
                                                    <td> {{ $produit->stockProduitFinis->designation }} </td>
                                                    <td><input type="text" class="form-control" name="entree" value="0"></td>
                                                    <td><input type="text" class="form-control" name="solde" value="0"></td>
                                                    <td><input type="text" class="form-control" name="avarie" value="0"></td>
                                                    <td><input type="text" class="form-control" name="consommation" value="0"></td>
                                                    <!-- Ajoute d'autres champs selon tes besoins -->
                                                    <td>
                                                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Cloturer</button>
                                                    </td>
                                                </tr>
                                            </form>
                                            <tr>
                                        @empty
                                            <tr>
                                                <td class="text-center text-danger" colspan="8" style="font-size: 18px">La validation de votre inventaire est en cours </td>
                                            </tr>        
                                        @endforelse
                                    </tbody>
                                    
                                </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>INVENTAIRE JOURNALIER </h4>
                                <div class="card-header-action">
                                    <button type="button" class="btn btn-icon icon-left btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-check"></i> FIN INVENTAIRE</button>
                                </div>
                            </div>
                             
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>                                 
                                        <tr>
                                            <th>#</th>
                                            <th>Produit finis</th>
                                            <th>Solde</th>
                                            <th>Quantité entrée</th>
                                            <th>Quantité sortie</th>
                                            <th>Quantité abimée</th>
                                            <th>Consommation maison</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totVente = 0;
                                            $totAvarie = 0;
                                            $totConsommation = 0;
                                        @endphp
                                        @foreach ($viewData['inventaires'] as $inventaire) 
                                            @php
                                                $totVente+=($inventaire->prix * $inventaire->qnte_sortie);
                                                $totAvarie+=($inventaire->prix * $inventaire->avarie);
                                                $totConsommation+=($inventaire->prix * $inventaire->consommation);
                                            @endphp
                                            <tr>
                                                <td>{{ $inventaire->id }} </td>
                                                <td>{{ $inventaire->stockProduitFinis->stockProduitFinis->designation }} </td>
                                                <td>{{ $inventaire->solde }} </td>
                                                <td>{{ $inventaire->qnte_entree }}</td>
                                                <td>{{ $inventaire->qnte_sortie }}</td>
                                                <td>{{ $inventaire->avarie }}</td>
                                                <td>{{ $inventaire->consommation }}</td>
                                            </tr>
                                                
                                        @endforeach
                                    </tbody>
                                    
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
              <h5 class="modal-title" id="exampleModalLabel">FIN INVENTAIRE</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post" class="row" action="{{ route('stock-boulangerie.cloture_boulangerie', $site->id)}}" enctype="multipart/form-data">
                @csrf
                
                <input type="hidden" name="vente" value="{{ $totVente }}">
                <input type="hidden" name="avarie" value="{{ $totAvarie }}">
                <input type="hidden" name="consommation" value="{{ $totConsommation }}">
                <input type="hidden" name="depense" value="{{ $viewData['depenses'] }}">
                
                <div class="form-group col-12 col-md-12 col-lg-12">
                    <label>Montant dette(Fc)</label>
                    <input type="text" class="form-control" name="dette" value="{{ old('dette') }}" required="">
                </div>
                  <div class="form-group col-12 col-md-12 col-lg-12">
                    <label>Montant change(Fc)</label>
                    <input type="text" class="form-control" name="change" value="{{ old('change') }}" required="">
                  </div>
                  <div class="form-group col-12 col-md-12 col-lg-12">
                    <label>Montant espèce donné(Fc)</label>
                    <input type="text" class="form-control" name="espece" value="{{ old('espece') }}" required="">
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