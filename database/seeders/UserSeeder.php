<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');

        DB::table('users')->insert([
            'name' => 'Muhammad Ahmad',
            'email' => 'sheikhahmad62720@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('ahmad@720'),
            'role_id' => $adminRoleId,
            'bio' => 'Vue.js & Laravel Developer at NBTHUB. Passionate Full Stack Web Developer specializing in building modern, scalable web applications.',
            'phone' => '+92 331 6918426',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
