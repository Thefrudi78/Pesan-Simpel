<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\DiffieHellmanService;
use App\Models\UserKey;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function __construct(private DiffieHellmanService $dh) {}

    public function run()
    {
        User::create([
            'name' => 'Froni',
            'email' => 'froni@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Default password
            'remember_token' => Str::random(10),
            'key' => Crypt::encryptString(Str::random(32)), // Add a random key for encryption
        ]);
        User::create([
            'name' => 'Martin',
            'email' => 'Martin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Default password
            'remember_token' => Str::random(10),
            'key' => Crypt::encryptString(Str::random(32)), // Add a random key for encryption
        ]);
        User::create([
            'name' => 'Dimas',
            'email' => 'Dimas@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Default password
            'remember_token' => Str::random(10),
            'key' => Crypt::encryptString(Str::random(32)),
        ]);
        User::create([
            'name' => 'Iki',
            'email' => 'iki@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'key' => Crypt::encryptString(Str::random(32)),
        ]);
        User::create([
            'name' => 'Jeki',
            'email' => 'jeki@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'key' => Crypt::encryptString(Str::random(32)),
        ]);
        User::create([
            'name' => 'Rudi',
            'email' => 'rudi@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'key' => Crypt::encryptString(Str::random(32)),
        ]);

        $users = User::all();
        foreach ($users as $user) {
            $keyPair = $this->dh->generateKeyPair();
            UserKey::create([
                'user_id'     => $user->id,
                'public_key'  => $keyPair['public_key'],
                'private_key' => encrypt($keyPair['private_key']),
            ]);
    }
    }
}