

@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('stock-usine.index')}}">Liste matières premières</a></div>
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
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>                                 
                                        <tr>
                                            <th>#</th>
                                            <th>Matières premières</th>
                                            <th>Prix d'achat</th>
                                            <th>Solde</th>
                                            <th>Valeur du stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $tot = 0;
                                        @endphp
                                        @foreach ($viewData['matiresPremieres'] as $matiresPremiere) 
                                            @php
                                                $tot+=($matiresPremiere->stockMaison->prix * $matiresPremiere->solde)
                                            @endphp
                                            <tr>

                                                <td> {{ $matiresPremiere->id }} </td>
                                                <td> {{ $matiresPremiere->stockMaison->designation }} </td>
                                                <td> {{ $matiresPremiere->stockMaison->prix }} </td>
                                                <td> {{ $matiresPremiere->solde }} </td>
                                                <td> {{ $matiresPremiere->stockMaison->prix * $matiresPremiere->solde }} </td>
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tr>
                                        <td colspan="4"><b>Total Valeur en stock</b></td>
                                        <td colspan=""><b>{{ $tot }} fc</b></td>
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