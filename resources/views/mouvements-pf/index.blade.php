

@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('mouvement-stock-pf.index')}}">Sorties produits finis</a></div>
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
                                    <a href="{{ route('mouvement-stock-pf.create')}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Effectuer une sortie produit finis</a>
                                </div>   
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>                                 
                                        <tr>
                                            <th>#</th>
                                            <th>Point de vente</th>
                                            <th>Date Sortie</th>
                                            <th>Produit Finis</th>
                                            <th>Quantité Sortie</th>
                                            <th>Quantité Restante</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($viewData['sorties'] as $sortie) 
                                            <tr>

                                                <td> {{ $sortie->id }} </td>
                                                <td> {{ $sortie->site->nom }} </td>
                                                <td> {{ $sortie->created_at }} </td>
                                                <td> {{ $sortie->stockPf->designation }} </td>
                                                <td> {{ $sortie->quantite }} </td>
                                                <td> {{ $sortie->reste_stock_pf }} </td>
                                                
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle btn btn-primary" data-toggle="dropdown">Action</a>
                                                        
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                          
                                                            <a href="{{ route('mouvement-stock-pf.edit', $sortie->id)}}" class="dropdown-item has-icon"><i class="far fa-edit text-primary"></i> Modifier</a>
                                                          
                                                          <form action="{{ route('mouvement-stock-pf.destroy', $sortie->id)}}" method="post">
                                                            @csrf
                                                            <button  type="submit" class="dropdown-item has-icon"><i class="fas fa-trash text-danger"></i> Supprimer</button>
                                                          </form>
                                                          
                                                        </div>
                                                    </div>
                                                </td>
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

@endsection