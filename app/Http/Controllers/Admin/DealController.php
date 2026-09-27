<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Product;
use Illuminate\Http\Request;

class DealController extends Controller
{
    /**
     * Display a listing of deals
     */
    public function index()
    {
        $deals = Deal::with('product')->ordered()->paginate(15);
        return view('admin.deals.index', compact('deals'));
    }

    /**
     * Show form for creating new deal
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.deals.create', compact('products'));
    }

    /**
     * Store a new deal
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percent' => 'required|integer|min:1|max:99',
            'original_price' => 'required|numeric|min:0',
            'deal_price' => 'required|numeric|min:0',
            'badge_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['badge_text'] = $request->badge_text ?? 'عرض لمدة محدودة';

        Deal::create($data);

        return redirect()->route('deals.index')->with('success', __('Deal created successfully!'));
    }

    /**
     * Show form for editing deal
     */
    public function edit(Deal $deal)
    {
        $products = Product::all();
        return view('admin.deals.edit', compact('deal', 'products'));
    }

    /**
     * Update deal
     */
    public function update(Request $request, Deal $deal)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percent' => 'required|integer|min:1|max:99',
            'original_price' => 'required|numeric|min:0',
            'deal_price' => 'required|numeric|min:0',
            'badge_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $deal->update($data);

        return redirect()->route('deals.index')->with('success', __('Deal updated successfully!'));
    }

    /**
     * Delete deal
     */
    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('deals.index')->with('success', __('Deal deleted successfully!'));
    }
}
