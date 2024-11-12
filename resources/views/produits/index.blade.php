@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('produits.index') }}">Produits</a></div>
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
                                    <h6>{{ $error }}</h6>
                                </div>
                            @endforeach
                        @endif
                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible" id="msg" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h6>{{ Session::get('success') }}</h6>
                            </div> 
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $viewData['title'] }}</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('produits.create') }}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Ajouter produit</a>
                                </div>   
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>                                 
                                            <tr>
                                                <th>#</th>
                                                <th>Nom du produit</th>
                                                <th>Catégorie</th>
                                                <th>Prix</th>
                                                <th>Kg par sac</th>
                                                <th>Quantité par sac</th>
                                                <th>Quantité par kg</th>
                                                <th>Date d'enregistrement</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($viewData['produits'] as $index => $produit) 
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $produit->nom }}</td>
                                                    <td>{{ $produit->category->name ?? 'Non spécifié' }}</td>
                                                    <td>{{ number_format($produit->prix, 2) }} Fc</td>
                                                    <td>{{ number_format($produit->kg_par_sac, 2) }}</td>
                                                    <td>{{ number_format($produit->qte_par_sac, 2) }}</td>
                                                    <td>{{ number_format($produit->qte_par_kg, 2) }}</td>
                                                    <td>{{ $produit->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle btn btn-primary" data-toggle="dropdown">Action</a>
                                                            
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a href="{{ route('produits.edit', $produit->id) }}" class="dropdown-item has-icon"><i class="far fa-edit text-primary"></i> Modifier</a>
                                                              
                                                                <form action="{{ route('produits.destroy', $produit->id) }}" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer ce produit ?');">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item has-icon"><i class="fas fa-trash text-danger"></i> Supprimer</button>
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
