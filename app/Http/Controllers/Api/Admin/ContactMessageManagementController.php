<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactMessageManagementController extends ApiController
{
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ContactMessage::with(['artist:id,name']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        if ($request->filled('artist_id')) {
            $query->where('artist_id', $request->artist_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $messages = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($messages);
    }

    /**
     * Display the specified contact message.
     */
    public function show(int $id): JsonResponse
    {
        $message = ContactMessage::with(['artist:id,name,email'])->find($id);

        if (!$message) {
            return $this->notFound('Contact message not found');
        }

        return $this->success($message);
    }

    /**
     * Update the specified contact message.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->notFound('Contact message not found');
        }

        $validator = Validator::make($request->all(), [
            'status' => ['nullable', 'in:pending,in_progress,resolved,closed'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $message->update($request->all());

        return $this->success($message, 'Contact message updated successfully');
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(int $id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->notFound('Contact message not found');
        }

        $message->markAsRead();

        return $this->success($message, 'Message marked as read');
    }

    /**
     * Mark message as unread.
     */
    public function markAsUnread(int $id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->notFound('Contact message not found');
        }

        $message->update(['is_read' => false]);

        return $this->success($message, 'Message marked as unread');
    }

    /**
     * Update message status.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->notFound('Contact message not found');
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:pending,in_progress,resolved,closed'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $message->update(['status' => $request->status]);

        if ($request->status === 'resolved') {
            $message->update(['responded_at' => now()]);
        }

        return $this->success($message, 'Message status updated successfully');
    }

    /**
     * Respond to message.
     */
    public function respond(Request $request, int $id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->notFound('Contact message not found');
        }

        $validator = Validator::make($request->all(), [
            'response' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $message->update([
            'status' => 'resolved',
            'admin_notes' => $request->response,
            'responded_at' => now(),
        ]);

        // Send email notification (implement email service)
        // $emailService = app(\App\Services\EmailService::class);
        // $emailService->sendContactResponse($message, $request->response);

        return $this->success($message, 'Response sent successfully');
    }

    /**
     * Delete the specified contact message.
     */
    public function destroy(int $id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->notFound('Contact message not found');
        }

        $message->delete();

        return $this->success(null, 'Contact message deleted successfully');
    }

    /**
     * Bulk mark as read.
     */
    public function bulkMarkAsRead(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message_ids' => ['required', 'array'],
            'message_ids.*' => ['exists:contact_messages,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        ContactMessage::whereIn('id', $request->message_ids)->update(['is_read' => true]);

        return $this->success(null, 'Messages marked as read successfully');
    }

    /**
     * Bulk delete.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message_ids' => ['required', 'array'],
            'message_ids.*' => ['exists:contact_messages,id'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        ContactMessage::whereIn('id', $request->message_ids)->delete();

        return $this->success(null, 'Messages deleted successfully');
    }

    /**
     * Get contact message statistics.
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'pending_messages' => ContactMessage::where('status', 'pending')->count(),
            'in_progress_messages' => ContactMessage::where('status', 'in_progress')->count(),
            'resolved_messages' => ContactMessage::where('status', 'resolved')->count(),
            'closed_messages' => ContactMessage::where('status', 'closed')->count(),
        ];

        return $this->success($stats);
    }
}
