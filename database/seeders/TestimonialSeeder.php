<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'client_name' => 'Ali Hassan',
                'client_position' => 'CEO',
                'client_company' => 'TechVision PK',
                'content' => 'Muhammad Ahmad completely rebuilt our e-commerce platform using Laravel and Vue.js. The quality was exceptional, delivery was on time, and communication was great throughout the project. Highly recommended!',
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
                'content' => 'A highly professional developer with excellent expertise in API development and database architecture. His coordination with our team was outstanding and he delivered beyond our expectations.',
                'rating' => 5,
                'is_featured' => true,
                'is_published' => true,
                'order_position' => 3,
            ],
            [
                'client_name' => 'Emma Johnson',
                'client_position' => 'Founder',
                'client_company' => 'CreativeFlow Agency',
                'content' => 'Ahmad developed our portfolio website and it looks absolutely stunning! The animations are smooth, the design is modern and clean, and the performance score is excellent. Will definitely work with him again.',
                'rating' => 5,
                'is_featured' => true,
                'is_published' => true,
                'order_position' => 4,
            ],
            [
                'client_name' => 'Bilal Ahmed',
                'client_position' => 'Director',
                'client_company' => 'NextGen Technologies',
                'content' => 'The real estate management system Ahmad built is outstanding. Property listing, search filters, map integration and admin panel all work perfectly. Best developer I have worked with!',
                'rating' => 5,
                'is_featured' => true,
                'is_published' => true,
                'order_position' => 5,
            ],
            [
                'client_name' => 'James Wilson',
                'client_position' => 'Operations Manager',
                'client_company' => 'RetailPro Inc',
                'content' => 'The POS system Ahmad built for our restaurant chain has completely transformed our operations. Inventory management and real-time reporting features are exactly what we needed. Excellent work!',
                'rating' => 5,
                'is_featured' => true,
                'is_published' => true,
                'order_position' => 6,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }

        echo "✅ Seeded: " . Testimonial::count() . " testimonials\n";
    }
}
