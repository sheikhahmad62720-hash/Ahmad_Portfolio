<?php

namespace App\Http\Controllers\API\Public;

use App\Http\Controllers\API\BaseController;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Service;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Certificate;
use App\Models\Testimonial;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class PortfolioController extends BaseController
{
    /**
     * Get complete portfolio data
     */
    public function index(): JsonResponse
    {
        $data = [
            'profile' => [
                'name' => Setting::get('site_name', 'Muhammad Ahmad'),
                'tagline' => Setting::get('site_tagline', 'Full Stack Developer'),
                'description' => Setting::get('site_description', 'Building modern web applications'),
                'email' => Setting::get('contact_email'),
                'phone' => Setting::get('contact_phone'),
                'location' => Setting::get('contact_address'),
                'social' => [
                    'github' => Setting::get('social_github'),
                    'linkedin' => Setting::get('social_linkedin'),
                    'facebook' => Setting::get('social_facebook'),
                    'instagram' => Setting::get('social_instagram'),
                    'twitter' => Setting::get('social_twitter'),
                ],
            ],
            'projects' => Project::where('is_published', true)
                ->where('is_featured', true)
                ->with('technologies')
                ->orderBy('order_position')
                ->limit(6)
                ->get(),
            'skills' => Skill::where('is_featured', true)
                ->orderBy('order_position')
                ->get(),
            'services' => Service::where('is_active', true)
                ->where('is_featured', true)
                ->orderBy('order_position')
                ->limit(6)
                ->get(),
            'experiences' => Experience::orderBy('start_date', 'desc')
                ->limit(5)
                ->get(),
            'education' => Education::orderBy('start_date', 'desc')
                ->get(),
            'certificates' => Certificate::orderBy('issue_date', 'desc')
                ->limit(6)
                ->get(),
            'testimonials' => Testimonial::where('is_published', true)
                ->where('is_featured', true)
                ->orderBy('order_position')
                ->limit(6)
                ->get(),
        ];

        return $this->sendResponse($data, 'Portfolio data retrieved successfully');
    }

    /**
     * Get all projects with filters
     */
    public function projects(): JsonResponse
    {
        $query = Project::where('is_published', true)
            ->with('technologies', 'testimonials');

        // Apply filters
        if (request('category')) {
            $query->where('project_type', request('category'));
        }

        if (request('technology')) {
            $query->whereHas('technologies', function ($q) {
                $q->where('slug', request('technology'));
            });
        }

        if (request('search')) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('description', 'like', '%' . request('search') . '%');
            });
        }

        $projects = $query->orderBy('order_position')
            ->orderBy('created_at', 'desc')
            ->paginate(request('per_page', 12));

        return $this->sendPaginatedResponse($projects, 'Projects retrieved successfully');
    }

    /**
     * Get single project by slug
     */
    public function projectDetail(string $slug): JsonResponse
    {
        $project = Project::where('slug', $slug)
            ->where('is_published', true)
            ->with(['technologies', 'images', 'testimonials'])
            ->firstOrFail();

        // Increment views
        $project->incrementViews();

        // Get related projects
        $relatedProjects = Project::where('id', '!=', $project->id)
            ->where('is_published', true)
            ->whereHas('technologies', function ($q) use ($project) {
                $q->whereIn('technologies.id', $project->technologies->pluck('id'));
            })
            ->with('technologies')
            ->limit(3)
            ->get();

        $data = [
            'project' => $project,
            'related' => $relatedProjects,
        ];

        return $this->sendResponse($data, 'Project retrieved successfully');
    }

    /**
     * Get all skills grouped by category
     */
    public function skills(): JsonResponse
    {
        $skills = Skill::orderBy('order_position')->get();
        $groupedSkills = $skills->groupBy('category');

        return $this->sendResponse($groupedSkills, 'Skills retrieved successfully');
    }

    /**
     * Get all services
     */
    public function services(): JsonResponse
    {
        $services = Service::where('is_active', true)
            ->orderBy('order_position')
            ->get();

        return $this->sendResponse($services, 'Services retrieved successfully');
    }

    /**
     * Get experience timeline
     */
    public function experience(): JsonResponse
    {
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        return $this->sendResponse($experiences, 'Experience retrieved successfully');
    }

    /**
     * Get education history
     */
    public function education(): JsonResponse
    {
        $education = Education::orderBy('start_date', 'desc')->get();
        return $this->sendResponse($education, 'Education retrieved successfully');
    }

    /**
     * Get certificates
     */
    public function certificates(): JsonResponse
    {
        $certificates = Certificate::orderBy('issue_date', 'desc')->get();
        return $this->sendResponse($certificates, 'Certificates retrieved successfully');
    }

    /**
     * Get testimonials
     */
    public function testimonials(): JsonResponse
    {
        $testimonials = Testimonial::where('is_published', true)
            ->with('project:id,title,slug')
            ->orderBy('order_position')
            ->get();

        return $this->sendResponse($testimonials, 'Testimonials retrieved successfully');
    }
}
