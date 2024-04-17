@extends('layouts.backend')

@php
  use Carbon\Carbon;
@endphp

@section('content')
   
    <!-- Main Content Admin-->
    @if (Auth::user()->role == 'admin')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Tableau de bord</h1>
          </div>

          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="ion-android-cloud"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Val. Stock MP Maison</h4>
                  </div>
                  <div class="card-body">

                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($viewData['stockMaisons'] as $stockMaison)
                      @php
                          $tot += ($stockMaison->prix * $stockMaison->solde);
                      @endphp
                    @endforeach
                    {{ $tot }} Fc

                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="ion-android-cloud-circle"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Val. Stock MP Usine</h4>
                  </div>
                  <div class="card-body">

                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($viewData['stockUsines'] as $stockUsine)
                      @php
                          $tot += ($stockUsine->stockMaison->prix * $stockUsine->stockMaison->solde);
                      @endphp
                    @endforeach
                    {{ $tot }} Fc

                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="ion-android-cloud-done"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Val. Stock PF</h4>
                  </div>
                  <div class="card-body">

                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($viewData['stockPfs'] as $stockPf)
                      @php
                          $tot += ($stockPf->prix * $stockPf->solde);
                      @endphp
                    @endforeach
                    {{ $tot }} Fc

                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="ion-home"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Val. Stock Boulang.</h4>
                  </div>
                  <div class="card-body">

                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($viewData['stockBoulangerie'] as $stockBoul)
                      @php
                          $tot += ($stockBoul->stockProduitFinis->prix * $stockBoul->solde);
                      @endphp
                    @endforeach
                    {{ $tot }} Fc

                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                  <i class="ion-ios-download"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Coût Achats Jour.</h4>
                  </div>
                  <div class="card-body">

                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($viewData['achats'] as $achat)
                      @php
                          $tot += ($achat->prix_achat * $achat->quantite);
                      @endphp
                    @endforeach
                    {{ $tot }} Fc

                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="ion-ios-upload"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Coût Ventes Jour.</h4>
                  </div>
                  <div class="card-body">

                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($viewData['ventes'] as $vente)
                      @php
                          $tot += ($vente->prix * $vente->quantite);
                      @endphp
                    @endforeach
                    {{ $tot }} Fc

                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="ion-cash"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Tot. Dépenses Jour</h4>
                  </div>
                  <div class="card-body">
                    
                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($viewData['depenses'] as $depense)
                      @php
                          $tot += ($depense->montant);
                      @endphp
                    @endforeach
                    {{ $tot }} Fc

                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-dark">
                  <i class="ion-person"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Tot. Founisseurs</h4>
                  </div>
                  <div class="card-body">
                    
                    {{ count($viewData['fournisseurs']) }}

                  </div>
                </div>
              </div>
            </div>

          <div class="row">
            <div class="col-md-6">
              <div class="card" >
                <div class="card-header" style="margin-bottom:-30px">
                  <div class="row">
                    <div class="col-md-12">
                      <h6 style="font-size: 14px">Achats MP du @php echo date('d-m-Y') @endphp </h6>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-striped" id="table-1" style="font-size: 12px">
                        <thead>                                 
                            <tr>
                                <th>Date Achat</th>
                                <th>Matière Première</th>
                                <th>Quantite Entrée</th>
                                <th>Fournisseur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($viewData['achats'] as $entree) 
                                <tr>

                                    <td> {{ $entree->created_at }} </td>
                                    <td> {{ $entree->stockMaison->designation }} </td>
                                    <td> {{ $entree->quantite }} </td>
                                    <td> {{ $entree->fournisseur->nom }} </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card" >
                <div class="card-header" style="margin-bottom:-30px">
                  <div class="row">
                    <div class="col-md-12">
                      <h6 style="font-size: 14px">Ventes Produits du @php echo date('d-m-Y') @endphp </h6>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-striped" id="table-1" style="font-size: 12px">
                        <thead>                                 
                            <tr>
                                <th>Date Ventes</th>
                                <th>Produit</th>
                                <th>Quantite Sortie</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($viewData['ventes'] as $sortie) 
                                <tr>

                                    <td> {{ $sortie->created_at }} </td>
                                    <td> {{ $sortie->designation }} </td>
                                    <td> {{ $sortie->quantite }} </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
          
        </section>
      </div>
    @endif

    @if (Auth::user()->role == 'geran_depot_maison')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Tableau de bord</h1>
          </div>

          <div class="row">
            
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="ion-android-cloud"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Val. Stock MP Maison</h4>
                  </div>
                  <div class="card-body">

                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($viewData['stockMaisons'] as $stockMaison)
                      @php
                          $tot += ($stockMaison->prix * $stockMaison->solde);
                      @endphp
                    @endforeach
                    {{ $tot }} Fc

                  </div>
                </div>
              </div>
            </div>  

          </div>
          
        </section>
      </div>
    @endif

    @if (Auth::user()->role == 'geran_depot_usine')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Tableau de bord</h1>
          </div>

          <div class="row">
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="ion-android-cloud-circle"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Val. Stock MP Usine</h4>
                  </div>
                  <div class="card-body">

                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($viewData['stockUsines'] as $stockUsine)
                      @php
                          $tot += ($stockUsine->stockMaison->prix * $stockUsine->stockMaison->solde);
                      @endphp
                    @endforeach
                    {{ $tot }} Fc

                  </div>
                </div>
              </div>
            </div>  

          </div>
          
        </section>
      </div>
    @endif
    @if (Auth::user()->role == 'geran_depot_boulangerie')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Tableau de bord</h1>
          </div>

          <div class="row">
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="ion-android-cloud-done"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Val. Stock PF</h4>
                  </div>
                  <div class="card-body">

                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($viewData['stockPfs'] as $stockPf)
                      @php
                          $tot += ($stockPf->prix * $stockPf->solde);
                      @endphp
                    @endforeach
                    {{ $tot }} Fc

                  </div>
                </div>
              </div>
            </div>  

          </div>
          
        </section>
      </div>
    @endif
    @if (Auth::user()->role == 'geran_depot_magasin')
    <div class="main-content">
      <section class="section">
        <div class="section-header">
          <h1>Tableau de bord</h1>
        </div>

        <div class="row">
          
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="ion-ios-upload"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Coût Ventes Jour.</h4>
                </div>
                <div class="card-body">

                  @php
                      $tot = 0;
                  @endphp
                  @foreach ($viewData['ventes'] as $vente)
                    @php
                        $tot += ($vente->prix * $vente->quantite);
                    @endphp
                  @endforeach
                  {{ $tot }} Fc

                </div>
              </div>
            </div>
          </div>  

        </div>
        
      </section>
    </div>
    @endif

@endsection

<style>
  .card-icon i{
    font-size: 20px;
    color: white;
    font-size: 30px;
  }
  .card-icon{
    padding-top: 25px;
  }
  
</style>