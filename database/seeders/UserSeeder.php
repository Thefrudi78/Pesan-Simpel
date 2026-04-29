<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Default password
            'remember_token' => Str::random(10),
            'key' => Crypt::encryptString(Str::random(32)), // Add a random key for encryption
        ]);
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
    }
}