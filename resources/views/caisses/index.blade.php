@extends('layouts.backend')

@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ $viewData['title'] }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Tableau de Bord</a></div>
                    <div class="breadcrumb-item">{{ $viewData['title'] }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">

                        @if($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible" id="msg" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h6>{{ $error }}</h6>
                                </div>
                            @endforeach
                        @endif

                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible" id="msg" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h6>{{ Session::get('success') }}</h6>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $viewData['title'] }}</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('caisses.create') }}" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Nouvelle opération</a>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-1">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Motif</th>
                                                <th>Montant</th>
                                                <th>Solde</th>
                                                <th>Utilisateur</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $id = 1; @endphp
                                            @foreach ($viewData['caisses'] as $caisse)
                                                <tr>
                                                    <td>{{ $id++ }}</td>
                                                    <td>{{ $caisse->created_at->format('d/m/Y à H:i') }}</td>
                                                    <td>
                                                        <span class="badge {{ $caisse->type_operation === 'entree' ? 'badge-success' : 'badge-danger' }}">
                                                            {{ ucfirst($caisse->type_operation) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $caisse->motif ?? '-' }}</td>
                                                    <td>{{ number_format($caisse->montant, 2, ',', ' ') }} Fc</td>
                                                    <td>{{ number_format($caisse->solde_apres_operation, 2, ',', ' ') }} Fc</td>
                                                    <td>{{ $caisse->user->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if(auth()->user()->role === 'admin')
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle btn btn-primary" data-toggle="dropdown">Action</a>
                                                            <div class="dropdown-menu dropdown-menu-right">

                                                                @can('update', $caisse)
                                                                <a href="{{ route('caisses.edit', $caisse->id)}}" class="dropdown-item has-icon">
                                                                    <i class="far fa-edit text-primary"></i> Modifier
                                                                </a>
                                                                @endcan

                                                                @can('delete', $caisse)
                                                                    <form action="{{ route('caisses.destroy', $caisse->id) }}" method="POST" onsubmit="return confirm('Supprimer cette opération ?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item has-icon">
                                                                            <i class="fas fa-trash text-danger"></i> Supprimer
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </div>
                                                        </div>
                                                        @else
                                                            <span class="text-muted">Aucune action</span>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
