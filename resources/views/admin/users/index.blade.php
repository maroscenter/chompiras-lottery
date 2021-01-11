@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Listado de vendedores</div>
            <div class="card-body">
                <div class="form-group">
                    <a href="{{ url('users/create') }}" class="btn btn-sm btn-success pull-right">
                        <i class="fa fa-plus"></i>
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
                                <a href="{{ url('users/'.$user->id.'/edit') }}" class="btn btn-primary btn-sm">
                                    <span class="fa fa-edit"></span>
                                    Editar
                                </a>
                                @if($user->deleted_at)
                                    <button data-activate="{{ url('users/'.$user->id.'/activate') }}" class="btn btn-success btn-sm">
                                        <span class="fa fa-check"></span>
                                        Activar
                                    </button>
                                @else
                                    <button data-deactivate="{{ url('users/'.$user->id.'/deactivate') }}"  class="btn btn-danger btn-sm">
                                        <span class="fa fa-times"></span>
                                        Desactivar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Sweet alert 2 -->
    <script src="https://unpkg.com/sweetalert2@7.3.0/dist/sweetalert2.all.js"></script>
    <script>
        $(document).ready(function() {
            $('[data-deactivate]').on('click', onClickDeactivate);
            $('[data-activate]').on('click', onClickActivate);
        });

        function onClickDeactivate() {
            let urlDelete = $(this).data('deactivate');
            swal({
                title: '¿Seguro que desea desactivar este vendedor?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',

                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, desactivar!'
            }).then((result) => {
                if (result.value) {
                    location.href = urlDelete;
                }
            });
        }

        function onClickActivate() {
            let urlRestore = $(this).data('activate');
            swal({
                title: '¿Seguro que desea activar este vendedor?',
                text: "",
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#10c469',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, activar!'
            }).then((result) => {
                if (result.value) {
                    location.href = urlRestore;
                }
            });
        }
    </script>
@endsection
