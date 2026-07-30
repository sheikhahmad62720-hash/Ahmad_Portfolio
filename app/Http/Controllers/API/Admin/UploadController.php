<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class UploadController extends BaseController
{
    public function upload(Request $request): JsonResponse
    {
        try {
            if (!$request->has('base64') || empty($request->base64)) {
                return $this->sendError('No image data provided', [], 422);
            }

            $base64Data = $request->base64;

            // Ensure it has the data URI prefix
            if (!str_contains($base64Data, 'data:image')) {
                $ext = strtolower($request->extension ?? 'jpg');
                $mimeTypes = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp'];
                $mime = $mimeTypes[$ext] ?? 'image/jpeg';
                $base64Data = 'data:' . $mime . ';base64,' . $base64Data;
            }

            return $this->sendResponse([
                'url' => $base64Data,
                'path' => $base64Data,
            ], 'File uploaded successfully');

        } catch (\Exception $e) {
            return $this->sendError('Upload error: ' . $e->getMessage(), [], 500);
        }
    }
}
