<?php

namespace App\Http\Controllers\EventManagement;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use App\ViewModels\ManageEventViewModel;
use Illuminate\Http\Request;

class EventPanelController extends Controller
{
    public function index($id)
    {
        $event = Event::with('eventTicketTypes.ticketType')->where('id', $id)->first();
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $ticketTypes = TicketType::orderBy('name')->get();
        $organizers = User::whereIn('role', ['organizer', 'admin'])->pluck('name', 'id');

        $ticketTypesData = $ticketTypes->map(function ($t) {
            return ['id' => (string) $t->id, 'name' => $t->name];
        })->values();

        $eventTicketTypesData = $event->eventTicketTypes->map(function ($tt) {
            return [
                'id'             => $tt->id,
                'ticket_type_id' => (string) $tt->ticket_type_id,
                'price'          => $tt->price,
                'capacity'       => $tt->capacity,
                'editMode'       => false,
                'isNew'          => false,
            ];
        })->values();

        $viewModel = new ManageEventViewModel($event, 'index', $categories, $ticketTypes, $organizers, $ticketTypesData, $eventTicketTypesData);
        return view('admin.event', $viewModel->toArray());
    }

    public function update($id, Request $request) {
        $event = Event::where('id', $id)->first();
        $event->update($request->all());

        if ($request->has('ticket_types')) {
            $event->eventTicketTypes()->delete();
            foreach($request->ticket_types as $tt) {
                if (!empty($tt['ticket_type_id']) && isset($tt['price']) && isset($tt['capacity'])) {
                    $event->eventTicketTypes()->create([
                        'ticket_type_id' => $tt['ticket_type_id'],
                        'price' => $tt['price'],
                        'capacity' => $tt['capacity'],
                    ]);
                }
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $ticketTypes = TicketType::orderBy('name')->get();
        $organizers = User::whereIn('role', ['organizer', 'admin'])->pluck('name', 'id');

        $event->load('eventTicketTypes.ticketType');

        $ticketTypesData = $ticketTypes->map(function ($t) {
            return ['id' => (string) $t->id, 'name' => $t->name];
        })->values();

        $eventTicketTypesData = $event->eventTicketTypes->map(function ($tt) {
            return [
                'id'             => $tt->id,
                'ticket_type_id' => (string) $tt->ticket_type_id,
                'price'          => $tt->price,
                'capacity'       => $tt->capacity,
                'editMode'       => false,
                'isNew'          => false,
            ];
        })->values();
        
        $viewModel = new ManageEventViewModel($event, 'index', $categories, $ticketTypes, $organizers, $ticketTypesData, $eventTicketTypesData);
        return view('admin.event', $viewModel->toArray());
    }
}
