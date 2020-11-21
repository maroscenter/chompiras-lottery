@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">Listado de vendedores</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <a href="/user/create" disabled class="btn btn-sm btn-success pull-right">
                                <span class="glyphicon glyphicon-plus"></span>
                                Registrar nuevo vendedor
                            </a>
                        </div>

                        <p>Listado de usuarios de la plataforma.</p>

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>E-mail</th>
                                <th>Nombre completo</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    <a href="/user/{{ $user->id }}/lists" class="btn btn-primary btn-sm">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        Ver listas
                                    </a>

                                    <a href="/user/{{ $user->id }}/edit" disabled class="btn btn-default btn-sm">
                                        <span class="glyphicon glyphicon-edit"></span>
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
        </div>
    </div>
@endsection
