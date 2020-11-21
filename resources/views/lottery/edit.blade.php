@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-7">
                            <h5>Editar lotería (<b>{{ $lottery->name }}</b>)</h5>
                        </div>
                        <div class="col-sm-5">
                            <div class="float-right">
                                <a href="{{ url('lotteries') }}" class="btn btn-sm btn-secondary">
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                    Regresar
                                </a>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <span class="glyphicon glyphicon-floppy-disk"></span>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5><span class="glyphicon glyphicon-list-alt"></span> Datos básicos</h5>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Nombre lotería</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name" value="{{ old('name', $lottery->name) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="abbreviated">Abreviado</label>
                                <input type="text" class="form-control" id="abbreviated" placeholder="" name="abbreviated" value="{{ old('abbreviated', $lottery->abbreviated) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="code">Código</label>
                                <input type="number" class="form-control" id="code" placeholder="" name="code" value="{{ old('code', $lottery->code) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="status">Estatus</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="0" {{ $lottery->status == 0 ? 'selected' : '' }}>Inactivo</option>
                                    <option value="1" {{ $lottery->status == 1 ? 'selected' : '' }}>Activo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h5><span class="glyphicon glyphicon-time"></span> Horario de cierre</h5>
                    <div class="row">
                        @foreach($lottery->closing_times as $closingTime)
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="monday">{{ $closingTime->title }}</label>
                                    <input type="time" class="form-control" id="monday" name="times[]" value="{{ $closingTime->time }}" required>
                                    <input type="hidden" name="closing_time_ids[]" value="{{ $closingTime->id }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-sm-offset-4 col-sm-4">
                            <h5><span class="glyphicon glyphicon-calendar"></span> Dias inactivos</h5>
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
                                    @foreach($lottery->inactive_dates as $inactiveDate)
                                        <tr>
                                            <td>
                                                {{ $inactiveDate->date }}
                                                <input type="hidden" name="inactive_dates[]" value="{{ $inactiveDate->date }}">
                                            </td>
                                            <td class="w-25 text-center">
                                                <button class="btn btn-sm btn-danger" type="button" title="Eliminar" data-delete="">Eliminar</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <h5><span class="glyphicon glyphicon-usd"></span> Premios</h5>
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
                            @foreach($lottery->prizes as $prize)
                                <tr>
                                    <td>
                                        {{ $prize->name }}
                                        <input type="hidden" class="form-control" name="prize_ids[]" value="{{ $prize->id }}">
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control" name="prize_first[]" placeholder="Primera" value="{{ $prize->first }}" required>
                                    </td>
                                    @if($prize->second)
                                        <td>
                                            <input type="number" min="0" class="form-control" name="prize_second[]" placeholder="Segunda" value="{{ $prize->second }}" required>
                                        </td>
                                    @endif
                                    @if($prize->third)
                                        <td>
                                            <input type="number" min="0" class="form-control" name="prize_third[]" placeholder="Tercera" value="{{ $prize->third }}" required>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
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
