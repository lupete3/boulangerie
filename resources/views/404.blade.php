@extends('layouts.backend')

@section('content')
    
    <!-- 404 Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s" >
      <div class="container text-center">
        <div class="row justify-content-center" style="margin-top: 100px">
          <div class="col-lg-6">
            <i class="bi bi-exclamation-triangle display-1 text-primary"></i>
            <h1 class="display-1">404</h1>
            <h1 class="mb-4">Page Non Retrouvée</h1>
            <p class="mb-4">
              Nous somme désolé !, la page que vous assayez d'afficher n'est pas disponible
            , Revenez sur la page d'accuei et réessayer
            </p>
            <a class="btn btn-primary py-3 px-5" href="{{ route('dashboard') }}">Revenez à l'accueil</a>
          </div>
        </div>
      </div>
    </div>
    <!-- 404 End -->

@endsection


   
