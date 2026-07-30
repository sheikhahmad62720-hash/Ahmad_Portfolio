<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class ServiceController extends BaseController
{
    public function index(): JsonResponse
    {
        $services = Service::orderBy('order_position')->get();
        return $this->sendResponse($services, 'Services retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:500',
            'price_starting' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'delivery_time' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $service = Service::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'icon' => $request->icon,
            'price_starting' => $request->price_starting,
            'currency' => $request->currency ?? 'USD',
            'delivery_time' => $request->delivery_time,
            'is_active' => $request->is_active ?? true,
            'is_featured' => $request->is_featured ?? false,
            'order_position' => Service::max('order_position') + 1,
        ]);

        return $this->sendResponse($service, 'Service created successfully', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $service = Service::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:500',
            'price_starting' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'delivery_time' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $service->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'icon' => $request->icon,
            'price_starting' => $request->price_starting,
            'currency' => $request->currency ?? 'USD',
            'delivery_time' => $request->delivery_time,
            'is_active' => $request->is_active ?? true,
            'is_featured' => $request->is_featured ?? false,
        ]);

        return $this->sendResponse($service, 'Service updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return $this->sendResponse(null, 'Service deleted successfully');
    }
}
