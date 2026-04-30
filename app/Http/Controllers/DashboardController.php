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

        return view('dashboard.index', compact('users'));
    }

    public function getMessages($userId)
    {
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('receiver_id', auth()->id())
                  ->where('sender_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $userId);
        })->oldest()->get();

        $messages->transform(function ($message) {
            try {
                $message->content = Crypt::decrypt($message->content);
            } catch (DecryptException $e) {
                $message->content = '[Unable to decrypt message]';
            }
            return $message;
        });

        return response()->json($messages);
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
