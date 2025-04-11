@extends('layouts.print')


@section('content')

<style>
  th,tr,td{
    font-size: 16px
  }
</style>

    <!-- Main Content -->
    <div class="container-fluid pt-4">
        <section class="section">
            <div class="section-body ">
              <div class="row">
                <div class=" col-md-2  align-center">
                  <a href="{{ route('rapports.syntheseJour')}}" class="btn btn-primary  valider">Rapport Journalier</a>
                </div>
                <div class="col-3 col-md-3 col-lg-3 align-center">
                  <a href="{{ route('rapports.syntheseHebdo')}}" class="btn btn-primary  valider">Rapport Hebdomadaire</a>
                </div>
                <div class="col-3 col-md-3 col-lg-3 align-center">
                  <a href="{{ route('rapports.syntheseMensuel')}}" class="btn btn-primary  valider">Rapport Mensuel</a>
                </div>
                <div class=" col-md-2  align-center">
                  <a href="{{ route('rapports.syntheseAnnuel')}}" class="btn btn-primary  valider">Rapport Annuel</a>
                </div>
                <div class=" col-md-2  align-center">
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

                      </div>

                      <div class="">
                        <div class="row" style="margin-bottom:10px;  " >
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <div class="row spacer" style="margin-bottom:20px; " >

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered table-striped table-sm" style="font-family:Century Gothic; font-size:0.7em;">
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
                                                            <td class="text-danger">-{{ $synthese->manquant }}Fc</td>
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
                                                    <td class="text-danger"><b>-{{ $totManquant }}Fc</b></td>
                                                    <td colspan="2"></td>

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

                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style=""></div>
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
            <form method="post" class="row" action="{{ route('rapports.syntheseDate')}}" enctype="multipart/form-data">
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

