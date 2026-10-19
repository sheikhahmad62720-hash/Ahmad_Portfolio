<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'Muhammad Ahmad - Portfolio', 'type' => 'string', 'group_name' => 'general', 'is_public' => true, 'description' => 'Website name'],
            ['key' => 'site_description', 'value' => 'Full Stack Developer specializing in Laravel & Vue.js at NBTHUB', 'type' => 'string', 'group_name' => 'general', 'is_public' => true, 'description' => 'Site description'],
            ['key' => 'site_tagline', 'value' => 'Building modern web applications with passion', 'type' => 'string', 'group_name' => 'general', 'is_public' => true, 'description' => 'Site tagline'],
            
            // Contact Settings
            ['key' => 'contact_email', 'value' => 'contact@muhammadahmad.dev', 'type' => 'string', 'group_name' => 'contact', 'is_public' => true, 'description' => 'Contact email'],
            ['key' => 'contact_phone', 'value' => '+92 300 1234567', 'type' => 'string', 'group_name' => 'contact', 'is_public' => true, 'description' => 'Contact phone'],
            ['key' => 'contact_address', 'value' => 'Lahore, Pakistan', 'type' => 'string', 'group_name' => 'contact', 'is_public' => true, 'description' => 'Office address'],
            
            // Social Media
            ['key' => 'social_github', 'value' => 'https://github.com/muhammadahmad', 'type' => 'string', 'group_name' => 'social', 'is_public' => true, 'description' => 'GitHub profile'],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/in/muhammadahmad', 'type' => 'string', 'group_name' => 'social', 'is_public' => true, 'description' => 'LinkedIn profile'],
            ['key' => 'social_facebook', 'value' => '', 'type' => 'string', 'group_name' => 'social', 'is_public' => true, 'description' => 'Facebook profile'],
            ['key' => 'social_instagram', 'value' => '', 'type' => 'string', 'group_name' => 'social', 'is_public' => true, 'description' => 'Instagram profile'],
            ['key' => 'social_twitter', 'value' => '', 'type' => 'string', 'group_name' => 'social', 'is_public' => true, 'description' => 'Twitter profile'],
            
            // System Settings
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group_name' => 'system', 'is_public' => false, 'description' => 'Enable maintenance mode'],
            ['key' => 'ai_chatbot_enabled', 'value' => 'true', 'type' => 'boolean', 'group_name' => 'ai', 'is_public' => true, 'description' => 'Enable AI chatbot'],
            ['key' => 'items_per_page', 'value' => '12', 'type' => 'number', 'group_name' => 'system', 'is_public' => false, 'description' => 'Items per page for pagination'],
            
            // SEO Settings
            ['key' => 'seo_meta_title', 'value' => 'Muhammad Ahmad - Full Stack Developer | Laravel & Vue.js Expert', 'type' => 'string', 'group_name' => 'seo', 'is_public' => true, 'description' => 'Default meta title'],
            ['key' => 'seo_meta_description', 'value' => 'Professional Full Stack Developer specializing in Laravel and Vue.js. Building modern, scalable web applications.', 'type' => 'string', 'group_name' => 'seo', 'is_public' => true, 'description' => 'Default meta description'],
            ['key' => 'seo_meta_keywords', 'value' => 'Laravel Developer, Vue.js Developer, Full Stack Developer, Web Development, PHP Developer', 'type' => 'string', 'group_name' => 'seo', 'is_public' => true, 'description' => 'Default meta keywords'],
        ];

        foreach ($settings as $setting) {
            $setting['created_at'] = now();
            $setting['updated_at'] = now();
            DB::table('settings')->insertOrIgnore($setting);
        }
    }
}
