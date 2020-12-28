@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Número ganadores</div>
            <div id="general-alert"></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Lotería</th>
                        <th>Tipo Jugada</th>
                        <th>Puntos Jugada</th>
                        <th>Número jugado</th>
                        <th>Fecha y hora</th>
                        <th class="text-right">Premio</th>
                        <th class="text-center">Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($winners as $winner)
                        <tr>
                            <td>{{ $winner->id }}</td>
                            <td>{{ $winner->lottery->name }}</td>
                            <td>{{ $winner->ticket_play->type }}</td>
                            <td>{{ $winner->ticket_play->points }}</td>
                            <td>{{ $winner->ticket_play->number }}</td>
                            <td>{{ $winner->created_at }}</td>
                            <td class="text-right">$ {{ number_format($winner->reward, 2, ',', ' ') }}</td>
                            <td class="text-center">
                                @if($winner->paid)
                                    <button class="btn btn-primary btn-sm" disabled>
                                        <i class="fa fa-money"></i>
                                        Pagado
                                    </button>
                                @else
                                    <a data-delete="{{ $winner->id }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-money"></i>
                                        Pagar
                                    </a>
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
        $(document).on('click', '[data-delete]', function () {
            let id = $(this).data('delete');
            let $token = @json($tokenResult->accessToken);

            swal({
                title: '¿Seguro que desea pagar este premio?',
                text: "",
                type: 'success',
                showCancelButton: true,
                confirmButtonColor: '#10c469',

                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, pagar!'
            }).then((result) => {
                if (result.value) {
                    let $td = $(this).closest('td');

                    $.ajax({
                        type: 'GET',
                        url: 'api/paid/'+id,
                        headers: {'Authorization': 'Bearer '+$token},
                        data:{
                        },
                        success:function (data){
                            if(!data.success) {
                                $('#general-alert').html('<div class="alert alert-danger alert-dismissable">' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                                    '<strong>'+data.error_message+'</strong>' +
                                    '</div>');
                            } else {
                                $td.html('<button class="btn btn-primary btn-sm" disabled><i class="fa fa-money"></i>Pagado</button>');
                            }

                        }
                    });
                }
            });
        });

    </script>
@endsection
