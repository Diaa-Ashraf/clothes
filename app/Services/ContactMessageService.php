<?php

namespace App\Services;

use App\Models\ContactMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactMessageService
{
    /**
     * Get paginated messages with optional read/unread filter.
     */
    public function paginate(?string $filter = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = ContactMessage::select([
            'id',
            'name',
            'phone',
            'email',
            'subject',
            'message',
            'is_read',
            'created_at',
        ]);

        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->read();
        }

        return $query->latest('id')->paginate($perPage)->withQueryString();
    }

    /**
     * Find message by ID and auto-mark as read.
     */
    public function findAndMarkAsRead(int $id): ContactMessage
    {
        $message = ContactMessage::select([
            'id',
            'name',
            'phone',
            'email',
            'subject',
            'message',
            'is_read',
            'created_at',
        ])->findOrFail($id);

        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return $message;
    }

    /**
     * Delete contact message.
     */
    public function delete(ContactMessage $message): bool
    {
        return (bool) $message->delete();
    }

    /**
     * Get count of unread messages.
     */
    public function getUnreadCount(): int
    {
        return ContactMessage::unread()->count();
    }

    /**
     * Create a new contact message.
     */
    public function create(array $data): ContactMessage
    {
        $message = ContactMessage::create([
            'name' => trim($data['name']),
            'phone' => trim($data['phone']),
            'email' => !empty($data['email']) ? strtolower(trim($data['email'])) : null,
            'subject' => trim($data['subject']),
            'message' => trim($data['message']),
            'is_read' => false,
        ]);

        try {
            \App\Models\AdminNotification::createForMessage($message);
        } catch (\Throwable $e) {
            // Ignore notification error
        }

        return $message;
    }
}
