<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();

        $messages = Message::where(function ($query) {
            $query->where('receiver_id', auth()->id())
                  ->orWhere('sender_id', auth()->id());
        })
        ->oldest()
        ->get()
        ->groupBy(function ($message) {
            return $message->sender_id === auth()->id() ? $message->receiver_id : $message->sender_id;
        });

        return view('dashboard.index', compact('users', 'messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);
        //dd($request->all());
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->message,
        ]);

        return redirect()->route('chat');
    }
}
