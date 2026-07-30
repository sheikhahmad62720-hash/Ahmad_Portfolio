<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class MessageController extends BaseController
{
    /**
     * Get all messages
     */
    public function index(): JsonResponse
    {
        $query = Message::query();

        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Search
        if (request('search')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                  ->orWhere('email', 'like', '%' . request('search') . '%')
                  ->orWhere('subject', 'like', '%' . request('search') . '%');
            });
        }

        $messages = $query->orderBy('created_at', 'desc')
            ->paginate(request('per_page', 15));

        return $this->sendPaginatedResponse($messages);
    }

    /**
     * Get single message
     */
    public function show(int $id): JsonResponse
    {
        $message = Message::findOrFail($id);
        
        // Mark as read if unread
        if ($message->status === 'unread') {
            $message->markAsRead();
        }

        return $this->sendResponse($message, 'Message retrieved successfully');
    }

    /**
     * Update message status
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $message = Message::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:unread,read,replied',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $message->status = $request->status;
        $message->save();

        return $this->sendResponse($message, 'Message updated successfully');
    }

    /**
     * Reply to message
     */
    public function reply(Request $request, int $id): JsonResponse
    {
        $message = Message::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'reply' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $message->markAsReplied($request->reply);

        // TODO: Send email reply to user

        return $this->sendResponse($message, 'Reply sent successfully');
    }

    /**
     * Delete message
     */
    public function destroy(int $id): JsonResponse
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return $this->sendResponse(null, 'Message deleted successfully');
    }

    /**
     * Bulk delete messages
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:messages,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        Message::whereIn('id', $request->ids)->delete();

        return $this->sendResponse(null, 'Messages deleted successfully');
    }
}
