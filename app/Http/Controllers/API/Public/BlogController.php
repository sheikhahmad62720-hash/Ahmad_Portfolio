<?php

namespace App\Http\Controllers\API\Public;

use App\Http\Controllers\API\BaseController;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class BlogController extends BaseController
{
    /**
     * Get all published blogs
     */
    public function index(): JsonResponse
    {
        $query = Blog::published()
            ->with(['user:id,name,avatar', 'categories', 'tags']);

        // Apply filters
        if (request('category')) {
            $query->whereHas('categories', function ($q) {
                $q->where('slug', request('category'));
            });
        }

        if (request('tag')) {
            $query->whereHas('tags', function ($q) {
                $q->where('slug', request('tag'));
            });
        }

        if (request('search')) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('excerpt', 'like', '%' . request('search') . '%')
                  ->orWhere('content', 'like', '%' . request('search') . '%');
            });
        }

        $blogs = $query->orderBy('published_at', 'desc')
            ->paginate(request('per_page', 12));

        return $this->sendPaginatedResponse($blogs, 'Blogs retrieved successfully');
    }

    /**
     * Get single blog by slug
     */
    public function show(string $slug): JsonResponse
    {
        $blog = Blog::where('slug', $slug)
            ->published()
            ->with([
                'user:id,name,avatar,bio',
                'categories',
                'tags',
                'approvedComments' => function ($query) {
                    $query->whereNull('parent_id')
                        ->with(['user:id,name,avatar', 'replies.user:id,name,avatar'])
                        ->orderBy('created_at', 'desc');
                }
            ])
            ->firstOrFail();

        // Increment views
        $blog->incrementViews();

        // Get related blogs
        $relatedBlogs = Blog::where('id', '!=', $blog->id)
            ->published()
            ->whereHas('categories', function ($q) use ($blog) {
                $q->whereIn('categories.id', $blog->categories->pluck('id'));
            })
            ->with('categories')
            ->limit(3)
            ->get();

        $data = [
            'blog' => $blog,
            'related' => $relatedBlogs,
        ];

        return $this->sendResponse($data, 'Blog retrieved successfully');
    }

    /**
     * Get all categories
     */
    public function categories(): JsonResponse
    {
        $categories = Category::withCount('blogs')
            ->orderBy('order_position')
            ->get();

        return $this->sendResponse($categories, 'Categories retrieved successfully');
    }

    /**
     * Get all tags
     */
    public function tags(): JsonResponse
    {
        $tags = Tag::withCount('blogs')
            ->orderBy('name')
            ->get();

        return $this->sendResponse($tags, 'Tags retrieved successfully');
    }

    /**
     * Get featured blogs
     */
    public function featured(): JsonResponse
    {
        $blogs = Blog::published()
            ->featured()
            ->with(['user:id,name,avatar', 'categories'])
            ->limit(5)
            ->get();

        return $this->sendResponse($blogs, 'Featured blogs retrieved successfully');
    }

    /**
     * Get recent blogs
     */
    public function recent(): JsonResponse
    {
        $blogs = Blog::published()
            ->with(['user:id,name,avatar', 'categories'])
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return $this->sendResponse($blogs, 'Recent blogs retrieved successfully');
    }
}
