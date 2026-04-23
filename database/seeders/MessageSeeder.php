<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Message::create([
            'sender_id' => 3,
            'receiver_id' => 2,
            'content' => 'Hello Admin, this is a message from Froni.',
        ]);
    }
}
