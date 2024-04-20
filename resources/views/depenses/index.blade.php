

@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('depenses.index')}}">Dépenses</a></div>
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
                                    <a href="{{ route('depenses.create')}}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Ajouter une dépense</a>
                                </div>   
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>                                 
                                        <tr>
                                            <th>#</th>
                                            <th>Date dépense</th>
                                            <th>Motif</th>
                                            <th>Montant</th>
                                            <th>Personne concernée</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $id = 1;
                                            $tot = 0;
                                        @endphp
                                        @foreach ($viewData['depenses'] as $depense) 
                                            <tr>
                                                @php
                                                    $tot =+ $depense->montant;
                                                @endphp
                                                <td> {{ $id++ }} </td>
                                                <td> {{ $depense->created_at }} </td>
                                                <td> {{ $depense->motif }} </td>
                                                <td> {{ $depense->montant }} Fc</td>
                                                <td> {{ $depense->personne }} </td>
                                                
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle btn btn-primary" data-toggle="dropdown">Action</a>
                                                        
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                          
                                                            <a href="{{ route('depenses.edit', $depense->id)}}" class="dropdown-item has-icon"><i class="far fa-edit text-primary"></i> Modifier</a>
                                                          
                                                          <form action="{{ route('depenses.destroy', $depense->id)}}" method="post">
                                                            @csrf
                                                            <button  type="submit" class="dropdown-item has-icon"><i class="fas fa-trash text-danger"></i> Supprimer</button>
                                                          </form>
                                                          
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tr>
                                        <td colspan="3"><b>Total</b></td>
                                        <td><b>{{ $tot }} Fc</b></td>
                                        <td colspan="2"></td>
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

@endsection