<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class BlogController extends BaseController
{
    /**
     * Get all blogs
     */
    public function index(): JsonResponse
    {
        $query = Blog::with(['user:id,name', 'categories', 'tags']);

        // Search
        if (request('search')) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('excerpt', 'like', '%' . request('search') . '%');
            });
        }

        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter by featured
        if (request('is_featured') !== null && request('is_featured') !== '') {
            $query->where('is_featured', request('is_featured'));
        }

        // Sort
        $sortBy = request('sort_by', 'created_at');
        $sortOrder = request('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $blogs = $query->paginate(request('per_page', 15));

        return $this->sendPaginatedResponse($blogs);
    }

    /**
     * Store new blog
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'array',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        // Calculate reading time
        $wordCount = str_word_count(strip_tags($request->content ?? ''));
        $readingTime = max(1, ceil($wordCount / 200));

        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $count = 1;
        while (Blog::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $featuredImage = $request->featured_image;

        // If base64 image, save it to disk
        if ($featuredImage && str_starts_with($featuredImage, 'data:image')) {
            $featuredImage = $this->saveBase64Image($featuredImage);
        }

        $blog = Blog::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $featuredImage,
            'reading_time' => $readingTime,
            'status' => $request->status,
            'is_featured' => $request->is_featured ?? false,
            'published_at' => $request->status === 'published' ? ($request->published_at ?? now()) : null,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        // Attach categories
        if ($request->has('categories')) {
            $blog->categories()->attach($request->categories);
        }

        // Handle tags (create if not exists)
        if ($request->has('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = \App\Models\Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
            $blog->tags()->attach($tagIds);
        }

        return $this->sendResponse($blog->load('categories', 'tags'), 'Blog created successfully', 201);
    }

    /**
     * Get single blog
     */
    public function show(int $id): JsonResponse
    {
        $blog = Blog::with(['categories', 'tags', 'comments'])->findOrFail($id);
        return $this->sendResponse($blog, 'Blog retrieved successfully');
    }

    /**
     * Update blog
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'array',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        // Calculate reading time
        $wordCount = str_word_count(strip_tags($request->content));
        $readingTime = ceil($wordCount / 200);

        $featuredImage = $request->featured_image;
        if ($featuredImage && str_starts_with($featuredImage, 'data:image')) {
            $featuredImage = $this->saveBase64Image($featuredImage);
        }

        // Generate unique slug excluding current blog
        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $count = 1;
        while (Blog::withTrashed()->where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $blog->update([
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $featuredImage,
            'reading_time' => $readingTime,
            'status' => $request->status,
            'is_featured' => $request->is_featured ?? false,
            'published_at' => $request->status === 'published' ? ($request->published_at ?? $blog->published_at ?? now()) : null,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        // Sync categories
        if ($request->has('categories')) {
            $blog->categories()->sync($request->categories);
        }

        // Sync tags
        if ($request->has('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = \App\Models\Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
            $blog->tags()->sync($tagIds);
        }

        return $this->sendResponse($blog->load('categories', 'tags'), 'Blog updated successfully');
    }

    /**
     * Delete blog
     */
    public function destroy(int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return $this->sendResponse(null, 'Blog deleted successfully');
    }

    private function saveBase64Image(string $base64Data): string
    {
        // Ensure it has the data URI prefix
        if (!str_contains($base64Data, 'data:image')) {
            return 'data:image/jpeg;base64,' . $base64Data;
        }
        return $base64Data;
    }

    /**
     * Bulk delete blogs
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:blogs,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        Blog::whereIn('id', $request->ids)->delete();

        return $this->sendResponse(null, 'Blogs deleted successfully');
    }
}
