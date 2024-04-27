@extends('layouts.backend')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                  <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de bord</a></div>
                  <div class="breadcrumb-item"><a href="{{ route('dashboard.usersIndex')}}">Liste des utilisateurs</a></div>
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
                        <form method="post" action="{{ route('dashboard.usersStore')}}" enctype="multipart/form-data">
                            @csrf
                          <div class="card-header">
                            <h4>{{$viewData['title']}}</h4>
                            <div class="card-header-action">
                                <a href="{{ route('dashboard.usersIndex')}}" class="btn btn-icon icon-left btn-info"><i class="fas fa-list-alt"></i> Afficher les utilisateurs</a>
                            </div> 
                          </div>
                          <div class="card-body">
                            <div class="form-group">
                              <label>Rôle de l'utilisateur*</label>
                              <select name="role" class="form-control selectpicker" id="role" onchange="afficherCacherZone()" data-show-subtext="true" data-live-search="true" required>

                                <option value="admin">Administrateur</option>
                                <option value="geran_depot_maison">Gérant Dépôt Maison</option>
                                <option value="geran_depot_usine">Gérant Dépôt Usine</option>
                                <option value="geran_depot_boulangerie">Gérant Dépôt Boulangerie</option>
                                <option value="geran_depot_magasin">Gérant Boulangérie</option>
                               
                              </select>
                            </div>
                            <div class="form-group" id="site" style="display: none">
                              <label>Point de vente</label>
                              <select name="site_id" class="form-control selectpicker" id="site_id" data-show-subtext="true" data-live-search="true" required>

                                @foreach ($viewData['sites'] as $site)

                                  <option value="{{ $site->id }}">{{ $site->nom }}</option>

                                @endforeach
                               
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Nom complet*</label>
                              <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="" required="">
                            </div>
                            <div class="form-group">
                              <label>Adresse mail*</label>
                              <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="ex: geran1@gmail.com" required="">
                            </div>
                            <div class="form-group">
                              <label>Mot de passe*</label>
                              <input type="password" class="form-control" name="password"  required="">
                            </div>
                          
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

    <script>
      function afficherCacherZone() {
        var role = document.getElementById("role");
        var site = document.getElementById("site");

        if (role.value == "geran_depot_magasin") {
          site.style.display = "block";
        } else {
          site.style.display = "none";
        }
      }
    </script>

@endsection