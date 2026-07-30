<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class ProjectController extends BaseController
{
    /**
     * Get all projects
     */
    public function index(): JsonResponse
    {
        $query = Project::with(['user:id,name', 'technologies']);

        // Search
        if (request('search')) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('description', 'like', '%' . request('search') . '%');
            });
        }

        // Filter by is_published
        if (request('is_published') !== null) {
            $query->where('is_published', request('is_published'));
        }

        // Filter by project status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter by featured
        if (request('is_featured') !== null) {
            $query->where('is_featured', request('is_featured'));
        }

        // Sort
        $sortBy = request('sort_by', 'created_at');
        $sortOrder = request('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $projects = $query->paginate(request('per_page', 15));

        return $this->sendPaginatedResponse($projects);
    }

    /**
     * Store new project
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'demo_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'video_url' => 'nullable|url|max:500',
            'client_name' => 'nullable|string|max:255',
            'project_type' => 'nullable|string|max:100',
            'status' => 'required|in:completed,in_progress,planned',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'technologies' => 'array',
            'technologies.*' => 'exists:technologies,id',
            'images' => 'array',
            'images.*.url' => 'required|string|max:500',
            'images.*.caption' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $count = 1;
        while (Project::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $project = Project::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'content' => $request->content,
            'thumbnail' => $request->thumbnail,
            'demo_url' => $request->demo_url,
            'github_url' => $request->github_url,
            'video_url' => $request->video_url,
            'client_name' => $request->client_name,
            'project_type' => $request->project_type,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_featured' => $request->is_featured ?? false,
            'is_published' => $request->is_published ?? true,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        // Attach technologies
        if ($request->has('technologies')) {
            $project->technologies()->attach($request->technologies);
        }

        // Add images
        if ($request->has('images')) {
            foreach ($request->images as $index => $image) {
                $project->images()->create([
                    'image_url' => $image['url'],
                    'caption' => $image['caption'] ?? null,
                    'order_position' => $index,
                ]);
            }
        }

        return $this->sendResponse($project->load('technologies', 'images'), 'Project created successfully', 201);
    }

    /**
     * Get single project
     */
    public function show(int $id): JsonResponse
    {
        $project = Project::with(['technologies', 'images', 'testimonials'])->findOrFail($id);
        return $this->sendResponse($project, 'Project retrieved successfully');
    }

    /**
     * Update project
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'demo_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'video_url' => 'nullable|url|max:500',
            'client_name' => 'nullable|string|max:255',
            'project_type' => 'nullable|string|max:100',
            'status' => 'required|in:completed,in_progress,planned',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'technologies' => 'array',
            'technologies.*' => 'exists:technologies,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        // Generate unique slug excluding current project
        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $count = 1;
        while (Project::withTrashed()->where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $project->update([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'content' => $request->content,
            'thumbnail' => $request->thumbnail,
            'demo_url' => $request->demo_url,
            'github_url' => $request->github_url,
            'video_url' => $request->video_url,
            'client_name' => $request->client_name,
            'project_type' => $request->project_type,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_featured' => $request->is_featured ?? false,
            'is_published' => $request->is_published ?? true,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        // Sync technologies
        if ($request->has('technologies')) {
            $project->technologies()->sync($request->technologies);
        }

        return $this->sendResponse($project->load('technologies', 'images'), 'Project updated successfully');
    }

    /**
     * Delete project
     */
    public function destroy(int $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return $this->sendResponse(null, 'Project deleted successfully');
    }

    /**
     * Bulk delete projects
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:projects,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        Project::whereIn('id', $request->ids)->delete();

        return $this->sendResponse(null, 'Projects deleted successfully');
    }

    /**
     * Restore deleted project
     */
    public function restore(int $id): JsonResponse
    {
        $project = Project::withTrashed()->findOrFail($id);
        $project->restore();

        return $this->sendResponse($project, 'Project restored successfully');
    }
}
