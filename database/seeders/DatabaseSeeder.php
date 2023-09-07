<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Ezyjump Admin', 'email' => 'ezyjumpitsolutions@gmail.com', 'password' => Hash::make('nuvdi2-rygbiv-fYvnit'), 'tenant_id' => 'admin',]);
    }
}
