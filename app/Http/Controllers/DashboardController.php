<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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
        $messages->transform(function ($group) {
            return $group->map(function ($message) {
                try {
                    $message->content = Crypt::decrypt($message->content);
                } catch (DecryptException $e) {
                    $message->content = '[Unable to decrypt message]';
                }
                return $message;
            });
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
            'content' => Crypt::encrypt($request->message),
        ]);

        return redirect()->route('chat');
    }
}
