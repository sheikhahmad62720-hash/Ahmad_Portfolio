<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CertificateController extends BaseController
{
    public function index(): JsonResponse
    {
        $certificates = Certificate::orderBy('issue_date', 'desc')->get();
        return $this->sendResponse($certificates, 'Certificates retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'credential_id' => 'nullable|string|max:255',
            'credential_url' => 'nullable|url|max:500',
            'certificate_file' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_verified' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $certificate = Certificate::create([
            ...$request->all(),
            'order_position' => Certificate::max('order_position') + 1,
        ]);

        return $this->sendResponse($certificate, 'Certificate created successfully', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $certificate = Certificate::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'credential_id' => 'nullable|string|max:255',
            'credential_url' => 'nullable|url|max:500',
            'certificate_file' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_verified' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $certificate->update($request->all());

        return $this->sendResponse($certificate, 'Certificate updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->delete();
        return $this->sendResponse(null, 'Certificate deleted successfully');
    }
}
