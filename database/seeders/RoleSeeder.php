<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full system access with all permissions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Client',
                'slug' => 'client',
                'description' => 'Client portal access for project management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guest',
                'slug' => 'guest',
                'description' => 'Limited public access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('roles')->upsert($roles, ['slug'], ['name', 'description', 'updated_at']);
    }
}
