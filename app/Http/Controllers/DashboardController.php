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
        $users = User::all();
        $users = User::where('id', '!=', auth()->id())->get();

        $messages = Message::where('receiver_id', auth()->id())
            ->latest()
            ->get()
            ->groupBy('sender_id');
            //dd($messages);
        return view('dashboard.index', compact('users', 'messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);
        dd($request->all());

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        return redirect()->route('chat');
    }
}
