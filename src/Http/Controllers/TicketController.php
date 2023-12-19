<?php
namespace Netweb\Tickets\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Netweb\Tickets\Services\TicketService;


class TicketController extends Controller
{

    public function ticketGenerate(Request $request) {
        return (new TicketService)->ticketGenerate($request);
    }
    public function tickets(){
        return (new TicketService)->tickets();
    }
    public function viewTickets(){
        return (new TicketService)->viewTickets();
    }
    public function ticketDetail($id){
        return (new TicketService)->ticketDetail($id);
    }
    public function ticketComments(Request $request){
        return (new TicketService)->ticketComment($request);
    }
    public function ticketsPagination(Request $request){
        return (new TicketService)->ticketsPagination($request);
    }
}

?>
