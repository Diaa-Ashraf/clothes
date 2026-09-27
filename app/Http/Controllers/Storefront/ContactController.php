<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\StoreContactMessageRequest;
use App\Models\Branch;
use App\Models\Setting;
use App\Services\ContactMessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        protected ContactMessageService $contactMessageService
    ) {}

    /**
     * Display contact us page with branch numbers, address & inquiry form.
     */
    public function index(): View
    {
        $branches = Branch::select(['id', 'name', 'phone', 'whatsapp', 'address', 'google_maps_url'])
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $settings = Setting::all()->pluck('formatted_value', 'key');

        return view('storefront.contact', compact('branches', 'settings'));
    }

    /**
     * Store incoming customer contact message.
     */
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $this->contactMessageService->create($request->validated());

        return back()->with('success', __('شكراً لتواصلك معنا! تم استلام رسالتك بنجاح وسنقوم بالرد عليك في أقرب وقت.'));
    }
}
