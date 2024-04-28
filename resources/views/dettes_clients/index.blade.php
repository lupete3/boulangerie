

@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('ventes.index',$site->id)}}">Liste des ventes</a></div>
                  <div class="breadcrumb-item">{{ $viewData['title'] }}</div>
                </div>
            </div>

            <div class="section-body ">
            
                <div class="row">
                    <div class="col-12">
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
                                <h4>{{ $viewData['title'] }} </h4>
                                <div class="card-header-action">
                                    <a href="{{ route('ventes.create',$site->id)}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i>Ajouter vente</a>
                                </div>   
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>                                 
                                        <tr>
                                            <th>#</th>
                                            <th>Action</th>
                                            <th>Date Vente</th>
                                            <th>Client</th>
                                            <th>Total à payer</th>
                                            <th>Total payé</th>
                                            <th>Dette</th>
                                            <th>Produit</th>
                                            <th>Quantite Vendue</th>
                                            <th>Prix Vente</th>
                                            <th>Prix Total</th>
                                            <th>Observation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $tot = 0;
                                            $totPaye = 0;
                                            $totReste = 0;
                                            $id = 1;
                                        @endphp

                                        @foreach ($viewData['commandes'] as $commande)
                                            @php
                                                $tot = $tot + $commande->montant;
                                                $totPaye = $totPaye + $commande->paye;
                                                $totReste = $totReste + $commande->reste;
                                            @endphp
                                            @foreach ($commande->ventes as $vente)
                                                
                                                <tr>
                                                    @if ($loop->first)
                                                        <td rowspan="{{ $commande->ventes->count() }}">{{ $id++ }}</td>
                                                        <td rowspan="{{ $commande->ventes->count() }}">
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle btn btn-primary" data-toggle="dropdown">Action</a>
                                                                
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                  
                                                                    <a href="{{ route('paiements.create', $commande->id)}}" class="dropdown-item has-icon"><i class="fas fa-check text-primary"></i> Payer</a>
                                                                                                                                    
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td rowspan="{{ $commande->ventes->count() }}">{{ $commande->created_at }}</td>
                                                        <td rowspan="{{ $commande->ventes->count() }}">{{ $commande->client->nom }}</td>
                                                        <td rowspan="{{ $commande->ventes->count() }}">{{ $commande->montant }} Fc</td>
                                                        <td rowspan="{{ $commande->ventes->count() }}">{{ $commande->paye }} Fc</td>
                                                        <td class="@if ($commande->reste > 0) text-danger @else @endif" rowspan="{{ $commande->ventes->count() }}">{{ $commande->reste }} Fc</td>
                                                    @endif
                                                    <td> {{ $vente->designation }} </td>
                                                    <td> {{ $vente->quantite }} </td>
                                                    <td> {{ $vente->prix }} Fc </td>
                                                    <td> {{ $vente->quantite * $vente->prix }} Fc </td>
                                                    @if ($loop->first)
                                                        <td rowspan="{{ $commande->ventes->count() }}">{{ $commande->observation }}</td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                        @endforeach

                                    </tbody>
                                    <tr>
                                        <td colspan="4"><b>Total</b></td>
                                        <td><b>{{ $tot }} Fc</b></td>
                                        <td><b>{{ $totPaye }} Fc</b></td>
                                        <td><b>{{ $totReste }} Fc</b></td>
                                        <td colspan="5"></td>
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