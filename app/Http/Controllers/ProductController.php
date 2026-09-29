<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()
            ->select([
                'id',
                'name',
                'category_id',
                'image',
                'price',
                'discount_price',
                'stock_quantity',
                'featured',
                'is_active',
                'is_available',
                'is_weighted',
                'unit_type',
                'created_at',
            ])
            ->with(['category'])
            ->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('featured')) {
            $query->where('featured', $request->boolean('featured'));
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request, \App\Services\ImageService $imageService): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $imageService->uploadAsWebp($request->file('image'), 'products');
        } else {
            $data['image'] = 'default.jpg';
        }

        $data['slug'] = Str::slug($request->input('name') ?? 'product-' . time()) . '-' . time();
        $data['stock_quantity'] = (int)($data['stock_quantity'] ?? $data['quantity'] ?? 0);
        $data['quantity'] = $data['stock_quantity'];
        $data['is_available'] = $data['stock_quantity'] > 0;
        $data['featured'] = $request->boolean('featured', false);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_weighted'] = $request->boolean('is_weighted', false);
        $data['unit_type'] = $request->input('unit_type', 'piece');
        $data['created_by'] = auth()->id();

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', __('تمت إضافة المنتج بنجاح.'));
    }

    public function edit(int $id): View
    {
        $product = Product::with(['category'])->findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, int $id, \App\Services\ImageService $imageService): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imageService->deleteImage($product->image);
            $data['image'] = $imageService->uploadAsWebp($request->file('image'), 'products');
        }

        $stock = (int)($data['stock_quantity'] ?? $data['quantity'] ?? $product->stock_quantity);
        $data['stock_quantity'] = $stock;
        $data['quantity'] = $stock;
        $data['is_available'] = $stock > 0;
        $data['featured'] = $request->boolean('featured', $product->featured);
        $data['is_active'] = $request->boolean('is_active', $product->is_active);
        $data['is_weighted'] = $request->boolean('is_weighted', false);
        $data['unit_type'] = $request->input('unit_type', 'piece');
        $data['updated_by'] = auth()->id();

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', __('تم تحديث بيانات المنتج بنجاح.'));
    }

    public function toggleFeatured(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $product->update(['featured' => !$product->featured]);

        $msg = $product->featured ? __('تم تمييز المنتج لعرضه في الصفحة الرئيسية.') : __('تم إلغاء تمييز المنتج.');
        return redirect()->back()->with('success', $msg);
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', __('تم حذف المنتج بنجاح.'));
    }
}
