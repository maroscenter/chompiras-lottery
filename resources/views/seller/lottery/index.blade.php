@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Listado de loterías</div>
            <div class="card-body">
                <div class="form-group">
                    <a href="{{ url('lotteries/create') }}" class="btn btn-sm btn-success pull-right">
                        <i class="fa fa-plus"></i>
                        Registrar nueva lotería
                    </a>
                </div>

                <p>Listado de loterías de la plataforma.</p>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Abreviado</th>
                        <th>Código</th>
                        <th>Estatus</th>
                        <th class="text-center">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($lotteries as $lottery)
                        <tr>
                            <td>{{ $lottery->name }}</td>
                            <td>{{ $lottery->abbreviated }}</td>
                            <td>{{ $lottery->code }}</td>
                            <td>{{ $lottery->status ? 'Activo' : 'Inactivo' }}</td>
                            <td class="text-center">
                                <a href="{{ url('lotteries/'.$lottery->id.'/edit') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i>
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
