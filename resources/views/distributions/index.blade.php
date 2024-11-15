@extends('layouts.backend')

<style>
    table tr{
        font-size: 12px;
    }
</style>

@section('content')

<div class="main-content">
    <section class="section" style="margin:-15px">
        {{-- <div class="section-header">
            <h1>Récapitulatif des Distributions</h1>   
        </div> --}}

        <div class="section-body">
            

            <div class="row">
                <div class="col-12">
                    @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ $error }}
                            </div>
                        @endforeach
                    @endif
                    @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <div class="card" >
                        <div class="card-header">
                            <h4>Distribution du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h4>
                            
                            <div class="card-header-action">
                                <a href="{{ route('distributions.create')}}" class="btn btn-icon icon-left btn-success"><i
                                        class="fas fa-plus"></i> Ajouter </a>
                            </div>
                        </div>
                        <div class="card-body" style="padding:10px">
                            <!-- Filtre par date -->
                            <form action="{{ route('distributions.index') }}" method="GET" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="date">Filtrer par date :</label>
                                        <input type="date" id="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
                                    </div>
                                </div>
                            </form>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité totale vers Sites</th>
                                        <th>Quantité totale vers Partenaires</th>
                                        <th>Détails des Sites</th>
                                        <th>Détails des Partenaires</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($produits as $produit)
                                        <tr>
                                            <td>{{ $produit->nom }}</td>

                                            <!-- Quantité totale vers Sites -->
                                            <td>
                                                @php
                                                    $quantiteTotalSite = isset($distributionsSites[$produit->id]) ? $distributionsSites[$produit->id]->sum('quantity') : 0;
                                                @endphp
                                                {{ $quantiteTotalSite }}
                                            </td>

                                            <!-- Quantité totale vers Partenaires -->
                                            <td>
                                                @php
                                                    $quantiteTotalPartenaire = isset($distributionsPartenaires[$produit->id]) ? $distributionsPartenaires[$produit->id]->sum('quantity') : 0;
                                                @endphp
                                                {{ $quantiteTotalPartenaire }}
                                            </td>

                                            <!-- Détails des Sites -->
                                            <td>

                                                @forelse ($distributionsSites[$produit->id] ?? [] as $distributionSite)
                                                    <span>{{ $distributionSite->site->nom }} : <b>{{ $distributionSite->quantity }}</b> </span> <br>
                                                @empty
                                                    <span>Aucune distribution</span>
                                                @endforelse

                                            </td>

                                            <!-- Détails des Partenaires -->
                                            <td>
                                                @forelse ($distributionsPartenaires[$produit->id] ?? [] as $distributionPartenaire)
                                                    <span>{{ $distributionPartenaire->partenaire->nom }} : <b>{{ $distributionPartenaire->quantity }}</b> </span><br>
                                                @empty
                                                    <span>Aucune distribution</span>
                                                @endforelse
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Aucun produit trouvé.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
