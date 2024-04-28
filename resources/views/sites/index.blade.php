

@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('sites.index')}}">Points de vente</a></div>
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
                                    <a href="{{ route('sites.create')}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Ajouter point de vente</a>
                                </div>   
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($viewData['sites'] as $site)
                                        <div class="col-12 col-md-6 col-lg-6">
                                            <div class="pricing pricing-highlight">
                                            <div class="pricing-title">
                                                POINT DE VENTE
                                            </div>
                                            <div class="pricing-padding">
                                                <div class="pricing-price">
                                                <div>{{ $site->nom }}</div>
                                                </div>
                                                <div class="pricing-details">
                                                    
                                                    <div class="pricing-item">
                                                        <div class="pricing-item-icon"><i class="far fa-edit"></i></div>
                                                        <div class="pricing-item-label"><a href="{{ route('sites.edit', $site->id)}}"> Modifier le point de vente</a></div>
                                                    </div>
                                                    
                                                    <div class="pricing-item">
                                                        <div class="pricing-item-icon"><i class="fas fa-list-alt"></i></div>
                                                        <div class="pricing-item-label"><a href="{{ route('stock-boulangerie.index', $site->id)}}"> Liste des produits disponibles</a></div>
                                                    </div>
                                                    
                                                    <div class="pricing-item">
                                                        <div class="pricing-item-icon"><i class="fas fa-arrow-down"></i></div>
                                                        <div class="pricing-item-label"><a href="{{ route('mouvement-stock-pf-boulangerie.index', $site->id)}}">Liste des entrées produits</a></div>
                                                    </div>
                                                    
                                                    <div class="pricing-item">
                                                        <div class="pricing-item-icon"><i class="fas fa-arrow-up"></i></div>
                                                        <div class="pricing-item-label"><a href="{{ route('ventes.index', $site->id)}}"> Liste des ventes produits</a></div>
                                                    </div>
                                                    
                                                    <div class="pricing-item">
                                                        <div class="pricing-item-icon"><i class="fas fa-credit-card"></i></div>
                                                        <div class="pricing-item-label"><a href="{{ route('paiements.detteClients', $site->id)}}"> Liste de dettes clients</a></div>
                                                    </div>
                                                    
                                                    <div class="pricing-item">
                                                        <div class="pricing-item-icon"><i class="far fa-credit-card"></i></div>
                                                        <div class="pricing-item-label"><a href="{{ route('paiements.index', $site->id)}}"> Liste de paiements lients</a></div>
                                                    </div>
                                                    


                                                </div>
                                            </div>
                                            <div class="pricing-cta bg-danger">
                                                <a href="{{ route('sites.destroy', $site->id)}}"><i class="fas fa-trash"> </i> Supprimer le point de vente</a>
                                            </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {{-- </div>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>                                 
                                            <tr>
                                                <th>#</th>
                                                <th>Nom point de vente</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $id = 1;
                                            @endphp
                                            @foreach ($viewData['sites'] as $site) 
                                                <tr>
                                                    <td> {{ $id++ }} </td>
                                                    <td> {{ $site->nom }} </td>
                                                    
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-danger">Action sur le point de vente</button>
                                                            <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                                                              <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <div class="dropdown-menu">

                                                                <a href="{{ route('sites.edit', $site->id)}}" class="dropdown-item has-icon"><i class="far fa-edit text-primary"></i> Modifier</a>
                                                                <a href="{{ route('stock-boulangerie.index', $site->id)}}" class="dropdown-item has-icon"><i class="far fa-edit text-primary"></i> Liste des produits</a>
                                                                <a href="{{ route('mouvement-stock-pf-boulangerie.index', $site->id)}}" class="dropdown-item has-icon"><i class="far fa-edit text-primary"></i> Entrées produits</a>
                                                                <a href="{{ route('ventes.index', $site->id)}}" class="dropdown-item has-icon"><i class="far fa-edit text-primary"></i> Ventes produits</a>
                                                                <a href="{{ route('paiements.detteClients', $site->id)}}" class="dropdown-item has-icon"><i class="far fa-edit text-primary"></i> Dettes clients</a>
                                                                <a href="{{ route('paiements.index', $site->id)}}" class="dropdown-item has-icon"><i class="far fa-edit text-primary"></i> Paiements clients</a>
                                                                <a href="{{ route('sites.destroy', $site->id)}}" class="dropdown-item has-icon"><i class="fas fa-trash text-dange"></i> Supprimer</a>
                                                                                                                        
                                                            </div>
                                                          </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection