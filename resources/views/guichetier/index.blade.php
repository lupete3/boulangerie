@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Gestion du guichet</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif



        <form action="{{ route('guichetier.store') }}" method="POST">
            @csrf
            <h3>Produits reçus</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité reçue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>
                                <input type="number" name="products_received[{{ $product->id }}]" class="form-control" required>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h3>Ventes et gestion des stocks</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Stock de début</th>
                        <th>Stock de fin</th>
                        <th>Quantité vendue</th>
                        <th>Dette accordée</th>
                        <th>Consommé</th>
                        <th>Montant déposé (FCFA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $initialStock[$product->id] ?? 0 }}</td>
                            <td>
                                <input type="number" name="stock_end[{{ $product->id }}]" class="form-control" required>
                            </td>
                            <td>
                                <input type="number" name="quantity_sold[{{ $product->id }}]" class="form-control">
                            </td>
                            <td>
                                <input type="number" name="debt[{{ $product->id }}]" class="form-control">
                            </td>
                            <td>
                                <input type="number" name="consumed[{{ $product->id }}]" class="form-control">
                            </td>
                            <td>
                                <input type="number" name="amount_deposited[{{ $product->id }}]" class="form-control" required>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h3>Détails du shift</h3>
            <div class="form-group">
                <label for="total_cash">Montant total des coupures de change</label>
                <input type="number" name="total_cash" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Clôturer le shift</button>
        </form>
    </div>
@endsection
