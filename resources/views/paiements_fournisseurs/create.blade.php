@extends('layouts.backend')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Enregistrer un paiement</h1>
        </div>

        <div class="section-body">
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

            <div class="card">
                <form action="{{ route('paiements.store') }}" method="POST">
                    @csrf
                    <div class="card-header">
                        <h4>{{$viewData['title']}}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('paiements.index')}}" class="btn btn-icon icon-left btn-info"><i class="fas fa-list-alt"></i> Afficher les paiements</a>
                        </div>
                    </div>
                    <div class="card-body row">
                        <div class="form-group col-md-6">
                            <label>Fournisseur : {{ $fournisseurs->nom }}</label>
                            <select name="fournisseur_id" id="fournisseurSelect" class="form-control" required>
                                <option value="{{ $fournisseurs->id }}">{{ $fournisseurs->nom }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Achat / Dette</label>
                            <select name="dette_fournisseur_id" class="form-control" required>
                                @foreach($dettes as $dette)
                                    <option value="{{ $dette->id }}">
                                        Achat #{{ $dette->id_achat }} - Reste à payer: {{ number_format($dette->reste_a_payer, 2) }} FC
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Montant à payer*</label>
                            <input type="number" name="montant" step="0.01" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Date de paiement</label>
                            <input type="date" name="date_paiement" class="form-control" value="{{ now()->toDateString() }}" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Mode de paiement</label>
                            <input type="text" name="mode_paiement" class="form-control" value="Espèces" placeholder="Espèces, Virement..." required>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Valider le paiement</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
