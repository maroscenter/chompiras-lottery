<?php

namespace App\Http\Controllers;

use App\Ticket;
use Illuminate\Http\Request;
//use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);

        return view('ticket.show')->with(compact('ticket'));
    }

//    public function excel($id)
//    {
//        $ticket = Ticket::findOrFail($id);
//
//        $createdDate = $ticket->created_at->format('d/m/Y') ;
//        $titleFile = 'Jugadas ' . $createdDate;
//
//        Excel::create($titleFile, function($excel) use ($ticket, $createdDate) {
//            $excel->sheet('Datos', function($sheet) use ($ticket, $createdDate) {
//                // Header
//                $headerTitle = 'Jugadas vendidas por el usuario ' . $ticket->user->name . ' el día ' . $createdDate;
//                $sheet->mergeCells('A1:F1');
//                $sheet->row(1, [$headerTitle]);
//                $sheet->row(2, ['Número', 'Puntos', 'Tipo', 'Lotería']);
//
//                // Data
//                foreach ($ticket->plays as $play) {
//                    $row = [];
//                    $row[0] = $play->number;
//                    $row[1] = $play->point;
//                    $row[2] = $play->type;
//                    $row[3] = $play->lottery->name;
//
//                    $sheet->appendRow($row);
//                }
//            });
//        })->export('xls');
//    }

    public function pdf($id)
    {
        $ticket = Ticket::findOrFail($id);

        $pdf = PDF::loadView('ticket.pdf.ticket-pdf', ['ticket' =>$ticket]);

        return $pdf->stream($ticket->id.'.pdf');
    }
}
