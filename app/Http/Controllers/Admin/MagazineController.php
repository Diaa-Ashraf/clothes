<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMagazineRequest;
use App\Http\Requests\Admin\UpdateMagazineRequest;
use App\Models\Magazine;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MagazineController extends Controller
{
    /**
     * Display a listing of magazine editions.
     */
    public function index(Request $request): View
    {
        $magazines = Magazine::withCount('items')
            ->orderBy('is_active', 'desc')
            ->orderBy('starts_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.magazines.index', compact('magazines'));
    }

    /**
     * Show the form for creating a new magazine edition.
     */
    public function create(): View
    {
        $products = Product::select(['id', 'name', 'price', 'discount_price', 'image', 'is_available'])
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.magazines.create', compact('products'));
    }

    /**
     * Store a newly created magazine edition in storage.
     */
    public function store(StoreMagazineRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $itemsData = $validated['items'] ?? [];
        unset($validated['items']);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('magazines', 'public');
        }

        DB::transaction(function () use ($validated, $itemsData, $request) {
            // If marked as active, optionally ensure only one is the main active or let multiple be active
            if (!empty($validated['is_active'])) {
                Magazine::where('is_active', true)->update(['is_active' => false]);
            }

            $magazine = Magazine::create($validated);

            // Bulk create magazine items
            foreach ($itemsData as $index => $item) {
                $magazine->items()->create([
                    'product_id' => $item['product_id'],
                    'original_price' => $item['original_price'],
                    'deal_price' => $item['deal_price'],
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'badge_text' => $item['badge_text'] ?? 'عرض خاص',
                    'sort_order' => $index,
                ]);
            }
        });

        return redirect()->route('admin.magazines.index')->with('success', __('تم إنشاء ونشر إصدار المجلة بنجاح مع كافة منتجاتها.'));
    }

    /**
     * Show the form for editing the specified magazine.
     */
    public function edit(Magazine $magazine): View
    {
        $magazine->load(['items.product:id,name,price,discount_price,image']);

        $products = Product::select(['id', 'name', 'price', 'discount_price', 'image', 'is_available'])
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.magazines.edit', compact('magazine', 'products'));
    }

    /**
     * Update the specified magazine edition in storage.
     */
    public function update(UpdateMagazineRequest $request, Magazine $magazine): RedirectResponse
    {
        $validated = $request->validated();
        $itemsData = $validated['items'] ?? [];
        unset($validated['items']);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            if ($magazine->cover_image && Storage::disk('public')->exists($magazine->cover_image)) {
                Storage::disk('public')->delete($magazine->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('magazines', 'public');
        }

        DB::transaction(function () use ($magazine, $validated, $itemsData) {
            if (!empty($validated['is_active'])) {
                Magazine::where('id', '!=', $magazine->id)->where('is_active', true)->update(['is_active' => false]);
            }

            $magazine->update($validated);

            // Sync items: delete previous and insert updated
            $magazine->items()->delete();

            foreach ($itemsData as $index => $item) {
                $magazine->items()->create([
                    'product_id' => $item['product_id'],
                    'original_price' => $item['original_price'],
                    'deal_price' => $item['deal_price'],
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'badge_text' => $item['badge_text'] ?? 'عرض خاص',
                    'sort_order' => $index,
                ]);
            }
        });

        return redirect()->route('admin.magazines.index')->with('success', __('تم تحديث بيانات المجلة ومنتجاتها بنجاح.'));
    }

    /**
     * Toggle active status for the magazine.
     */
    public function toggleActive(Magazine $magazine): RedirectResponse
    {
        $newState = !$magazine->is_active;

        if ($newState) {
            Magazine::where('id', '!=', $magazine->id)->update(['is_active' => false]);
        }

        $magazine->update(['is_active' => $newState]);

        $msg = $newState 
            ? __('تم تفعيل المجلة وجعلها المجلة النشطة الحالية للمتجر.')
            : __('تم إلغاء تفعيل المجلة.');

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Remove the specified magazine from storage.
     */
    public function destroy(Magazine $magazine): RedirectResponse
    {
        if ($magazine->cover_image && Storage::disk('public')->exists($magazine->cover_image)) {
            Storage::disk('public')->delete($magazine->cover_image);
        }

        $magazine->delete();

        return redirect()->route('admin.magazines.index')->with('success', __('تم حذف إصدار المجلة بنجاح.'));
    }
}
