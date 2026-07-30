<?php

namespace App\Http\Controllers\API\Public;

use App\Http\Controllers\API\BaseController;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class NewsletterController extends BaseController
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:newsletters,email',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $newsletter = Newsletter::create([
            'email' => $request->email,
            'name' => $request->name,
            'status' => 'subscribed',
            'subscribed_at' => now(),
        ]);

        return $this->sendResponse($newsletter, 'Successfully subscribed to newsletter!', 201);
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:newsletters,email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $newsletter = Newsletter::where('email', $request->email)->first();
        $newsletter->unsubscribe();

        return $this->sendResponse(null, 'Successfully unsubscribed from newsletter');
    }
}
