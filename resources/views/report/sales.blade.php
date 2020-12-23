@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-3">
            <div class="card-header">
                Reportes
            </div>
            <div class="card-body">
                <p>Ventas por rango</p>

                <p>Seleccione el rango de fechas:</p>
                <form class="form-inline" role="search">
                    <div class="form-row">
                        <div class="col">
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate ?? date('Y-m-d') }}">
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" id="ending_date" name="ending_date" value="{{ $endingDate ?? date('Y-m-d') }}">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                </form>
                {{--<div class="row text-center">--}}
                {{--<div class="col-md-4">--}}

                {{--<a href="/users" class="btn btn-primary">--}}
                {{--Consultar tickets por vendedor--}}
                {{--<span class="glyphicon glyphicon-user"></span>--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                {{--<a href="/lotteries" class="btn btn-primary">--}}
                {{--Consultar tickets por lotería--}}
                {{--<span class="glyphicon glyphicon-star"></span>--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                {{--<a href="/dates" class="btn btn-primary">--}}
                {{--Consultar tickets por fecha--}}
                {{--<span class="glyphicon glyphicon-calendar"></span>--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Tickets vendidos
            </div>
            <div class="card-body">
                <p>A continuación, un listado de las últimos tickets vendidos.</p>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Nro Ticket</th>
                        <th>Vendedor</th>
                        <th>Loterías</th>
                        <th>Fecha Compra</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->user->name }}</td>
                            <td>
                                @foreach($ticket->lotteries as $lottery)
                                    {{ $lottery->name }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td>{{ $ticket->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
