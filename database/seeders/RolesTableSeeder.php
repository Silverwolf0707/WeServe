<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'Admin',
            ],
            [
                'id'    => 2,
                'title' => 'CSWD Office',
            ],
            [
                'id'    => 3,
                'title' => 'Mayors Office',
            ],
            [
                'id'    => 4,
                'title' => 'Budget Office',
            ],
            [
                'id'    => 5,
                'title' => 'Accounting Office',
            ],
            [
                'id'    => 6,
                'title' => 'Treasury Office',
            ],
        ];

        Role::insert($roles);
    }
}
