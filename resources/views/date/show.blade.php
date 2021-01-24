@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">Listas de una fecha específica</div>
                    <div class="panel-body">
                        <p>Listas reportadas en la fecha {{ $specific_date }}.</p>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Fecha y hora</th>
                                    <th>Vendedor</th>
                                    <th>Total de tiempos vendidos</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($lists as $list)
                                    <tr>
                                        <td>{{ $list->created_at }}</td>
                                        <td>{{ $list->user->name }}</td>
                                        <td>{{ $list->total_tickets_sold }}</td>
                                        <td>
                                            <a href="/list/{{ $list->id }}" class="btn btn-primary btn-sm">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                                Ver tiempos
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
        </div>
    </div>
@endsection
