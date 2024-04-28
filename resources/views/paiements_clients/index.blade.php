

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
                                            <th>Date Commannde</th>
                                            <th>Client</th>
                                            <th>Total à payer</th>
                                            <th>Total payé</th>
                                            <th>Date Paiement</th>
                                            <th>Montant </th>
                                            <th>Reste</th>
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
                                                $tot = $tot + $commande->paye;
                                            @endphp
                                            @foreach ($commande->paiements as $paiement)
                                                
                                                <tr>
                                                    @if ($loop->first)
                                                        <td rowspan="{{ $commande->paiements->count() }}">{{ $id++ }}</td>
                                                        <td rowspan="{{ $commande->paiements->count() }}">{{ $commande->created_at }}</td>
                                                        <td rowspan="{{ $commande->paiements->count() }}">{{ $commande->client->nom }}</td>
                                                        <td rowspan="{{ $commande->paiements->count() }}">{{ $commande->montant }} Fc</td>
                                                        <td rowspan="{{ $commande->paiements->count() }}">{{ $commande->paye }} Fc</td>
                                                    @endif
                                                    <td> {{ $paiement->created_at }}</td>
                                                    <td> {{ $paiement->montant }} Fc</td>
                                                    <td> {{ $paiement->reste }} Fc</td>

                                                </tr>
                                            @endforeach
                                        @endforeach

                                    </tbody>
                                    <tr>
                                        <td colspan="4"><b>Total</b></td>
                                        <td><b>{{ $tot }} Fc</b></td>
                                        <td colspan="3"></td>
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