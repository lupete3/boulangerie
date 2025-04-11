@extends('layouts.print')


@section('content')

<style>
  th,tr,td{
    font-size: 14px
  }
</style>

    <!-- Main Content -->
    <div class="container-fluid pt-4">

        <section class="section">

            <div class="section-body ">
              <div class="row">
                <div class="col-md-2 align-center">
                  <a href="{{ route('rapports.productionJour')}}" class="btn btn-primary  valider">Rapport Journalier</a>
                </div>
                <div class="col-3 col-md-3 col-lg-3 align-center">
                  <a href="{{ route('rapports.productionHebdo')}}" class="btn btn-primary  valider">Rapport Hebdomadaire</a>
                </div>
                <div class="col-md-2 align-center">
                  <a href="{{ route('rapports.productionAnnuel')}}" class="btn btn-primary  valider">Rapport Annuel</a>
                </div>
                <div class="col-md-2 align-center">
                  <button type="button" class="btn btn-primary  valider" data-toggle="modal" data-target="#exampleModal">
                    Rapport personnalisé
                  </button>
                </div>


              </div>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 align-center">

                      <div class="row" style="margin-bottom:10px;  " >
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <center>
                                <p style="font-weight:bold; font-family:Century Gothic; font-size:1.6em;" >
                                    {{ $viewData['title'] }}
                                </p>
                            </center>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="">
                                <div class="row spacer" style="margin-bottom:20px; " >

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table class="table table-striped" >
                                            <thead>
                                                <tr>
                                                    <th>Date production</th>
                                                    <th>Produit</th>
                                                    <th>Quantité produite</th>
                                                    <th>Prix de vente</th>
                                                    <th>Valeur de production</th>
                                                    <th>Composition_Matières_Premières</th>
                                                    <th>Coût de production (Composition + Personnel + Autres Charges)</th>
                                                    <th>Bénéfice (Valeur Production - Coût Production)</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @php
                                                    $total = 0;
                                                    $totProd = 0;
                                                    $totalBen = 0;
                                                    $totCharge = 0;
                                                    $sommeCharges = 0;
                                                @endphp

                                                @foreach ($viewData['productions'] as $production)
                                                <tr>
                                                    @php
                                                        // Réinitialiser les variables pour chaque production
                                                        $totalBen = $production->quantite * $production->produitFinis->prix; // Valeur de production
                                                        $totProd = 0; // Coût des matières premières pour cette production
                                                        $totCharge = $production->charge_personnel + $production->autres_charges; // Charges fixes

                                                        // Calculer le coût des matières premières
                                                        foreach ($production->compositions as $composition) {
                                                            $totProd += $composition->quantite * $composition->prix;
                                                        }

                                                        // Calculer le coût total de production
                                                        $coutTotalProduction = $totProd + $totCharge;

                                                        // Ajouter le coût total à la somme globale des charges
                                                        $sommeCharges += $coutTotalProduction;
                                                        $total += $totalBen;
                                                    @endphp

                                                    <td>{{ $production->created_at }}</td>
                                                    <td>{{ $production->produitFinis->designation }}</td>
                                                    <td>{{ $production->quantite }}</td>
                                                    <td>{{ $production->produitFinis->prix }}</td>
                                                    <td>{{ $totalBen }} Fc</td>
                                                    <td>
                                                        @foreach ($production->compositions as $composition)
                                                            <li>({{ number_format($composition->quantite, 1) }}{{ $composition->unite }}) {{ $composition->designation }}</li>
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $coutTotalProduction }} Fc</td>
                                                    <td class="text-{{ (($totalBen - $coutTotalProduction) >= 0) ? 'info' : 'danger' }}">
                                                        {{ $totalBen - $coutTotalProduction }} Fc
                                                    </td>
                                                </tr>
                                                @endforeach


                                            </tbody>
                                            <tr>
                                                <td colspan="4"><b>Total</b></td>
                                                <td><b>{{ $total }} Fc</b></td>
                                                <td></td>
                                                <td><b>{{ $sommeCharges }} Fc</b></td>
                                                <td><b>{{ $total - $sommeCharges }} Fc</b></td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>

                                <div class="row spacer" style="margin-bottom: 1.3em;">

                                  <table class="container-fluid">
                                    <p style="font-family:Century Gothic; font-size:1em; margin-left:20px; ">
                                        Date : <?php echo date('d-m-Y'); ?>
                                        <br>
                                        <span>Heure : <?php echo date('H:i'); ?></span>
                                         <br>

                                    </p>

                                  </table>
                            </div>

                            <div class="row">
                                <div class="col-md-3 offset-3">
                                  <button type="button" class="btn btn-primary print pull-right valider"><span class="fa fa-print"></span> Imprimer</button>
                                  <a href="{{ url()->previous() }}" class="btn btn-secondary valider">
                                    <span class="fa fa-arrow-left"></span> Retour
                                  </a>
                                </div>
                            </div>

                        </div>

                      </div>

                    </div>
                  </div>
          </div>
        </section>
    </div>

     <!-- Critere selon date -->
     <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Intervalle donnée</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" class="row" action="{{ route('rapports.productionDate')}}" enctype="multipart/form-data">
              @csrf
              <div class="form-group col-5 col-md-5 col-lg-5">
                <label>Date début</label>
                <input type="date" class="form-control" name="debut" value="{{ old('debut') }}" required="">
              </div>
              <div class="form-group col-5 col-md-5 col-lg-5">
                <label>Date fin</label>
                <input type="date" class="form-control" name="fin" value="{{ old('fin') }}" required="">
              </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            <button type="submit" class="btn btn-primary  valider">Rechercher</button>
          </div>
        </div>
      </form>
      </div>
    </div>

@endsection

