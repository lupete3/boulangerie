
@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('sites.index')}}">Points de vente</a></div>
                  <div class="breadcrumb-item">{{ $viewData['title'] }}</div>
                </div>
            </div>

            <div class="section-body ">
            
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 align-center">
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
                      <div class="card ">
                        <form action="{{ route('chef_distribution.store') }}" method="POST">
                            @csrf
                          <div class="card-header">
                            <h4>{{$viewData['title']}}</h4>
                            <div class="card-header-action">
                                <a href="{{ route('sites.index')}}" class="btn btn-icon icon-left btn-info"><i class="fas fa-list-alt"></i> Afficher les Points de vente</a>
                            </div> 
                          </div>
                          <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité attendue</th>
                                        <th>Quantité reçue</th>
                                        <th>Répartition aux guichets</th>
                                        <th>Répartition aux partenaires</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td><input type="number" name="products[{{ $product->id }}][quantity_expected]" class="form-control"></td>
                                            <td><input type="number" name="products[{{ $product->id }}][quantity_received]" class="form-control"></td>
                                            <td>
                                                @foreach($counters as $counter)
                                                    <label>{{ $counter->location }}</label>
                                                    <input type="number" name="products[{{ $product->id }}][counters][{{ $counter->id }}]" class="form-control">
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($partners as $partner)
                                                    <label>{{ $partner->name }}</label>
                                                    <input type="number" name="products[{{ $product->id }}][partners][{{ $partner->id }}]" class="form-control">
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                          </div>
                          <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Enregistrer</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
          </div>
        </section>
    </div>

@endsection

