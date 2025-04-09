<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'admin'])
            ->latest()
            ->paginate(10);

        return view('admin.support.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        return view('admin.support.show', compact('ticket'));
    }

    public function update(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'response' => 'required|string|max:2000',
            'status' => 'required|in:answered,closed'
        ]);

        $ticket->update([
            'admin_response' => $request->response,
            'status' => $request->status,
            'admin_id' => Auth::id()
        ]);

        // Send notification to user
        // $ticket->user->notify(new SupportTicketResponse($ticket));

        return redirect()->route('admin.support.index')
            ->with('success', 'Response submitted successfully');
    }
}
