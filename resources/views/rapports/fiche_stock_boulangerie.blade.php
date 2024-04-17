@extends('layouts.backend')


@section('content')

<style>
  th,tr,td{
    font-size: 16px
  }
</style>

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header valider">
                
            </div>

            <div class="section-body ">
            
                <div class="row">
                  <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 align-center valider">
                    </div>
                  </div>
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

                      <div class="container">
                        <div class="row" style="margin-bottom:10px;  " >
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                           
                                <div class="container">
                                    <div class="row spacer" style="margin-bottom:20px; " >
            
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered table-striped table-sm" style="font-family:Century Gothic; font-size:0.7em;">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Designation </th>
                                                        <th>Prix </th>
                                                        <th>Quantité en stock</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php 
                                                      $num = 1;
                                                      $tot = 0;
                                                    @endphp
                                                    
                                                    @forelse ($viewData['stockBoulangerie'] as $produit)

                                                      @php
                                                        $tot +=$produit->stockProduitFinis->prix * $produit->solde;
                                                      @endphp
                                                      <tr>
                                                        <td>{{ $num++ }}</td>
                                                        <td>{{ $produit->stockProduitFinis->designation }}</td>
                                                        <td>{{ $produit->stockProduitFinis->prix }} Fc</td>
                                                        <td>{{ $produit->solde }}</td>
                                                        <td>{{ $produit->stockProduitFinis->prix * $produit->solde }} Fc</td>
                                                      </tr>

                                                    @empty
                                                    
                                                    @endforelse

                                                    @php
                                                      $num++;
                                                    @endphp
                                                    <tr>
                                                        <td colspan="4"><b>Total</b></td>
                                                        <td ><b>{{ $tot }} Fc</b></td>
                                                    </tr>
                                                </tbody>
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
                                  </div>
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

@endsection

<style>
  th,td{font-size: 2em;}
</style>

