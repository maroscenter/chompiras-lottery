<div class="table-responsive">
    @csrf
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
                <td>{{ $ticket->code }}</td>
                <td>{{ $ticket->user->name }}</td>
                <td>
                    @foreach($ticket->lotteries as $lottery)
                        {{ $lottery->name }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </td>
                <td>{{ $ticket->created_at }}</td>
                <td>
                    <a href="{{ url('ticket/'.$ticket->id) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-list-ul"></i>
                        Ver detalles
                    </a>
                    @if($ticket->available_delete)
                        <button data-delete="{{ url('api/tickets/'.$ticket->id.'/delete') }}" class="btn btn-danger btn-sm">
                            <i class="fa fa-times"></i>
                            Anular
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

