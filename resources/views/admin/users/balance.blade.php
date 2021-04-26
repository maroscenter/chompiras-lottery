@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-7">
                        <h5>Balance de cuenta</h5>
                    </div>
                    <div class="col-sm-5">
                        <div class="float-right">
                            <a href="{{ url('users') }}" class="btn btn-sm btn-secondary">
                                <i class="fa fa-rotate-left"></i>
                                Regresar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <h2>$ {{ number_format($user->balance, 2, '.', '') }}</h2>
            </div>
        </div>
        @if($user->balance != 0)
            <form action="" method="POST">
                @csrf
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-7">
                                <h5><i class="fa fa-dollar"></i> Pagar al {{ $user->balance > 0 ? 'vendedor' : 'administrador' }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="alert"></div>
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <input type="hidden" id="type" name="type" value="{{ $user->balance > 0 ? 2 : 1 }}">
                                    <input type="number" step="any" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <button type="button" class="btn btn-sm btn-secondary" data-amount="{{ abs($user->balance) }}" id="btnComplete">
                                    Completar con el total a pagar
                                </button>
                            </div>
                        </div>
                        <button type="button" data-register="{{ url('api/balance/'.$user->id) }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-save"></i>
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        @endif
        
        <div class="card">
            <div class="card-header">
                Lista de movimientos
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->movement_histories as $movementHistory)
                            <tr>
                                <td>{{ $movementHistory->description }}</td>
                                <td>{{ $movementHistory->amount }}</td>
                                <td>{{ $movementHistory->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('click', '[data-amount]', function () {
            let amount = $(this).data('amount');
            $('#amount').val(amount);
        });

    </script>
    <script>
        $(document).on('click', '[data-register]', function () {
            let urlRegister = $(this).data('register');
            let $amount = $('#amount');
            let $type = $('#type');
            let $token = @json($tokenResult->accessToken);

            $(this).prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: urlRegister,
                headers: {'Authorization': 'Bearer '+$token},
                data:{
                    '_token':$('input[name=_token]').val(),
                    'amount':$amount.val(),
                    'type':$type.val(),
                },
                success:function (data){
                    if(!data.success) {
                        $('#alert').html('<div class="alert alert-danger alert-dismissable">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\n' +
                            '<strong>'+data.error_message+'</strong>' +
                            '</div>');

                        $('[data-register]').prop('disabled', false);
                    } else {
                        window.location.href = "/users";
                    }

                }
            });
        });
    </script>
@endsection
