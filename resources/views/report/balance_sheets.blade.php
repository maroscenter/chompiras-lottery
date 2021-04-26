@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-3">
            <div class="card-header">
                Balance de cuenta
            </div>
            <div class="card-body">
                <h2>$ {{ number_format($user->balance, 2, '.', '') }}</h2>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($user->movement_histories as $movementHistory)
                        <tr>
                            <td>{{ $movementHistory->description }}</td>
                            <td>{{ $movementHistory->amount }}</td>
                            <td>{{ $movementHistory->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
