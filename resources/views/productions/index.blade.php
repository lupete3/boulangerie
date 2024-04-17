

@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('production.index')}}">Liste des productions</a></div>
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
                                    <a href="{{ route('production.create')}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i>Ajouter production</a>
                                </div>   
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>                                 
                                            <tr>
                                                <th>#</th>
                                                <th>Date production</th>
                                                <th>Produit</th>
                                                <th>Quantité produite</th>
                                                <th>Prix de vente</th>
                                                <th>Valeur de production</th>
                                                <th>Composition</th>
                                                <th>Coût de production</th>
                                                <th>Bénéfice (Valeur Production - Coût Production)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @php
                                                $total = 0;
                                                $totProd = 0;
                                            @endphp

                                            @foreach ($viewData['productions'] as $production) 
                                                <tr>
                                                    @php
                                                        $total += $production->quantite * $production->produitFinis->prix;
                                                        $totalBen = $production->quantite * $production->produitFinis->prix;
                                                        $designations = explode(', ', $production->designation);
                                                    @endphp
                                                    <td> {{ $production->id }} </td>
                                                    <td> {{ $production->created_at }} </td>
                                                    <td> {{ $production->produitFinis->designation }} </td>
                                                    <td> {{ $production->quantite }} </td>
                                                    <td> {{ $production->produitFinis->prix }} </td>
                                                    <td> {{ $production->quantite * $production->produitFinis->prix }} Fc</td>
                                                    <td> 
                                                        @foreach ($production->compositions as $composition)
                                                            @php
                                                                $totProd += $composition->quantite * $composition->prix;  
                                                            @endphp
                                                            <li>({{ number_format($composition->quantite,0) }}) {{ $composition->designation }}</li>
                                                        @endforeach    
                                                    </td>
                                                    <td>{{ $totProd }} Fc</td>
                                                    <td class="text-{{ (($totalBen - $totProd) >= 0)? 'info' : 'danger' }}">{{ $totalBen - $totProd }} Fc</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle btn btn-primary" data-toggle="dropdown">Action</a>
                                                            
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                              
                                                                <a href="{{ route('production.edit', $production->id)}}" class="dropdown-item has-icon"><i class="far fa-edit text-primary"></i> Modifier</a>
                                                              
                                                            </div>
                                                        </div>
                                                    </td>
                                                    
                                                </tr>
                                            @endforeach
                                            
                                        </tbody>
                                        <h3>Total production : {{ $total }} Fc</h3> 
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