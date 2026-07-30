<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Education;
use Illuminate\Database\Seeder;

class ExperienceEducationSeeder extends Seeder
{
    public function run(): void
    {
        // Work Experience
        $experiences = [
            [
                'company' => 'NBTHUB',
                'position' => 'Full Stack Developer',
                'location' => 'Lahore, Pakistan',
                'employment_type' => 'Full-time',
                'description' => 'Working as a Full Stack Developer building scalable web applications using Laravel and Vue.js. Responsible for designing RESTful APIs, database architecture, and frontend development. Collaborated with cross-functional teams to deliver high-quality products.',
                'start_date' => '2022-01-01',
                'end_date' => null,
                'is_current' => true,
                'order_position' => 1,
            ],
            [
                'company' => 'FreelanceWork',
                'position' => 'Laravel & Vue.js Developer',
                'location' => 'Remote',
                'employment_type' => 'Freelance',
                'description' => 'Developed multiple client projects including e-commerce platforms, SaaS dashboards, and business management systems. Worked with international clients from UK, USA, and Middle East. Delivered 20+ successful projects.',
                'start_date' => '2020-06-01',
                'end_date' => '2021-12-31',
                'is_current' => false,
                'order_position' => 2,
            ],
            [
                'company' => 'TechSolutions PK',
                'position' => 'Junior Web Developer',
                'location' => 'Lahore, Pakistan',
                'employment_type' => 'Full-time',
                'description' => 'Started career as a Junior Web Developer working on PHP and Laravel based projects. Gained hands-on experience in MVC architecture, database design, and frontend development using HTML, CSS, and JavaScript.',
                'start_date' => '2019-03-01',
                'end_date' => '2020-05-31',
                'is_current' => false,
                'order_position' => 3,
            ],
        ];

        foreach ($experiences as $exp) {
            Experience::firstOrCreate(
                ['company' => $exp['company'], 'position' => $exp['position']],
                $exp
            );
        }

        // Education
        $education = [
            [
                'institution' => 'Islamia University of Bahawalpur (Bwn campus)',
                'degree' => 'Bachelor of Science',
                'field_of_study' => 'Computer Science',
                'location' => 'Lahore, Pakistan',
                'description' => 'Studied core computer science subjects including Data Structures, Algorithms, Database Systems, Software Engineering, and Web Development. Graduated with distinction.',
                'start_date' => '2020-09-01',
                'end_date' => '2024-06-30',
                'is_current' => false,
            ],
            [
                'institution' => 'Govt Higher Secondary School',
                'degree' => 'Intermediate (ICS)',
                'field_of_study' => 'Computer Science',
                'location' => 'Lahore, Pakistan',
                'description' => 'Completed intermediate education with focus on Mathematics, Physics, and Computer Science fundamentals.',
                'start_date' => '2018-09-01',
                'end_date' => '2020-06-30',
                'is_current' => false,
            ],
        ];

        foreach ($education as $edu) {
            Education::firstOrCreate(
                ['institution' => $edu['institution'], 'degree' => $edu['degree']],
                $edu
            );
        }

        echo "✅ Seeded: " . Experience::count() . " experiences, " . Education::count() . " education records\n";
    }
}
