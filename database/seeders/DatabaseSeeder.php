<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = [
            'name' => 'Ezyjump Admin',
            'email' => 'ezyjumpitsolutions@gmail.com',
            'type' => 'admin',
            'password' => Hash::make('admin'),
        ];
        if (app()->isProduction()) {
            $user['password'] = Hash::make('nuvdi2-rygbiv-fYvnit');
        }
        User::create($user);
    }
}
