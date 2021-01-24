@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Listado de sorteos</div>
            <div class="card-body">
                <div class="form-group">
                    <a href="{{ url('raffles/create') }}" class="btn btn-sm btn-success pull-right mb-3">
                        <i class="fa fa-plus"></i>
                        Registrar nuevo sorteo
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Lotería</th>
                            <th>Número 01</th>
                            <th>Número 02</th>
                            <th>Número 03</th>
                            <th>Fecha y hora</th>
                            <th>Opción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($raffles as $raffle)
                            <tr>
                                <td>{{ $raffle->lottery->name }}</td>
                                <td>{{ $raffle->number_1 }}</td>
                                <td>{{ $raffle->number_2 }}</td>
                                <td>{{ $raffle->number_3 }}</td>
                                <td>{{ $raffle->datetime }}</td>
                                <td>
                                    <a href="/raffles/{{ $raffle->id }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-eye"></i>
                                        Ver ganadores
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
