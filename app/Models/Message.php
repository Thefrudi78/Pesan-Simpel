<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'content', 'is_encrypted', 'iv'];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
