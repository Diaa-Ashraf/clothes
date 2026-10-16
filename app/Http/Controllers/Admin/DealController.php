<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDealRequest;
use App\Http\Requests\Admin\UpdateDealRequest;
use App\Models\Deal;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DealController extends Controller
{
    public function index(): View
    {
        $deals = Deal::query()
            ->select([
                'id',
                'product_id',
                'original_price',
                'deal_price',
                'discount_percent',
                'badge_text',
                'is_active',
                'starts_at',
                'ends_at',
                'sort_order',
                'created_at',
            ])
            ->with([
                'product' => fn($q) => $q->select(['id', 'name', 'price', 'image']),
            ])
            ->ordered()
            ->paginate(15);

        return view('admin.deals.index', compact('deals'));
    }

    public function create(): View
    {
        $products = Product::query()
            ->select(['id', 'name', 'price'])
            ->orderBy('name')
            ->get();

        return view('admin.deals.create', compact('products'));
    }

    public function store(StoreDealRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['badge_text'] = $request->filled('badge_text') ? $request->badge_text : 'تخفيض خاص';

        Deal::create($data);

        return redirect()->route('admin.deals.index')->with('success', 'تم إنشاء العرض الخاص بنجاح.');
    }

    public function edit(int $id): View
    {
        $deal = Deal::findOrFail($id);

        $products = Product::query()
            ->select(['id', 'name', 'price'])
            ->orderBy('name')
            ->get();

        return view('admin.deals.edit', compact('deal', 'products'));
    }

    public function update(UpdateDealRequest $request, int $id): RedirectResponse
    {
        $deal = Deal::findOrFail($id);
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['badge_text'] = $request->filled('badge_text') ? $request->badge_text : 'تخفيض خاص';

        $deal->update($data);

        return redirect()->route('admin.deals.index')->with('success', 'تم تحديث العرض بنجاح.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $deal = Deal::findOrFail($id);
        $deal->delete();

        return redirect()->route('admin.deals.index')->with('success', 'تم حذف العرض بنجاح.');
    }
}
