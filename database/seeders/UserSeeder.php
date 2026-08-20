<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Define users to create
        $users = [
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@prodi.ac.id',
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Admin Prodi',
                'email' => 'admin@prodi.ac.id',
                'role' => 'Admin Prodi',
            ],
            [
                'name' => 'Editor Konten',
                'email' => 'editor@prodi.ac.id',
                'role' => 'Editor Konten',
            ],
            [
                'name' => 'Dosen Pengajar',
                'email' => 'dosen@prodi.ac.id',
                'role' => 'Dosen',
            ],
            [
                'name' => 'Operator Akademik',
                'email' => 'operator@prodi.ac.id',
                'role' => 'Operator Akademik',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::withTrashed()->updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'deleted_at' => null,
                ]
            );

            // Sync role (removes existing roles and adds the new one)
            $user->syncRoles($userData['role']);
        }
    }
}
