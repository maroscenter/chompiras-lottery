@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-7">
                            <h5>Registrar sorteo</h5>
                        </div>
                        <div class="col-sm-5">
                            <div class="float-right">
                                <a href="{{ url('raffles') }}" class="btn btn-sm btn-secondary">
                                    <i class="fa fa-rotate-left"></i>
                                    Regresar
                                </a>
                                <button type="button" data-register class="btn btn-sm btn-primary">
                                    <i class="fa fa-save"></i>
                                    Registrar
                                </button>
                                <input type="submit" id="btnRegister" class="d-none">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="lottery_id">Lotería</label>
                                <select name="lottery_id" id="lottery_id" class="form-control" required>
                                    <option value="">Seleccionar una opción</option>
                                    @foreach($lotteries as $lottery)
                                        <option value="{{ $lottery->id }}" {{ $lottery->id == old('lottery_id') }}>{{ $lottery->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="number_1">Número 01</label>
                                <input type="number" min="0" max="99" class="form-control" id="number_1" name="number_1" value="{{ old('number_1') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="number_2">Número 02</label>
                                <input type="number" min="0" max="99" class="form-control" id="number_2" name="number_2" value="{{ old('number_2') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="number_3">Número 03</label>
                                <input type="number" min="0" max="99" class="form-control" id="number_3" name="number_3" value="{{ old('number_3') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="datetime">Fecha y hora</label>
                                <input type="datetime-local" class="form-control" id="datetime" name="datetime" value="{{ old('datetime') }}" required>
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
        $(document).on('click', '[data-register]', function () {
            let urlDelete = $(this).data('register');

            swal({
                title: '¿Seguro que desea registrar este sorteo?',
                text: "",
                type: 'info',
                showCancelButton: true,

                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, registrar!'
            }).then((result) => {
                if (result.value) {
                    $('#btnRegister').click();
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
@endsection
