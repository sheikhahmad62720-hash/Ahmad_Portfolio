<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class EducationController extends BaseController
{
    public function index(): JsonResponse
    {
        $education = Education::orderBy('start_date', 'desc')->get();
        return $this->sendResponse($education, 'Education retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'grade' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'order_position' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $education = Education::create([
            'institution' => $request->institution,
            'degree' => $request->degree,
            'field_of_study' => $request->field_of_study,
            'location' => $request->location,
            'description' => $request->description,
            'grade' => $request->grade,
            'start_date' => $request->start_date,
            'end_date' => $request->is_current ? null : $request->end_date,
            'is_current' => $request->is_current ?? false,
            'order_position' => $request->order_position ?? 0,
        ]);

        return $this->sendResponse($education, 'Education created successfully', 201);
    }

    public function show(int $id): JsonResponse
    {
        $education = Education::findOrFail($id);
        return $this->sendResponse($education, 'Education retrieved successfully');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $education = Education::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'grade' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'order_position' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $education->update([
            'institution' => $request->institution,
            'degree' => $request->degree,
            'field_of_study' => $request->field_of_study,
            'location' => $request->location,
            'description' => $request->description,
            'grade' => $request->grade,
            'start_date' => $request->start_date,
            'end_date' => $request->is_current ? null : $request->end_date,
            'is_current' => $request->is_current ?? false,
            'order_position' => $request->order_position ?? 0,
        ]);

        return $this->sendResponse($education, 'Education updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $education = Education::findOrFail($id);
        $education->delete();
        return $this->sendResponse(null, 'Education deleted successfully');
    }
}
