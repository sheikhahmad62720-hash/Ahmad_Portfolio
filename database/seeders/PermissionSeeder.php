<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage_users', 'description' => 'Create, edit, delete users'],
            ['name' => 'Manage Projects', 'slug' => 'manage_projects', 'description' => 'Full project management'],
            ['name' => 'Manage Blogs', 'slug' => 'manage_blogs', 'description' => 'Full blog management'],
            ['name' => 'Manage Skills', 'slug' => 'manage_skills', 'description' => 'Manage skills section'],
            ['name' => 'Manage Services', 'slug' => 'manage_services', 'description' => 'Manage services offered'],
            ['name' => 'Manage Media', 'slug' => 'manage_media', 'description' => 'Upload and manage media'],
            ['name' => 'Manage Settings', 'slug' => 'manage_settings', 'description' => 'Access system settings'],
            ['name' => 'View Analytics', 'slug' => 'view_analytics', 'description' => 'View analytics dashboard'],
            ['name' => 'Manage AI', 'slug' => 'manage_ai', 'description' => 'Access AI features'],
            ['name' => 'View Client Portal', 'slug' => 'view_client_portal', 'description' => 'Access client portal'],
            ['name' => 'Manage Messages', 'slug' => 'manage_messages', 'description' => 'View and reply to messages'],
            ['name' => 'Manage Newsletter', 'slug' => 'manage_newsletter', 'description' => 'Manage email subscribers'],
            ['name' => 'Manage Testimonials', 'slug' => 'manage_testimonials', 'description' => 'Manage client testimonials'],
            ['name' => 'Manage Certificates', 'slug' => 'manage_certificates', 'description' => 'Manage certificates'],
        ];

        foreach ($permissions as $permission) {
            $permission['created_at'] = now();
            $permission['updated_at'] = now();
            DB::table('permissions')->insert($permission);
        }

        // Assign all permissions to Admin role
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');
        $permissionIds = DB::table('permissions')->pluck('id');

        foreach ($permissionIds as $permissionId) {
            DB::table('role_permission')->insert([
                'role_id' => $adminRoleId,
                'permission_id' => $permissionId,
                'created_at' => now(),
            ]);
        }

        // Assign client portal permission to Client role
        $clientRoleId = DB::table('roles')->where('slug', 'client')->value('id');
        $clientPermissionId = DB::table('permissions')->where('slug', 'view_client_portal')->value('id');
        
        DB::table('role_permission')->insert([
            'role_id' => $clientRoleId,
            'permission_id' => $clientPermissionId,
            'created_at' => now(),
        ]);
    }
}
