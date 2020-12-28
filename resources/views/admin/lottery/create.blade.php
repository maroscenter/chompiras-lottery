@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-7">
                            <h5>Registrar lotería</h5>
                        </div>
                        <div class="col-sm-5">
                            <div class="float-right">
                                <a href="{{ url('lotteries') }}" class="btn btn-sm btn-secondary">
                                    <i class="fa fa-rotate-left"></i>
                                    Regresar
                                </a>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fa fa-save"></i>
                                    Registrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5><i class="fa fa-navicon"></i> Datos básicos</h5>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Nombre lotería</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="abbreviated">Abreviado</label>
                                <input type="text" class="form-control" id="abbreviated" placeholder="" name="abbreviated" value="{{ old('abbreviated') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="code">Código</label>
                                <input type="number" class="form-control" id="code" placeholder="" name="code" value="{{ old('code') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="status">Estatus</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="0">Inactivo</option>
                                    <option value="1">Activo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h5><i class="fa fa-clock-o"></i> Horario de cierre</h5>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="monday">Lunes</label>
                                <input type="time" class="form-control" id="monday" name="times[]" value="{{ old('monday') }}" required>
                                <input type="hidden" name="titles[]" value="Lunes">
                                <input type="hidden" name="time_days[]" value="Monday">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="tuesday">Martes</label>
                                <input type="time" class="form-control" id="tuesday" name="times[]" value="{{ old('tuesday') }}" required>
                                <input type="hidden" name="titles[]" value="Martes">
                                <input type="hidden" name="time_days[]" value="Tuesday">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="wednesday">Miércoles</label>
                                <input type="time" class="form-control" id="wednesday" name="times[]" value="{{ old('wednesday') }}" required>
                                <input type="hidden" name="titles[]" value="Miércoles">
                                <input type="hidden" name="time_days[]" value="Wednesday">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="thursday">Jueves</label>
                                <input type="time" class="form-control" id="thursday" name="times[]" value="{{ old('thursday') }}" required>
                                <input type="hidden" name="titles[]" value="Jueves">
                                <input type="hidden" name="time_days[]" value="Thursday">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="friday">Viernes</label>
                                <input type="time" class="form-control" id="friday" name="times[]" value="{{ old('friday') }}" required>
                                <input type="hidden" name="titles[]" value="Viernes">
                                <input type="hidden" name="time_days[]" value="Friday">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="saturday">Sábado</label>
                                <input type="time" class="form-control" id="saturday" name="times[]" value="{{ old('saturday') }}" required>
                                <input type="hidden" name="titles[]" value="Sábado">
                                <input type="hidden" name="time_days[]" value="Saturday">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="sunday">Domingo</label>
                                <input type="time" class="form-control" id="sunday" name="times[]" value="{{ old('sunday') }}" required>
                                <input type="hidden" name="titles[]" value="Domingo">
                                <input type="hidden" name="time_days[]" value="Sunday">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="offset-md-4 col-sm-4">
                            <h5><i class="fa fa-calendar-times-o"></i> Dias inactivos</h5>
                            <div id="alertInactiveDay"></div>
                            <div class="form-inline" style="margin-bottom: 15px">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="inactiveDate" value="">
                                </div>
                                <button type="button" id="btnAddInactiveDay" class="btn btn-success">Agregar</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody id="contentInactiveDays">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <h5><i class="fa fa-dollar"></i> Premios</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Primera</th>
                                <th>Segunda</th>
                                <th>Tercera</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    Quiniela
                                    <input type="hidden" class="form-control" name="prize_names[]" value="Quiniela">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="prize_first[]" placeholder="Primera" required>
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="prize_second[]" placeholder="Segunda" required>
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="prize_third[]" placeholder="Tercera" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Palé
                                    <input type="hidden" class="form-control" name="prize_names[]" value="Palé">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="prize_first[]" placeholder="Primera" required>
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="prize_second[]" placeholder="Segunda" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Tripleta
                                    <input type="hidden" class="form-control" name="prize_names[]" value="Tripleta">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="prize_first[]" placeholder="Primera" required>
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="prize_second[]" placeholder="Segunda" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Super Palé
                                    <input type="hidden" class="form-control" name="prize_names[]" value="Super Palé">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="prize_first[]" placeholder="Primera" required>
                                </td>
                            </tr>
                            </tbody>
                        </table>
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
                title: '¿Seguro que desea eliminar este día inactivo?',
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
@endsection
