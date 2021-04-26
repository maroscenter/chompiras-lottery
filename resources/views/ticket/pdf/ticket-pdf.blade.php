<!DOCTYPE html>
<html>
<head>
    <title>{{ $ticket->id }}.pdf</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        @page {
            size: 8.5cm @json($height)cm;
            margin: 0 !important;
        }
        body {
            margin: 0;
            font-family: "Nunito", sans-serif !important;
            font-size: 85%;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            text-transform: uppercase;
        }

        table, th, td {
            border: 1px solid black;
            vertical-align: center;
        }

        th {
            vertical-align: center;
            text-align: center;
            padding: 4px;
        }
        td {
            height: 5px;
            vertical-align: center;
            text-align: center;
            padding: 4px;
        }

        .text-left {
            text-align: left !important;
        }
    </style>
</head>
<body>
<div style="width: 100%; margin: 0 auto; padding: 10px">
    <h3>{{ $ticket->user->name }}</h3>
    <p>
        <b>Lotería{{ $ticket->lotteries()->count() > 1 ? 's' : '' }}:</b>
        @foreach($ticket->lotteries as $lottery)
            {{ $lottery->name }}{{ !$loop->last ? ', ' : '' }}
        @endforeach
    </p>
    <p>
        <b>Ticket:</b>
        {{ $ticket->id }}
        @if($ticket->winner_numbers)
            - Números ganadores: {{ $ticket->winner_numbers }}
        @endif
    </p>
    <p>
        <b>Fecha Compra:</b>
        {{ $ticket->created_at }}
    </p>
    {{--<table style="margin-bottom: 5px">--}}
        {{--<tbody>--}}
        {{--<tr>--}}
            {{--<td style="font-weight: 600; width: 40%;">Lotería{{ $ticket->lotteries->count() > 1 ? 's' : '' }}:</td>--}}
            {{--<td class="text-left">--}}
                {{--@foreach($ticket->lotteries as $lottery)--}}
                    {{--{{ $lottery->name }}{{ !$loop->last ? ', ' : '' }}--}}
                {{--@endforeach--}}
            {{--</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<td style="font-weight: 600">Ticket:</td>--}}
            {{--<td class="text-left">{{ $ticket->id }}</td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
            {{--<td style="font-weight: 600;">Fecha Compra:</td>--}}
            {{--<td class="text-left">{{ $ticket->created_at }}</td>--}}
        {{--</tr>--}}
        {{--</tbody>--}}
    {{--</table>--}}
    <table>
        <thead>
        <tr>
            <th style="width: 20%">Tipo</th>
            <th style="width: 20%">Num</th>
            <th style="width: 20%">Ptos</th>
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
    <p>
        <b>Total:</b>
        $ {{ $ticket->sum_points }}
    </p>
    <p>
        Compruebe su ticket
    </p>
</div>
</body>
</html>
