@extends('layouts.print')


@section('content')

<style>
  th,tr,td{
    font-size: 16px
  }
</style>

    <!-- Main Content -->
    <div class="main-content">

        <section class="section">

            <div class="section-body ">

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
                        <div class="row spacer" style="margin-bottom:20px; " >

                            <div class="col-md-4 mt-4">
                                <div class="">
                                    <p>Coût Achat Matières Premières</p>
                                    <h4>{{ number_format($viewData['valeurTotaleEntrees'], 2, '.', ' ') }} Fc</h4>
                                </div>
                            </div><div class="col-md-4 mt-4">
                                <div class="">
                                    <p>Valeur Stock Matière Première Déppot</p>
                                    <h4>{{ number_format($viewData['stockMpMaison'], 2, '.', ' ') }} Fc</h4>
                                </div>
                            </div><div class="col-md-4 mt-4">
                                <div class="">
                                    <p>Valeur Stock Matière Première Usine</p>
                                    <h4>{{ number_format($viewData['valeurTotaleUsine'], 2, '.', ' ') }} Fc</h4>
                                </div>
                            </div>
                            <div class="col-md-4 mt-4">
                                <div class="">
                                    <p>Valeur Stock Produits Finis</p>
                                    <h4>{{ number_format($viewData['stockPf'], 2, '.', ' ') }} Fc</h4>
                                </div>
                            </div>
                            <div class="col-md-4 mt-4">
                                <div class="">
                                    <p>Valeur Stock Points de vente</p>
                                    <h4>{{ number_format($viewData['valeurTotalePointVente'], 2, '.', ' ') }} Fc</h4>
                                </div>
                            </div>
                            <div class="col-md-4 mt-4">
                                <div class="">
                                    <p>Valeur de production</p>
                                    <h4>{{ number_format($viewData['totalValeurProduction'], 2, '.', ' ') }} Fc</h4>
                                </div>
                            </div>
                            <div class="col-md-4 mt-4">
                                <div class="">
                                    <p>Coût de production</p>
                                    <h4>{{ number_format($viewData['totalCoutProduction'], 2, '.', ' ') }} Fc</h4>
                                </div>
                            </div>
                            <div class="col-md-4 mt-4">
                                <div class="">
                                    <p>Bénéfice Brute</p>
                                    <h4>{{ number_format($viewData['totalBenefice'], 2, '.', ' ') }} Fc</h4>
                                </div>
                            </div>

                        </div>

                        <div class="row spacer" style="margin-bottom: 1.3em;">

                          <table class="">
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
        </section>
    </div>

@endsection

<style>
  th,td{font-size: 2em;}
</style>

