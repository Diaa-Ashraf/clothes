<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage-products'])->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index(): \Illuminate\View\View
    {
        $stocks = Stock::query()
            ->with(['product'])
            ->latest()
            ->paginate(15);

        return view('admin.stocks.index', compact('stocks'));
    }

public function create(Request $request)
{
    $q = $request->q;

    $products = Product::when($q, function ($query) use ($q) {
        $query->where('name', 'LIKE', "%{$q}%")
              ->orWhere('description', 'LIKE', "%{$q}%");
    })->paginate(5);

    return view('admin.stocks.create', compact('products', 'q'));
}


    public function store(StoreStockRequest $request)
    {
        // حماية: منع إنشاء مخزون لنفس المنتج مرتين
        if (Stock::where('product_id', $request->product_id)->exists()) {
            return back()->withErrors(['product_id' => 'This product already has stock']);
        }

        Stock::create($request->validated());
        return redirect()->route('stocks.index')->with('success', 'Stock created successfully');
    }

    public function show(Stock $stock)
    {
        $products = Product::all();
        return view('admin.stocks.show', compact('stock', 'products'));
    }

    public function edit(Stock $stock)
    {
        $products = Product::all();
        return view('admin.stocks.edit', compact('stock', 'products'));
    }

    public function update(UpdateStockRequest $request, Stock $stock)
    {
        $stock->update($request->validated());
        return redirect()->route('stocks.index')->with('success', 'Stock updated successfully');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('stocks.index')->with('success', 'Stock deleted successfully');
    }
}
