<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContactMessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function __construct(
        protected ContactMessageService $contactMessageService
    ) {}

    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request): View
    {
        $filter = $request->query('filter');
        $messages = $this->contactMessageService->paginate($filter, 15);
        $unreadCount = $this->contactMessageService->getUnreadCount();

        return view('admin.contact-messages.index', compact('messages', 'filter', 'unreadCount'));
    }

    /**
     * Display the specified contact message and mark it as read.
     */
    public function show(int $id): View
    {
        $message = $this->contactMessageService->findAndMarkAsRead($id);
        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Remove the specified contact message from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $message = $this->contactMessageService->findAndMarkAsRead($id);
        $this->contactMessageService->delete($message);

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'تم حذف رسالة التواصل بنجاح.');
    }
}
