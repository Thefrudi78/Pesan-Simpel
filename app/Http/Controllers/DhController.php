<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DiffieHellmanService;

class DhController extends Controller
{
    public function __construct(protected DiffieHellmanService $dhService) {}

    public function init(Request $request)
    {
        $recipientId = $request->input('recipient_id');
        $data = $this->dhService->initExchange(auth()->id(), $recipientId);
        return response()->json($data);
    }

    public function exchange(Request $request)
    {
        $request->validate([
            'exchange_id'       => 'required|string',
            'client_public_key' => 'required|string',
        ]);

        $recipientId = $request->input('recipient_id'); // we also need the recipient
        $this->dhService->computeSharedSecret(
            auth()->id(),
            $recipientId,
            $request->exchange_id,
            $request->client_public_key
        );

        return response()->json(['status' => 'ok']);
    }
}

