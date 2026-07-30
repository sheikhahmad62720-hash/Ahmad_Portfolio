<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class SkillTestimonialSeeder extends Seeder
{
    public function run(): void
    {
        // Skills
        $skills = [
            ['name' => 'Laravel', 'slug' => 'laravel', 'icon' => '🔴', 'proficiency_level' => 95, 'category' => 'backend', 'order_position' => 1, 'is_featured' => true],
            ['name' => 'Vue.js', 'slug' => 'vuejs', 'icon' => '🟢', 'proficiency_level' => 90, 'category' => 'frontend', 'order_position' => 2, 'is_featured' => true],
            ['name' => 'PHP', 'slug' => 'php', 'icon' => '🐘', 'proficiency_level' => 92, 'category' => 'backend', 'order_position' => 3, 'is_featured' => true],
            ['name' => 'TypeScript', 'slug' => 'typescript', 'icon' => '🔷', 'proficiency_level' => 85, 'category' => 'frontend', 'order_position' => 4, 'is_featured' => true],
            ['name' => 'MySQL', 'slug' => 'mysql', 'icon' => '🗄️', 'proficiency_level' => 88, 'category' => 'database', 'order_position' => 5, 'is_featured' => true],
            ['name' => 'Tailwind CSS', 'slug' => 'tailwind-css', 'icon' => '🎨', 'proficiency_level' => 93, 'category' => 'frontend', 'order_position' => 6, 'is_featured' => true],
            ['name' => 'Docker', 'slug' => 'docker', 'icon' => '🐳', 'proficiency_level' => 75, 'category' => 'devops', 'order_position' => 7, 'is_featured' => false],
            ['name' => 'Git', 'slug' => 'git', 'icon' => '📦', 'proficiency_level' => 90, 'category' => 'tools', 'order_position' => 8, 'is_featured' => false],
            ['name' => 'React', 'slug' => 'react', 'icon' => '⚛️', 'proficiency_level' => 78, 'category' => 'frontend', 'order_position' => 9, 'is_featured' => false],
            ['name' => 'Node.js', 'slug' => 'nodejs', 'icon' => '💚', 'proficiency_level' => 72, 'category' => 'backend', 'order_position' => 10, 'is_featured' => false],
            ['name' => 'Redis', 'slug' => 'redis', 'icon' => '🔴', 'proficiency_level' => 70, 'category' => 'database', 'order_position' => 11, 'is_featured' => false],
            ['name' => 'AWS', 'slug' => 'aws', 'icon' => '☁️', 'proficiency_level' => 65, 'category' => 'devops', 'order_position' => 12, 'is_featured' => false],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(['slug' => $skill['slug']], $skill);
        }

        // Testimonials
        $testimonials = [
            [
                'client_name' => 'Ali Hassan',
                'client_position' => 'CEO',
                'client_company' => 'TechVision PK',
                'content' => 'Muhammad Ahmad ne hamare e-commerce platform ko completely rebuild kiya. Laravel aur Vue.js ka combination bohot acha tha. Delivery time pe thi aur quality exceptional thi. Highly recommended!',
                'rating' => 5,
                'is_featured' => true,
                'is_published' => true,
                'order_position' => 1,
            ],
            [
                'client_name' => 'Sarah Mitchell',
                'client_position' => 'Product Manager',
                'client_company' => 'StartupHub UK',
                'content' => 'Working with Ahmad was an absolute pleasure. He built our entire SaaS dashboard from scratch with clean code and great attention to detail. The project was delivered on time and within budget.',
                'rating' => 5,
                'is_featured' => true,
                'is_published' => true,
                'order_position' => 2,
            ],
            [
                'client_name' => 'Usman Malik',
                'client_position' => 'CTO',
                'client_company' => 'DigiSoft Solutions',
                'content' => 'Bohot hi professional developer hain. API development aur database design mein unki expertise kamaal ki hai. Hamari team ke saath coordination bhi bohot acha tha.',
                'rating' => 5,
                'is_featured' => true,
                'is_published' => true,
                'order_position' => 3,
            ],
            [
                'client_name' => 'Emma Johnson',
                'client_position' => 'Founder',
                'client_company' => 'CreativeFlow Agency',
                'content' => 'Ahmad developed our portfolio website and it looks stunning! The animations are smooth, the design is modern, and the performance is excellent. Will definitely work with him again.',
                'rating' => 5,
                'is_featured' => true,
                'is_published' => true,
                'order_position' => 4,
            ],
            [
                'client_name' => 'Bilal Ahmed',
                'client_position' => 'Director',
                'client_company' => 'NextGen Technologies',
                'content' => 'Real estate management system jo Ahmad ne banaya, woh outstanding hai. Property listing, search filters, aur admin panel sab kuch perfectly kaam kar raha hai. 5 stars!',
                'rating' => 5,
                'is_featured' => false,
                'is_published' => true,
                'order_position' => 5,
            ],
            [
                'client_name' => 'James Wilson',
                'client_position' => 'Operations Manager',
                'client_company' => 'RetailPro Inc',
                'content' => 'The POS system Ahmad built for our restaurant chain has transformed our operations. Inventory management and reporting features are exactly what we needed. Excellent work!',
                'rating' => 5,
                'is_featured' => false,
                'is_published' => true,
                'order_position' => 6,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::firstOrCreate(
                ['client_name' => $t['client_name'], 'client_company' => $t['client_company']],
                $t
            );
        }

        echo "✅ Seeded: " . Skill::count() . " skills, " . Testimonial::count() . " testimonials\n";
    }
}
