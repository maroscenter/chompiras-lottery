@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">Listado de fechas</div>
                    <div class="panel-body">
                        <p>Listado de fechas en las que se han registrado listas (al menos una).</p>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cantidad de listas reportadas</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($dates as $date)
                                    <tr>
                                        <td>{{ $date->day }}</td>
                                        <td>{{ $date->listsCount }}</td>
                                        <td>
                                            <a href="/dates/?lists={{ $date->day }}" class="btn btn-primary btn-sm">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                                Ver listas
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
