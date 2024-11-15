<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{ $viewData['title'] }}</title>

  <!-- Bootstrap Select -->
  <link rel="stylesheet" href="{{asset('assets/backend/bootstrap-select/dist/css/bootstrap.min.css ')}}">
  <link rel="stylesheet" href="{{asset('assets/backend/bootstrap-select/dist/css/bootstrap-select.min.css ')}}">
  <link rel="stylesheet" href="{{asset('assets/backend/modules/select2/dist/css/select2.min.css ')}}">


  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{asset('assets/backend/modules/bootstrap/css/bootstrap.min.css ')}}">
  <link rel="stylesheet" href="{{asset('assets/backend/modules/fontawesome/css/all.min.css ')}}">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{asset('assets/backend/modules/jqvmap/dist/jqvmap.min.css ')}}">
  <link rel="stylesheet" href="{{asset('assets/backend/modules/weather-icon/css/weather-icons.min.css ')}}">
  <link rel="stylesheet" href="{{asset('assets/backend/modules/weather-icon/css/weather-icons-wind.min.css ')}}">
  <link rel="stylesheet" href="{{asset('assets/backend/modules/summernote/summernote-bs4.css ')}}">

  <link rel="stylesheet" href="{{asset('assets/backend/modules/datatables/datatables.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/backend/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css')}}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('assets/backend/css/style.css ')}}">
  <link rel="stylesheet" href="{{asset('assets/backend/css/components.css ')}}">
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{asset('assets/backend/modules/ionicons/css/ionicons.min.css ')}}">

  <!-- Bootstrap-Iconpicker -->
<link rel="stylesheet" href="{{asset('assets/backend/modules/bootstrap-iconpicker/dist/css/bootstrap-iconpicker.min.css ')}}"/>

<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>

<!-- /END GA --></head>


<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar valider">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
          <div class="search-element">

            <div class="search-backdrop"></div>

          </div>
        </form>
        <ul class="navbar-nav navbar-right">

          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="{{asset('assets/backend/img/avatar/avatar-1.png ')}}" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">Salut, {{ Auth::user()->name }}</div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-title">Récemment connecté</div>
              <a href="{{route('profile.edit')}}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile
              </a>
              <div class="dropdown-divider"></div>

              <!-- Authentication -->
              <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a href="{{route('logout')}}" class="dropdown-item has-icon text-danger"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </form>
            </div>
          </li>
        </ul>
      </nav>
      @if (Auth::user()->role == 'admin')

        <div class="main-sidebar sidebar-style-2 valider">
            <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard')}}">Tableau de Bord</a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Tableau de Bord</li>

                <li class="@if (request()->routeIs('dashboard')) active @endif">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de Bord</span>
                </a>
                </li>

                <li class="dropdown @if (request()->routeIs('sites.*')) active @endif">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-store-alt"></i>
                    <span>Points de vente</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('sites.index')}}">- Liste points de ventes</a></li>
                </ul>
                </li>

                <li class="dropdown @if (request()->routeIs('categories.*')) active @endif">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-th-list"></i>
                    <span>Catégorie produit</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('categories.index')}}">- Liste catégories</a></li>
                </ul>
                </li>

                <li class="dropdown @if (request()->routeIs('partenaires.*')) active @endif">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-handshake"></i>
                    <span>Partenaires</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('partenaires.index')}}">- Liste des partenaires</a></li>
                </ul>
                </li>

                <li class="dropdown @if (request()->routeIs('produits.*')) active @endif">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="ion-android-cloud-circle"></i>
                    <span>Produits</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('produits.index')}}">- Liste des produits</a></li>
                </ul>
                </li>

                {{-- <li class="dropdown @if (request()->routeIs('commandes.*')) active @endif">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Commandes</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{route('commandes.index')}}">- Liste des commandes</a></li>
                    </ul>
                </li> --}}

                {{-- <li class="dropdown @if (request()->routeIs('productions.*')) active @endif">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-cogs"></i>
                        <span>Production</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{route('productions.index')}}">- Liste des productions</a></li>
                    </ul>
                </li> --}}

                {{-- <li class="dropdown @if (request()->routeIs('depots.*')) active @endif">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-warehouse"></i>
                        <span>Dépôt</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{route('depots.index')}}">- Mouvements dépôt</a></li>
                    </ul>
                </li> --}}

                {{-- <li class="dropdown @if (request()->routeIs('distributions.*')) active @endif">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-truck"></i>
                        <span>Distributions</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{route('distributions.index')}}">- Liste distributions</a></li>
                    </ul>
                </li> --}}

                <li class="dropdown @if (request()->routeIs('operation_guichets.*')) active @endif">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="ion-cash"></i>
                        <span>Vente</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{route('operation_guichets.indexAdmin')}}">- Liste ventes</a></li>
                    </ul>
                </li>

                <li class="dropdown @if (request()->routeIs('syntheses.*')) active @endif">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-chart-line"></i>
                        <span>Réconciliation</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{route('syntheses.index')}}">- Afficher</a></li>
                    </ul>
                </li>

                <li class="dropdown @if (request()->routeIs('rapports.*')) active @endif">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-file-alt"></i>
                        <span>Rapports</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('rapports.renconciliationAdmin')}}">- Reconciliation</a></li>
                        <li><a class="nav-link" href="{{route('rapports.venteAdmin')}}">- Fiche Ventes</a></li>
                        <li><a class="nav-link" href="{{ route('rapports.produitAdmin')}}">- Fiche Produits</a></li>
                        <li><a class="nav-link" href="{{ route('rapports.commandeAdmin')}}">- Fiche Commande </a></li>
                        <li><a class="nav-link" href="{{ route('rapports.productionAdmin')}}">- Fiche Production</a></li>
                        <li><a class="nav-link" href="{{ route('rapports.depotAdmin')}}">- Fiche Entrée Dépôt</a></li>
                        <li><a class="nav-link" href="{{ route('rapports.distributionAdmin')}}">- Fiche Distribution</a></li>


                    </ul>
                </li>

                <li class="dropdown @if (request()->routeIs('dashboard.usersIndex','dashboard.usersCreate')) active @endif">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-users" style="font-size: 16px"></i>
                        <span>Utilisateurs</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{route('dashboard.usersCreate')}}">- Ajouter un utilisateur</a></li>
                        <li><a class="nav-link" href="{{route('dashboard.usersIndex')}}">- Liste des utilisateurs</a></li>
                    </ul>
                </li>
            </ul>
            </aside>
        </div>

      @endif

      @if (Auth::user()->role == 'chef_distribution')

        <div class="main-sidebar sidebar-style-2 valider">
            <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard')}}">Tableau de Bord</a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Tableau de Bord</li>

                <li class="@if (request()->routeIs('dashboard')) active @endif">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de Bord</span>
                </a>
                </li>

                <li class="dropdown @if (request()->routeIs('commandes.*')) active @endif">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Commandes</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('commandes.index')}}">- Liste des commandes</a></li>
                </ul>
                </li>

                <li class="dropdown @if (request()->routeIs('distributions.*')) active @endif">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-truck"></i>
                    <span>Distributions</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('distributions.index')}}">- Liste distributions</a></li>
                </ul>
                </li>

                <li class="dropdown @if (request()->routeIs(
                'rapports.*')) active @endif">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-file-alt"></i>
                    <span>Rapports</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('rapports.stockMpMaison')}}">- Stock MP Dépôt</a></li>
                    <li><a class="nav-link" href="{{ route('rapports.stockMpUsine')}}">- Stock MP Usine</a></li>
                    <li><a class="nav-link" href="{{ route('rapports.stockPf')}}">- Stock Produits Finis</a></li>
                    <li><a class="nav-link" href="{{ route('rapports.stockBoulangerie',['site' => 'all'])}}">- Stock Point de vente</a></li>
                    <li><a class="nav-link" href="{{route('rapports.entreeStockMpAll')}}">- Achats MP</a></li>
                    <li><a class="nav-link" href="{{route('rapports.productionAll')}}">- Fiche Productions</a></li>
                    <li><a class="nav-link" href="{{route('rapports.syntheseAll')}}">- Fiche Synthese</a></li>
                    <li><a class="nav-link" href="{{route('rapports.venteAll')}}">- Fiche Ventes</a></li>
                    <li><a class="nav-link" href="{{route('rapports.dettesAll')}}">- Fiche Dettes Clients</a></li>
                    <li><a class="nav-link" href="{{route('rapports.paiementsAll')}}">- Fiche Paiements Clients</a></li>
                    <li><a class="nav-link" href="{{route('rapports.depenseAll')}}">- Fiche Dépenses</a></li>
                </ul>
                </li>
            </ul>
            </aside>
        </div>

      @endif

      @if (Auth::user()->role == 'chef_production')

        <div class="main-sidebar sidebar-style-2 valider">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <a href="{{ route('dashboard')}}">Tableau de Bord</a>
                </div>
                <ul class="sidebar-menu">
                    <li class="menu-header">Tableau de Bord</li>

                    <li class="@if (request()->routeIs('dashboard')) active @endif">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Tableau de Bord</span>
                    </a>
                    </li>

                    <li class="dropdown @if (request()->routeIs('productions.*')) active @endif">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-cogs"></i>
                        <span>Production</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{route('productions.index')}}">- Liste des productions</a></li>
                    </ul>
                    </li>

                    <li class="dropdown @if (request()->routeIs(
                    'rapports.*')) active @endif">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-file-alt"></i>
                        <span>Rapports</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="{{ route('rapports.stockMpMaison')}}">- Stock MP Dépôt</a></li>
                        <li><a class="nav-link" href="{{ route('rapports.stockMpUsine')}}">- Stock MP Usine</a></li>
                        <li><a class="nav-link" href="{{ route('rapports.stockPf')}}">- Stock Produits Finis</a></li>
                        <li><a class="nav-link" href="{{ route('rapports.stockBoulangerie',['site' => 'all'])}}">- Stock Point de vente</a></li>
                        <li><a class="nav-link" href="{{route('rapports.entreeStockMpAll')}}">- Achats MP</a></li>
                        <li><a class="nav-link" href="{{route('rapports.productionAll')}}">- Fiche Productions</a></li>
                        <li><a class="nav-link" href="{{route('rapports.syntheseAll')}}">- Fiche Synthese</a></li>
                        <li><a class="nav-link" href="{{route('rapports.venteAll')}}">- Fiche Ventes</a></li>
                        <li><a class="nav-link" href="{{route('rapports.dettesAll')}}">- Fiche Dettes Clients</a></li>
                        <li><a class="nav-link" href="{{route('rapports.paiementsAll')}}">- Fiche Paiements Clients</a></li>
                        <li><a class="nav-link" href="{{route('rapports.depenseAll')}}">- Fiche Dépenses</a></li>
                    </ul>
                    </li>
                </ul>
            </aside>
        </div>

      @endif

      @if (Auth::user()->role == 'chef_depot')

        <div class="main-sidebar sidebar-style-2 valider">
          <ul class="sidebar-menu">
            <li class="menu-header">Tableau de Bord</li>

            <li class=" @if (request()->routeIs('dashboard')) active @endif ">

              <a href="{{ route('dashboard') }}" class="nav-link "><i class="fas fa-tachometer-alt"></i><span>Tableau de Bord </span></a>

            </li>

            <li class="dropdown @if (request()->routeIs('depots.*')) active @endif">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-warehouse"></i>
                    <span>Dépôt</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('depots.index')}}">- Mouvements dépôt</a></li>
                </ul>
            </li>

          </ul>
        </div>

      @endif

      @if (Auth::user()->role == 'guichetier')

        <div class="main-sidebar sidebar-style-2 valider">
          <ul class="sidebar-menu">
            <li class="menu-header">{{ Auth::user()->name }}</li>

            <li class=" @if (request()->routeIs('dashboard')) active @endif ">

              <a href="{{ route('dashboard') }}" class="nav-link "><i class="fas fa-tachometer-alt"></i><span>Tableau de Bord </span></a>

            </li>

            <li class="dropdown @if (request()->routeIs('operation_guichets.*')) active @endif">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="ion-cash"></i>
                    <span>Vente</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('operation_guichets.create')}}">- Ajouter vente</a></li>
                    <li><a class="nav-link" href="{{route('operation_guichets.index')}}">- Liste ventes</a></li>
                </ul>
            </li>

          </ul>
        </div>

      @endif

      @yield('content')

      <footer class="main-footer valider">
        <div class="footer-left">
          Copyright &copy; {{ date('Y') }} <div class="bullet"></div> <a href="https://pdevtuto.com" target="__blank">Boulangerie Pain d'Or</a>
        </div>
        <div class="footer-right">

        </div>
      </footer>
    </div>
  </div>

  <style>
    li i{
      font-size: 20px;
    }
  </style>


  <!-- General JS Scripts -->
  <script src="{{asset('assets/backend/modules/jquery.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/popper.js')}}"></script>
  <script src="{{asset('assets/backend/modules/tooltip.js')}}"></script>
  <script src="{{asset('assets/backend/modules/bootstrap/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/nicescroll/jquery.nicescroll.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/moment.min.js')}}"></script>
  <script src="{{asset('assets/backend/js/stisla.js')}}"></script>
  <script src="{{asset('assets/backend/js/bundle.js')}}"></script>


  <!-- JS Libraies -->
  <script src="{{asset('assets/backend/modules/simple-weather/jquery.simpleWeather.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/chart.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/jqvmap/dist/jquery.vmap.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
  <script src="{{asset('assets/backend/modules/summernote/summernote-bs4.js')}}"></script>
  <script src="{{asset('assets/backend/modules/chocolat/dist/js/jquery.chocolat.min.js')}}"></script>

  <!-- JS Libraies -->
  <script src="{{asset('assets/backend/modules/datatables/datatables.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/datatables/Select-1.2.4/js/dataTables.select.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/jquery-ui/jquery-ui.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/jquery-selectric/jquery.selectric.min.js ')}}"></script>
  <script src="{{asset('assets/backend/modules/upload-preview/assets/js/jquery.uploadPreview.min.js ')}}"></script>
  <script src="{{asset('assets/backend/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js ')}}"></script>

  <!-- Page Specific JS File -->
  <script src="{{asset('assets/backend/js/page/modules-datatables.js')}}"></script>
  <script src="{{asset('assets/backend/js/bootstrap-iconpicker.bundle.min.js')}}"></script>
  <!-- Page Specific JS File -->
  <script src="{{asset('assets/backend/modules/upload-preview/assets/js/jquery.uploadPreview.min.js ')}}"></script>


  <!-- Page Specific JS File -->
  <script src="{{asset('assets/backend/js/page/index-0.js')}}"></script>

  <!-- Template JS File -->
  <script src="{{asset('assets/backend/js/scripts.js')}}"></script>
  <script src="{{asset('assets/backend/js/custom.js')}}"></script>

  <!-- Bootstrap Select -->
  <script src="{{asset('assets/backend/bootstrap-select/dist/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('assets/backend/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
  <script src="{{asset('assets/backend/modules/select2/dist/js/select2.full.min.js ')}}"></script>



  <script>
    $(document).ready(function(){
        $('.print').on('click',function(){
            $('.valider').hide();
            if (!window.print()) {
                $('.valider').show();
            };
        });
    });
  </script>



</body>
</html>
