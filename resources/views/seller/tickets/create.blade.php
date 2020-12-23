@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-7">
                            <h5>Registrar ticket</h5>
                        </div>
                        <div class="col-sm-5">
                            <div class="float-right">
                                <button type="button" data-register="{{ url('api/tickets') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-save"></i>
                                    Registrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="general-alert"></div>
                    <div class="row">
                        <div class="col-sm-3">
                            <h5>Loterías</h5>
                            @foreach($lotteries as $lottery)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="lotteries[]" value="{{ $lottery->id }}" id="lottery{{$lottery->id}}">
                                    <label class="form-check-label" for="lottery{{$lottery->id}}">
                                        {{ $lottery->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-sm-9">
                            <div id="alert"></div>
                            <div class="form-inline">
                                <div class="form-group mb-2">
                                    <label for="type" class="sr-only">Jugada</label>
                                    <select name="" id="type" class="form-control">
                                        <option value="">Seleccionar una jugada</option>
                                        <option value="Quiniela">Quiniela</option>
                                        <option value="Pale">Pale</option>
                                        <option value="Tripleta">Tripleta</option>
                                    </select>
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <label for="number" class="sr-only">Número</label>
                                    <input type="number" class="form-control" id="number" placeholder="Número">
                                </div>
                                <div class="form-group mx-sm-3 mb-2">
                                    <label for="points" class="sr-only">Puntos</label>
                                    <input type="number" class="form-control" id="points" placeholder="Puntos">
                                </div>
                                <button type="button" id="btnAdd" class="btn btn-primary mb-2">Marcar</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Jugada</th>
                                            <th>Número</th>
                                            <th>Puntos</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="https://unpkg.com/sweetalert2@7.3.0/dist/sweetalert2.all.js"></script>
    <script>
        $(document).on('click', '[data-delete]', function () {
            let urlDelete = $(this).data('delete');

            swal({
                title: '¿Seguro que desea eliminar esta jugada?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',

                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminar!'
            }).then((result) => {
                if (result.value) {
                    let $div = $(this).closest('tr');
                    $div.remove();
                }
            });
        });

    </script>
    <script>
        $(document).ready(function() {
            $('#btnAddInactiveDay').on('click', onAddInactiveDay);
        });
        function onAddInactiveDay() {
            let $inactiveDate = $('#inactiveDate');
            const $alert = $('#alertInactiveDay');
            const $tBody = $('#contentInactiveDays');



            if($inactiveDate.val() === ''){
                $alert.html('<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '<strong>Complete el campo para agregar un día inactivo</strong>' +
                    '</div>');
            } else if ($('#contentInactiveDays').find(':input[value="'+$inactiveDate.val()+'"]').val()) {
                $alert.html('<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '<strong>El día ingresado ya se encuentra agregado</strong>' +
                    '</div>');
            } else {
                $alert.html('');

                $tBody.append('<tr>\n' +
                    '<td>'+$inactiveDate.val()+'<input type="hidden" name="inactive_dates[]" value="'+$inactiveDate.val()+'"></td>\n' +
                    '<td class="w-25 text-center">\n' +
                    '<button class="btn btn-sm btn-danger" type="button" title="Eliminar"\n' +
                    'data-delete="">Eliminar</button>\n' +
                    '</td>\n' +
                    '</tr>');

                $inactiveDate.val('');
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#btnAdd').on('click', onAddAccount);
        });

        function onAddAccount() {
            let $number = $('#number');
            let $type = $('#type').find('option:selected');
            let $points = $('#points');
            const $alert = $('#alert');
            const $tBody = $('#tbody');

            if($number.val() === '' || $type.val() === ''|| $points.val() === ''){
                $alert.html('<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '<strong>Complete los campos para agregar una cuenta</strong>' +
                    '</div>');
            } else if ($points.val() <= 0) {
                $alert.html('<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '<strong>Ingrese puntos mayor a 0</strong>' +
                    '</div>');
            } else if ($type.val() === 'Quiniela' && $number.val().length !== 2) {
                $alert.html('<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '<strong>Ingrese un número de 2 dígitos para la jugada Quiniela</strong>' +
                    '</div>');
            } else if ($type.val() === 'Pale' && $number.val().length !== 4) {
                $alert.html('<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '<strong>Ingrese un número de 4 dígitos para la jugada Pale</strong>' +
                    '</div>');
            } else if ($type.val() === 'Tripleta' && $number.val().length !== 6) {
                $alert.html('<div class="alert alert-danger alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '<strong>Ingrese un número de 6 dígitos para la jugada Tripleta</strong>' +
                    '</div>');
            } else {
                $alert.html('');

                $tBody.append('<tr>\n' +
                    '<td>'+$type.val()+'<input type="hidden" name="type[]" value="'+$type.val()+'"></td>\n' +
                    '<td>'+$number.val()+'<input type="hidden" name="number[]" value="'+$number.val()+'"></td>\n' +
                    '<td>'+$points.val()+'<input type="hidden" name="points[]" value="'+$points.val()+'"></td>\n' +
                    '<td>\n' +
                    '<button class="btn btn-sm btn-danger" type="button" title="Eliminar"\n' +
                    'data-delete="">\n' +
                    '<i class="fa fa-trash o"></i>\n' +
                    '</button>\n' +
                    '</td>\n' +
                    '</tr>');

                $number.val('');
                $type.removeAttr('selected');
                $points.val('');
            }
        }
    </script>
    <script>
        $(document).on('click', '[data-register]', function () {
            let urlRegister = $(this).data('register');
            let $tbody = $('#tbody');
            let $trs = $tbody.find($('tr'));
            let $token = @json($tokenResult->accessToken);

            let lotteries = $('[name="lotteries[]"]:checked').map(function(){
                return this.value;
            }).get();

            const plays = [];

            $.each( $trs, function( key, tr ) {
                let play = {
                    type : $(tr).find('[name="type[]"]').val(),
                    number : $(tr).find('[name="number[]"]').val(),
                    points : $(tr).find('[name="points[]"]').val(),
                };

                plays.push(play);
            });

            $(this).prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: urlRegister,
                headers: {'Authorization': 'Bearer '+$token},
                data:{
                    '_token':$('input[name=_token]').val(),
                    'lotteries':lotteries,
                    'plays':plays,
                },
                success:function (data){
                    if(!data.success) {
                        $('#general-alert').html('<div class="alert alert-danger alert-dismissable">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                            '<strong>'+data.error_message+'</strong>' +
                            '</div>');

                        $('[data-register]').prop('disabled', false);
                    } else {
                        window.location.href = "/home";
                    }

                }
            });
        });
    </script>
@endsection
