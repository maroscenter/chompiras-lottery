@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="offset-sm-3 col-md-6">

                <div class="card">
                    <div class="card-header">
                        Jugadas vendidas en el ticket # {{ $ticket->code }}
                        <div class="float-right">
                            <a href="/ticket/{{ $ticket->id }}/pdf" target="_blank" class="btn btn-danger btn-sm">
                                <span class="glyphicon glyphicon-export"></span>
                                Exportar a PDF
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{--<p><b>Total de puntos vendidos:</b> {{ $ticket->total_tickets_sold }}</p>--}}

                        <div class="text-right">

                            {{--<a href="/ticket/{{ $ticket->id }}/excel" class="btn btn-success btn-sm">--}}
                                {{--<span class="glyphicon glyphicon-export"></span>--}}
                                {{--Exportar a Excel--}}
                            {{--</a>--}}
                        </div>

                        {{--<p>A continuación, las jugadas vendidas, en el ticket seleccionado.</p>--}}

                        <h4>{{ $ticket->user->name }}</h4>
                        <p>
                            <b>Lotería{{ $ticket->lotteries->count() > 1 ? 's' : '' }}:</b>
                            @foreach($ticket->lotteries as $lottery)
                                {{ $lottery->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                        <p>
                            <b>Ticket:</b>
                            {{ $ticket->code }}
                            @if($ticket->winner_numbers)
                                - Números ganadores: {{ $ticket->winner_numbers }}
                            @endif
                        </p>
                        <p>
                            <b>Fecha Compra:</b>
                            {{ $ticket->created_at }}
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Num</th>
                                    <th>Ptos</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($ticket->plays as $play)
                                    <tr>
                                        <td>{{ $play->type }}</td>
                                        <td>{{ $play->number }}</td>
                                        <td>{{ $play->points }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <p>
                            <b>Total:</b>
                            $ {{ $ticket->sum_points }}
                        </p>
                        <p>
                            Compruebe su ticket
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
