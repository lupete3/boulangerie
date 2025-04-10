

@extends('layouts.backend')

@php
    use Carbon\Carbon;
@endphp
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
                                <h4>INVENTAIRE JOURNALIER </h4>
                                
                            </div>
                             
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>                                 
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>PRIX TOTAL</th>
                                            <th>ABIME</th>
                                            <th>DEPENSE</th>
                                            <th>CONSOMMATION</th>
                                            <th>DETTE</th>
                                            <th>CHANGE</th>
                                            <th>TOTAL VENDUE</th>
                                            <th>ESPECE</th>
                                            <th>MANQUANT</th>
                                            <th>SITE</th>
                                            <th>BOULANGER</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $id = 1;
                                            $totVente = 0;
                                            $totAvarie = 0;
                                            $totDepense = 0;
                                            $totConsommation = 0;
                                            $totDette = 0;
                                            $totChange = 0;
                                            $totTotal = 0;
                                            $totEspece = 0;
                                            $totManquant = 0;
                                        @endphp
                                        @foreach ($viewData['syntheses'] as $synthese) 
                                            @php
                                                $totVente+=$synthese->vente;
                                                $totAvarie+=$synthese->avarie;
                                                $totDepense+=$synthese->depense ;
                                                $totConsommation+=$synthese->consommation ;
                                                $totDette+=$synthese->dette ;
                                                $totChange+=$synthese->change;
                                                $totTotal+=$synthese->total ;
                                                $totEspece+=$synthese->espece;
                                                $totManquant+=$synthese->manquant;
                                            @endphp
                                            <tr>
                                                <td>{{ $id++ }} </td>
                                                <td>{{ $synthese->created_at }} </td>
                                                <td>{{ $synthese->vente }}Fc</td>
                                                <td>{{ $synthese->avarie }}Fc</td>
                                                <td>{{ $synthese->depense }}Fc</td>
                                                <td>{{ $synthese->consommation }}Fc</td>
                                                <td>{{ $synthese->dette }}Fc</td>
                                                <td>{{ $synthese->change }}Fc</td>
                                                <td>{{ $synthese->total }}Fc</td>
                                                <td>{{ $synthese->espece }}Fc</td>
                                                <td class="text-danger">- {{ $synthese->manquant }}Fc</td>
                                                <td>{{ $synthese->site->nom }}</td>
                                                <td>{{ $synthese->user->name }}</td>

                                            </tr>
                                                
                                        @endforeach
                                        
                                    </tbody>
                                    <tr>
                                        <td colspan="2"><b>Total</b></td>
                                        <td><b>{{ $totVente }}Fc</b></td>
                                        <td><b>{{ $totAvarie }}Fc</b></td>
                                        <td><b>{{ $totDepense }}Fc</b></td>
                                        <td><b>{{ $totConsommation }}Fc</b></td>
                                        <td><b>{{ $totDette }}Fc</b></td>
                                        <td><b>{{ $totChange }}Fc</b></td>
                                        <td><b>{{ $totTotal }}Fc</b></td>
                                        <td><b>{{ $totEspece }}Fc</b></td>
                                        <td class="text-danger"><b>- {{ $totManquant }}Fc</b></td>
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