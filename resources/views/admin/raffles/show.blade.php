@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Sorteo {{ $raffle->lottery->name }}</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Ganancia</th>
                            <th>Tipo de jugada</th>
                            <th>Número ganador</th>
                            <th>Vendedor</th>
                            <th>Estado de pago</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($raffle->winners as $winner)
                            <tr>
                                <td>{{ $winner->reward }}</td>
                                <td>{{ $winner->ticket_play->type }}</td>
                                <td>{{ $winner->ticket_play->number }}</td>
                                <td>{{ $winner->user->name }}</td>
                                <td>{{ $winner->paid ? 'Pagado' : 'Por pagar' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
