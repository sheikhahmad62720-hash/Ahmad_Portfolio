<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            // Backend
            ['name' => 'Laravel', 'category' => 'Backend'],
            ['name' => 'PHP', 'category' => 'Backend'],
            ['name' => 'Node.js', 'category' => 'Backend'],
            ['name' => 'Express.js', 'category' => 'Backend'],
            
            // Frontend
            ['name' => 'Vue.js', 'category' => 'Frontend'],
            ['name' => 'React', 'category' => 'Frontend'],
            ['name' => 'JavaScript', 'category' => 'Frontend'],
            ['name' => 'TypeScript', 'category' => 'Frontend'],
            ['name' => 'HTML5', 'category' => 'Frontend'],
            ['name' => 'CSS3', 'category' => 'Frontend'],
            
            // CSS Frameworks
            ['name' => 'Tailwind CSS', 'category' => 'CSS Framework'],
            ['name' => 'Bootstrap', 'category' => 'CSS Framework'],
            
            // Databases
            ['name' => 'MySQL', 'category' => 'Database'],
            ['name' => 'PostgreSQL', 'category' => 'Database'],
            ['name' => 'MongoDB', 'category' => 'Database'],
            ['name' => 'Redis', 'category' => 'Database'],
            
            // Tools
            ['name' => 'Git', 'category' => 'Tools'],
            ['name' => 'GitHub', 'category' => 'Tools'],
            ['name' => 'Docker', 'category' => 'Tools'],
            ['name' => 'Vite', 'category' => 'Tools'],
            ['name' => 'Webpack', 'category' => 'Tools'],
            
            // APIs
            ['name' => 'REST API', 'category' => 'API'],
            ['name' => 'GraphQL', 'category' => 'API'],
            
            // State Management
            ['name' => 'Pinia', 'category' => 'State Management'],
            ['name' => 'Vuex', 'category' => 'State Management'],
            
            // Others
            ['name' => 'Axios', 'category' => 'HTTP Client'],
            ['name' => 'Linux', 'category' => 'OS'],
        ];

        foreach ($technologies as $tech) {
            DB::table('technologies')->insertOrIgnore([
                'name' => $tech['name'],
                'slug' => Str::slug($tech['name']),
                'category' => $tech['category'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
