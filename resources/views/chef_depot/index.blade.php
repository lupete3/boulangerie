@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Enregistrer les matières premières reçues</h1>

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

        <form action="{{ route('chef_depot.store') }}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Matière première</th>
                        <th>Quantité reçue (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rawMaterials as $material)
                        <tr>
                            <td>{{ $material->name }}</td>
                            <td>
                                <input type="number" name="materials[{{ $material->id }}]" class="form-control" required>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-success">Enregistrer</button>
        </form>
    </div>
@endsection
