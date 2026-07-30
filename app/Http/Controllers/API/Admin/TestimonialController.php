<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class TestimonialController extends BaseController
{
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::with('project:id,title')
            ->orderBy('order_position')
            ->get();
        return $this->sendResponse($testimonials, 'Testimonials retrieved successfully');
    }

    public function show(int $id): JsonResponse
    {
        $testimonial = Testimonial::with('project:id,title')->findOrFail($id);
        return $this->sendResponse($testimonial, 'Testimonial retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255',
            'client_position' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'client_avatar' => 'nullable|string|max:500',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'project_id' => 'nullable|exists:projects,id',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $testimonial = Testimonial::create([
            'client_name' => $request->client_name,
            'client_position' => $request->client_position,
            'client_company' => $request->client_company,
            'client_avatar' => $request->client_avatar,
            'content' => $request->content,
            'rating' => $request->rating,
            'project_id' => $request->project_id,
            'is_featured' => $request->is_featured ?? false,
            'is_published' => $request->is_published ?? true,
            'order_position' => Testimonial::max('order_position') + 1,
        ]);

        return $this->sendResponse($testimonial, 'Testimonial created successfully', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255',
            'client_position' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'client_avatar' => 'nullable|string|max:500',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'project_id' => 'nullable|exists:projects,id',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $testimonial->update($request->all());

        return $this->sendResponse($testimonial, 'Testimonial updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();
        return $this->sendResponse(null, 'Testimonial deleted successfully');
    }
}
