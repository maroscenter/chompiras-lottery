@extends('layouts.app')

@section('styles')
    <link href="{{ asset('/plugins/select2/dist/css/select2.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/plugins/select2/dist/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="container">
        <form action="" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-7">
                            <h5>Editar límite de ventas <b>(GLOBAL)</b></h5>
                        </div>
                        <div class="col-sm-5">
                            <div class="float-right">
                                <a href="{{ url('home') }}" class="btn btn-sm btn-secondary">
                                    <i class="fa fa-rotate-left"></i>
                                    Regresar
                                </a>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fa fa-save"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($limits as $key => $limit)
                        <h5>Límite {{ $key == 0 ? 'Global' : 'Individual' }}</h5>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <input type="hidden" value="{{ $limit->id }}" name="limit_ids[]">
                                    <label for="quiniela{{$limit->id}}">Quiniela</label>
                                    <input type="number" min="0" class="form-control" id="quiniela{{$limit->id}}" placeholder="" name="quiniela[]" value="{{ $limit->quiniela }}" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="pale{{$limit->id}}">Palé</label>
                                    <input type="number" min="0" class="form-control" id="pale{{$limit->id}}" placeholder="" name="pale[]" value="{{ $limit->pale }}" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="super_pale{{$limit->id}}">Super Palé</label>
                                    <input type="number" min="0" class="form-control" id="super_pale{{$limit->id}}" placeholder="" name="super_pale[]" value="{{ $limit->super_pale }}" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="tripleta{{$limit->id}}">Tripleta</label>
                                    <input type="number" min="0" class="form-control" id="tripleta{{$limit->id}}" placeholder="" name="tripleta[]" value="{{ $limit->tripleta }}" required>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <h5>Límites por vendedor</h5>
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <input type="hidden" class="select2 form-control" id="sellerId">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Vendedor</th>
                                <th>Quiniela</th>
                                <th>Palé</th>
                                <th>Super Palé</th>
                                <th>Tripleta</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="content">
                            @foreach($salesLimits as $salesLimit)
                                <tr>
                                    <td>
                                        <p class="form-control-static">{{ $salesLimit->user->name }}</p>
                                        <input type="hidden" class="seller_ids" name="seller_ids[]" value="{{ $salesLimit->user_id }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control quiniela" name="quiniela_seller[]" value="{{ $salesLimit->quiniela }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control pale" name="pale_seller[]" value="{{ $salesLimit->pale }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control super_pale" name="super_pale_seller[]" value="{{ $salesLimit->super_pale }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control tripleta" name="tripleta_seller[]" value="{{ $salesLimit->tripleta }}" readonly>
                                    </td>
                                    <td>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" data-checked> Personalizado
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-delete><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Límite de venta por <b>tipo de jugada y número</b></h5>
                </div>
                <div class="card-body">
                    <h5>Límite global</h5>
                    <button type="button" data-add="global" class="btn btn-sm btn-success mb-3"><i class="fa fa-plus"></i> Agregar</button>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Número</th>
                                <th>Puntos</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="globalContent">
                            @foreach($globalPlayLimits as $globalPlayLimit)
                                <tr>
                                    <td>
                                        <input type="hidden" name="global_play_limit_ids[]" value="{{ $globalPlayLimit->id }}">
                                        <input type="number" min="10" class="form-control" name="global_numbers[]" value="{{ $globalPlayLimit->number }}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="10" class="form-control" name="global_points[]" value="{{ $globalPlayLimit->points }}" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-remove><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h5>Límite por vendedor</h5>
                    <button type="button" data-add="seller" class="btn btn-sm btn-success mb-3"><i class="fa fa-plus"></i> Agregar</button>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Número</th>
                                <th>Puntos</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="sellerContent">
                            @foreach($sellerPlayLimits as $sellerPlayLimit)
                                <tr>
                                    <td>
                                        <input type="hidden" name="seller_play_limit_ids[]" value="{{ $sellerPlayLimit->id }}">
                                        <input type="number" min="10" class="form-control" name="seller_numbers[]" value="{{ $sellerPlayLimit->number }}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="1" class="form-control" name="seller_points[]" value="{{ $sellerPlayLimit->points }}" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-remove><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h5>Límite por ticket</h5>
                    <button type="button" data-add="ticket" class="btn btn-sm btn-success mb-3"><i class="fa fa-plus"></i> Agregar</button>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Número</th>
                                <th>Puntos</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="ticketContent">
                            @foreach($ticketPlayLimits as $ticketPlayLimit)
                                <tr>
                                    <td>
                                        <input type="hidden" name="ticket_play_limit_ids[]" value="{{ $ticketPlayLimit->id }}">
                                        <input type="number" min="10" class="form-control" name="ticket_numbers[]" value="{{ $ticketPlayLimit->number }}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="1" class="form-control" name="ticket_points[]" value="{{ $ticketPlayLimit->points }}" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-remove><i class="fa fa-trash"></i></button>
                                    </td>
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

    <script src="{{ asset('/plugins/select2/dist/js/select2.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#sellerId').select2({
                placeholder: 'Seleccione el vendedor',
                ajax: {
                    url: '/api/sellers',
                    dataType: 'json',
                    delay: 250,

                    data: function (query) {
                        return {
                            queryText: query
                        };
                    },

                    results: function (data) {
                        return {
                            results: data
                        };
                    },

                    cache: true
                }
            });
        });
    </script>
    <script>
        $('#sellerId').on('change', function() {
            const Id = $(this).val();

            if (!$('#content').find(':input[value="'+Id+'"]').val()) {
                $.ajax({
                    type: 'GET',
                    url: '/api/sellers/'+Id,

                    success:function (user){
                        let quiniela, pale, super_pale, tripleta;

                        let readonly = '';
                        let required = 'required';
                        let checked = 'checked';

                        let sales_limit = user.sales_limit;
                        if (sales_limit) {
                            quiniela = sales_limit.quiniela;
                            pale = sales_limit.pale;
                            super_pale = sales_limit.super_pale;
                            tripleta = sales_limit.tripleta;
                            required = '';
                            checked = '';
                            readonly = 'readOnly';
                        }

                        let html = '<tr>\n' +
                            '<td>\n' +
                            '<p class="form-control-static">'+user.name+'</p>\n' +
                            '<input type="hidden" class="seller_ids" name="seller_ids[]" value="'+user.id+'">\n' +
                            '</td>\n' +
                            '<td>\n' +
                            '<input type="number" min="0" class="form-control quiniela" name="quiniela_seller[]" value="'+quiniela+'" '+readonly+' '+required+'>\n' +
                            '</td>\n' +
                            '<td>\n' +
                            '<input type="number" min="0" class="form-control pale" name="pale_seller[]" value="'+pale+'" '+readonly+' '+required+'>\n' +
                            '</td>\n' +
                            '<td>\n' +
                            '<input type="number" min="0" class="form-control super_pale" name="super_pale_seller[]" value="'+super_pale+'" '+readonly+' '+required+'>\n' +
                            '</td>\n' +
                            '<td>\n' +
                            '<input type="number" min="0" class="form-control tripleta" name="tripleta_seller[]" value="'+tripleta+'" '+readonly+' '+required+'>\n' +
                            '</td>\n' +
                            '<td>\n' +
                            '<div class="checkbox">\n' +
                            '<label>\n' +
                            '<input type="checkbox" data-checked '+checked+'> Personalizado\n' +
                            '</label>\n' +
                            '</div>\n' +
                            '</td>' +
                            '<td><button type="button" class="btn btn-danger" data-delete><i class="fa fa-trash"></i></button></td>' +
                            '</tr>';

                        $('#content').append(html);

                        $("#sellerId").select2("val", "");
                    }
                });
            } else {
                $("#sellerId").select2("val", "");
            }
        });

        $(document).on('change', '[data-checked]', function () {
            const checked = $(this).is(':checked');

            let $tr = $(this).closest('tr');

            let $quiniela = $tr.find('.quiniela');
            let $pale = $tr.find('.pale');
            let $superPale = $tr.find('.super_pale');
            let $tripleta = $tr.find('.tripleta');

            if(checked || !$quiniela.val() || !$pale.val() || !$superPale.val() || !$tripleta.val()) {
                $quiniela.attr('readOnly', false);
                $pale.attr('readOnly', false);
                $superPale.attr('readOnly', false);
                $tripleta.attr('readOnly', false);
            } else {
                $quiniela.attr('readOnly', true);
                $pale.attr('readOnly', true);
                $superPale.attr('readOnly', true);
                $tripleta.attr('readOnly', true);
            }
        });

        $(document).on('click', '[data-delete]', function () {
            let $tr = $(this).closest('tr');
            $tr.remove();
            $("#sellerId").select2("val", "");
        });
    </script>

    <script>
        $(document).on('click', '[data-add]', function () {
            let $type = $(this).data('add');

            let $content = $('#'+$type+'Content');

            let html = '<tr>\n' +
                '<td>\n' +
                '<input type="hidden" name="'+$type+'_play_limit_ids[]" value="0">' +
                '<input type="number" min="10" class="form-control" name="'+$type+'_numbers[]" value="" required>' +
                '</td>\n' +
                '<td>\n' +
                '<input type="number" min="1" class="form-control" name="'+$type+'_points[]" value="" required>\n' +
                '<td><button type="button" class="btn btn-danger" data-remove><i class="fa fa-trash"></i></button></td>' +
                '</tr>';

            $content.append(html);
        });

        $(document).on('click', '[data-remove]', function () {
            let $tr = $(this).closest('tr');
            $tr.remove();
        });
    </script>
@endsection
