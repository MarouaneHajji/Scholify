<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create a default Super admin account
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin.2003@gmail.com',
            'password' => Hash::make('superadmin.2003'), // Default password
            'role' => 'super_admin', // Set role to 'super_admin'
        ]);

    }
}
