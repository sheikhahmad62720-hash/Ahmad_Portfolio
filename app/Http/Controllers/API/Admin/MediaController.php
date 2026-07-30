<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Media;
use App\Models\MediaFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class MediaController extends BaseController
{
    public function index(): JsonResponse
    {
        $query = Media::with(['user:id,name', 'folder']);

        if (request('folder_id')) {
            $query->where('folder_id', request('folder_id'));
        }

        if (request('type')) {
            $query->where('file_type', request('type'));
        }

        if (request('search')) {
            $query->where(function ($q) {
                $q->where('filename', 'like', '%' . request('search') . '%')
                  ->orWhere('original_filename', 'like', '%' . request('search') . '%');
            });
        }

        $media = $query->orderBy('created_at', 'desc')
            ->paginate(request('per_page', 24));

        return $this->sendPaginatedResponse($media);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB max
            'folder_id' => 'nullable|exists:media_folders,id',
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . str_replace(' ', '_', $originalName);
        $path = $file->storeAs('media', $filename, 'public');

        $media = Media::create([
            'user_id' => auth()->id(),
            'folder_id' => $request->folder_id,
            'filename' => $filename,
            'original_filename' => $originalName,
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'alt_text' => $request->alt_text,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return $this->sendResponse($media, 'File uploaded successfully', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $media = Media::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'folder_id' => 'nullable|exists:media_folders,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $media->update($request->only(['alt_text', 'title', 'description', 'folder_id']));

        return $this->sendResponse($media, 'Media updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $media = Media::findOrFail($id);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return $this->sendResponse(null, 'Media deleted successfully');
    }

    public function folders(): JsonResponse
    {
        $folders = MediaFolder::with('children')->whereNull('parent_id')->get();
        return $this->sendResponse($folders, 'Folders retrieved successfully');
    }

    public function createFolder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:media_folders,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $folder = MediaFolder::create($request->only(['name', 'parent_id']));

        return $this->sendResponse($folder, 'Folder created successfully', 201);
    }
}
