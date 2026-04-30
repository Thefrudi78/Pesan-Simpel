<?php
// app/Http/Controllers/ChatController.php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\UserKey;
use App\Services\DiffieHellmanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct(private DiffieHellmanService $dh) {}

    /**
     * Generate dan simpan key pair untuk user yang login
     */
    public function generateKeys()
    {
        $keyPair = $this->dh->generateKeyPair();

        UserKey::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'public_key'  => $keyPair['public_key'],
                'private_key' => encrypt($keyPair['private_key']), // enkripsi dengan APP_KEY Laravel
            ]
        );

        return response()->json([
            'message'    => 'Key pair generated successfully',
            'public_key' => $keyPair['public_key'],
        ]);
    }

    /**
     * Kirim pesan terenkripsi
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'required|string',
        ]);

        $senderId   = Auth::id();
        $receiverId = $request->receiver_id;

        // Ambil key pair sender
        $senderKey = UserKey::where('user_id', $senderId)->firstOrFail();

        // Ambil public key receiver
        $receiverKey = UserKey::where('user_id', $receiverId)->firstOrFail();

        // Hitung shared secret & derive AES key
        $privateKey = decrypt($senderKey->private_key);
        $aesKey = $this->dh->computeSharedSecret($privateKey, $receiverKey->public_key);

        // Enkripsi pesan
        $encrypted = $this->dh->encrypt($request->message, $aesKey);

        // Simpan ke database
        $message = Message::create([
            'sender_id'    => $senderId,
            'receiver_id'  => $receiverId,
            'content'      => $encrypted['content'],
            'iv'           => $encrypted['iv'],
            'is_encrypted' => true,
        ]);

        return response()->json([
            'message' => 'Message sent',
            'data'    => $message,
        ]);
    }

    /**
     * Ambil dan dekripsi pesan
     */
    public function getMessages(int $otherUserId)
    {
        $currentUserId = Auth::id();

        // Ambil key pair user yang login
        $myKey = UserKey::where('user_id', $currentUserId)->firstOrFail();

        // Ambil public key lawan bicara
        $otherKey = UserKey::where('user_id', $otherUserId)->firstOrFail();

        // Hitung AES key
        $privateKey = decrypt($myKey->private_key);
        $aesKey = $this->dh->computeSharedSecret($privateKey, $otherKey->public_key);

        // Ambil semua pesan antara 2 user
        $messages = Message::where(function ($q) use ($currentUserId, $otherUserId) {
                $q->where('sender_id', $currentUserId)
                  ->where('receiver_id', $otherUserId);
            })
            ->orWhere(function ($q) use ($currentUserId, $otherUserId) {
                $q->where('sender_id', $otherUserId)
                  ->where('receiver_id', $currentUserId);
            })
            ->orderBy('created_at')
            ->get();

        // Dekripsi semua pesan
        $decrypted = $messages->map(function ($msg) use ($aesKey) {
            return [
                'id'          => $msg->id,
                'sender_id'   => $msg->sender_id,
                'receiver_id' => $msg->receiver_id,
                'content'     => $msg->is_encrypted
                    ? $this->dh->decrypt($msg->content, $aesKey, $msg->iv)
                    : $msg->content,
                'created_at'  => $msg->created_at,
            ];
        });

        return response()->json($decrypted);
    }
}