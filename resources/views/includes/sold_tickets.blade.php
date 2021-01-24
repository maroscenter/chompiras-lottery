<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nro Ticket</th>
            <th>Vendedor</th>
            <th>Loterías</th>
            <th>Fecha Compra</th>
            <th>Opciones</th>
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
                <td>
                    <a href="/ticket/{{ $ticket->id }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-list-ul"></i>
                        Ver detalles
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

