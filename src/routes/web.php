<?php

use Illuminate\Support\Facades\Route;
use Netweb\Tickets\Http\Controllers\TicketController;
Route::get('tickets/create',[TicketController::class,'tickets'])->name('tickets.create');
Route::post('ticket/store',[TicketController::class,'ticketGenerate'])->name('tickets.store');
Route::get('tickets',[TicketController::class,'viewTickets'])->name('tickets.index');
Route::get('tickets/{id}/show',[TicketController::class,'ticketDetail'])->name('tickets.show');
Route::post('tickets/comments',[TicketController::class,'ticketComments'])->name('tickets.comments');
Route::post('tickets/pagination',[TicketController::class,'ticketsPagination'])->name('tickets.pagination');
?>
