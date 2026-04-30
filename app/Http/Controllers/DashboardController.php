<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Message;
use App\Models\UserKey;
use App\Services\DiffieHellmanService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private DiffieHellmanService $dh) {}

    public function index()
    {
        // Auto-generate DH key pair jika user belum punya
        if (!UserKey::where('user_id', auth()->id())->exists()) {
            $keyPair = $this->dh->generateKeyPair();
            UserKey::create([
                'user_id'     => auth()->id(),
                'public_key'  => $keyPair['public_key'],
                'private_key' => encrypt($keyPair['private_key']),
            ]);
        }

        $users = User::where('id', '!=', auth()->id())->get();
        return view('dashboard.index', compact('users'));
    }

    public function getMessages($userId)
    {
        $myKey    = UserKey::where('user_id', auth()->id())->first();
        $otherKey = UserKey::where('user_id', $userId)->first();

        // Jika salah satu belum punya key, kembalikan array kosong
        if (!$myKey || !$otherKey) {
            return response()->json([]);
        }

        // Hitung shared secret lalu derive AES key
        $aesKey = $this->dh->computeSharedSecret(
            decrypt($myKey->private_key),
            $otherKey->public_key
        );

        $messages = Message::where(function ($query) use ($userId) {
            $query->where('receiver_id', auth()->id())
                  ->where('sender_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $userId);
        })->oldest()->get();

        $messages->transform(function ($message) use ($aesKey) {
            try {
                $message->content = $message->iv
                    ? $this->dh->decrypt($message->content, $aesKey, $message->iv)
                    : '[Unable to decrypt message]';
            } catch (\Exception $e) {
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
            'message'     => 'required|string',
        ]);

        $myKey       = UserKey::where('user_id', auth()->id())->firstOrFail();
        $receiverKey = UserKey::where('user_id', $request->receiver_id)->firstOrFail();

        $aesKey    = $this->dh->computeSharedSecret(
            decrypt($myKey->private_key),
            $receiverKey->public_key
        );
        $encrypted = $this->dh->encrypt($request->message, $aesKey);

        $message = Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content'     => $encrypted['content'],
            'iv'          => $encrypted['iv'],
        ]);

        // ✅ Harus return JSON karena dikirim via AJAX fetch()
        return response()->json(['status' => 'sent', 'id' => $message->id]);
    }
}