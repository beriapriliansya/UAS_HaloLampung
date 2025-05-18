<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'adminparawisata@gmail.com',
            'password' => bcrypt('parawisata123'), // Ganti dengan password aman
            'is_admin' => true
        ]);
    }
}

