<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'      => 'Super Admin',
                'email'     => 'admin@temple.org',
                'password'  => Hash::make('password'),
                'role'      => 'super_admin',
                'is_active' => true,
                'phone'     => '020 5555 0001',
            ],
            [
                'name'      => 'Admin User',
                'email'     => 'manager@temple.org',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'is_active' => true,
                'phone'     => '020 5555 0002',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(['email' => $data['email']], $data);
        }

        // Promote any existing users that don't have a role set
        User::where('role', '')->orWhereNull('role')->update(['role' => 'staff']);
    }
}
