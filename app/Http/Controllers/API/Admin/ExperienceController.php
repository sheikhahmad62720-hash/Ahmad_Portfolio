<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ExperienceController extends BaseController
{
    public function index(): JsonResponse
    {
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        return $this->sendResponse($experiences, 'Experience retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'order_position' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $experience = Experience::create([
            'company' => $request->company,
            'position' => $request->position,
            'location' => $request->location,
            'employment_type' => $request->employment_type,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->is_current ? null : $request->end_date,
            'is_current' => $request->is_current ?? false,
            'order_position' => $request->order_position ?? 0,
        ]);

        return $this->sendResponse($experience, 'Experience created successfully', 201);
    }

    public function show(int $id): JsonResponse
    {
        $experience = Experience::findOrFail($id);
        return $this->sendResponse($experience, 'Experience retrieved successfully');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $experience = Experience::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'order_position' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $experience->update([
            'company' => $request->company,
            'position' => $request->position,
            'location' => $request->location,
            'employment_type' => $request->employment_type,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->is_current ? null : $request->end_date,
            'is_current' => $request->is_current ?? false,
            'order_position' => $request->order_position ?? 0,
        ]);

        return $this->sendResponse($experience, 'Experience updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $experience = Experience::findOrFail($id);
        $experience->delete();
        return $this->sendResponse(null, 'Experience deleted successfully');
    }
}
