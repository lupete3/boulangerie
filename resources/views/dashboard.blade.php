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

          <form action="{{ route('dashboard') }}" method="GET" class="mb-4">
            <div class="form-row">
                <div class="col-md-3">
                    <label for="date_debut">Date de début :</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut', $date_debut) }}">
                </div>
                <div class="col-md-3">
                    <label for="date_fin">Date de fin :</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin', $date_fin) }}">
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Filtrer</button>
                </div>
            </div>
        </form>

          <div class="row">
            <!-- Total kg demandés -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="ion-android-cloud"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total KG Demandés</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_kg_demanded }} kg
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total produits demandés -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="ion-android-cloud-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Produits Demandés</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_produits_demanded }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- KG Réel -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="ion-android-cloud-done"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>KG Réel</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($total_kg_reel, 2) }} kg
                        </div>
                    </div>
                </div>
            </div>

            <!-- Perte Production Valorisé -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="ion-home"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Perte Production Valorisé</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_perte_production_valorisee }} Fc
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quantité Dépot -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="ion-ios-download"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Quantité Dépot</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_quantite_depot }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Distribution -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="ion-ios-upload"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Distribution</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_distribution }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Perte Dépot -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="ion-cash"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Perte Dépot</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_perte_depot }} Fc
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bon Produit -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-dark">
                        <i class="ion-person"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Bon Produit</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_bon_produit }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Montant Physique -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-secondary">
                        <i class="ion-cash"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Montant Physique</h4>
                        </div>
                        <div class="card-body">
                            {{ $montant_physique }} Fc
                        </div>
                    </div>
                </div>
            </div>

            <!-- Montant Change -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="ion-cash"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Montant Change</h4>
                        </div>
                        <div class="card-body">
                            {{ $montant_change }} Fc
                        </div>
                    </div>
                </div>
            </div>

            <!-- Montant Manquant -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="ion-close-circled"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Montant Manquant</h4>
                        </div>
                        <div class="card-body">
                            {{ $montant_manquant }} Fc
                        </div>
                    </div>
                </div>
            </div>

            <!-- Montant Excédent -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="ion-checkmark-circled"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Montant Excédent</h4>
                        </div>
                        <div class="card-body">
                            {{ $montant_excedent }} Fc
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prix Total -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="ion-pricetag"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Sacs produits</h4>
                        </div>
                        <div class="card-body">
                            {{ number_format($total_bon_sac, 2) }}
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
