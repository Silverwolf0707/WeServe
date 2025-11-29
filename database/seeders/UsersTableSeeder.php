<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => null,
                'status'         => 'active',
                'email_verified_at'  => now(),
                'created_at'     => now(),

            ],
            [
                'id'             => 2,
                'name'           => 'CSWD Office',
                'email'          => 'cswd@weserve.gov',
                'password'       => bcrypt('12345678'),
                'remember_token' => null,
                'status'         => 'active',
                'email_verified_at'  => now(),
                'created_at'     => now(),
            ],
            [
                'id'             => 3,
                'name'           => 'Mayors Office',
                'email'          => 'mayor@weserve.gov',
                'password'       => bcrypt('12345678'),
                'remember_token' => null,
                'status'         => 'active',
                'email_verified_at'  => now(),
                'created_at'     => now(),
            ],
            [
                'id'             => 4,
                'name'           => 'Budget Office',
                'email'          => 'budget@weserve.gov',
                'password'       => bcrypt('12345678'),
                'remember_token' => null,
                'status'         => 'active',
                'email_verified_at'  => now(),
                'created_at'     => now(),
            ],
            [
                'id'             => 5,
                'name'           => 'Accounting Office',
                'email'          => 'accounting@weserve.gov',
                'password'       => bcrypt('12345678'),
                'remember_token' => null,
                'status'         => 'active',
                'email_verified_at'  => now(),
                'created_at'     => now(),
            ],
            [
                'id'             => 6,
                'name'           => 'Treasury Office',
                'email'          => 'treasury@weserve.gov',
                'password'       => bcrypt('12345678'),
                'remember_token' => null,
                'status'         => 'active',
                'email_verified_at'  => now(),
                'created_at'     => now(),
            ],
        ];

        User::insert($users);
    }
}