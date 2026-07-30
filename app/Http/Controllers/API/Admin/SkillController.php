<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class SkillController extends BaseController
{
    /**
     * Get all skills
     */
    public function index(): JsonResponse
    {
        $query = Skill::query();

        if (request('category')) {
            $query->where('category', request('category'));
        }

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        $skills = $query->orderBy('order_position')->get();

        return $this->sendResponse($skills, 'Skills retrieved successfully');
    }

    /**
     * Store new skill
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:100',
            'proficiency_level' => 'required|integer|min:0|max:100',
            'years_experience' => 'nullable|numeric|min:0|max:50',
            'icon' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $skill = Skill::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category' => $request->category,
            'proficiency_level' => $request->proficiency_level,
            'years_experience' => $request->years_experience,
            'icon' => $request->icon,
            'description' => $request->description,
            'is_featured' => $request->is_featured ?? false,
            'order_position' => Skill::max('order_position') + 1,
        ]);

        return $this->sendResponse($skill, 'Skill created successfully', 201);
    }

    /**
     * Get single skill
     */
    public function show(int $id): JsonResponse
    {
        $skill = Skill::findOrFail($id);
        return $this->sendResponse($skill, 'Skill retrieved successfully');
    }

    /**
     * Update skill
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $skill = Skill::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:100',
            'proficiency_level' => 'required|integer|min:0|max:100',
            'years_experience' => 'nullable|numeric|min:0|max:50',
            'icon' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $skill->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category' => $request->category,
            'proficiency_level' => $request->proficiency_level,
            'years_experience' => $request->years_experience,
            'icon' => $request->icon,
            'description' => $request->description,
            'is_featured' => $request->is_featured ?? false,
        ]);

        return $this->sendResponse($skill, 'Skill updated successfully');
    }

    /**
     * Delete skill
     */
    public function destroy(int $id): JsonResponse
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return $this->sendResponse(null, 'Skill deleted successfully');
    }

    /**
     * Reorder skills
     */
    public function reorder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'skills' => 'required|array',
            'skills.*.id' => 'required|exists:skills,id',
            'skills.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        foreach ($request->skills as $skill) {
            Skill::where('id', $skill['id'])->update(['order_position' => $skill['order']]);
        }

        return $this->sendResponse(null, 'Skills reordered successfully');
    }
}
