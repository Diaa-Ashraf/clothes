<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Product;
use App\Services\BundleService;
use App\Http\Requests\Admin\StoreBundleRequest;
use App\Http\Requests\Admin\UpdateBundleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class BundleController extends Controller
{
    public function __construct(
        protected BundleService $bundleService
    ) {}

    public function index(): View
    {
        $bundles = Bundle::query()
            ->select([
                'id',
                'name',
                'bundle_price',
                'original_total',
                'discount_percent',
                'is_active',
                'starts_at',
                'ends_at',
                'created_at',
            ])
            ->withCount('products')
            ->ordered()
            ->paginate(10);

        return view('admin.bundles.index', compact('bundles'));
    }

    public function create(): View
    {
        $products = Product::query()
            ->select(['id', 'name', 'price'])
            ->active()
            ->get();

        return view('admin.bundles.create', compact('products'));
    }

    public function store(StoreBundleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('bundles', 'public');
        }

        $bundle = Bundle::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'bundle_price' => $data['bundle_price'],
            'is_active' => $request->boolean('is_active', true),
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $productData = [];
        foreach ($request->products as $p) {
            $productData[$p['id']] = $p['quantity'] ?? 1;
        }

        $this->bundleService->syncBundle($bundle, $productData);

        return redirect()->route('admin.bundles.index')->with('success', __('تم إنشاء العرض المجمع بنجاح.'));
    }

    public function edit(int $id): View
    {
        $bundle = Bundle::with('products')->findOrFail($id);
        $products = Product::query()
            ->select(['id', 'name', 'price'])
            ->active()
            ->get();

        return view('admin.bundles.edit', compact('bundle', 'products'));
    }

    public function update(UpdateBundleRequest $request, int $id): RedirectResponse
    {
        $bundle = Bundle::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($bundle->image && Storage::disk('public')->exists($bundle->image)) {
                Storage::disk('public')->delete($bundle->image);
            }
            $data['image'] = $request->file('image')->store('bundles', 'public');
        }

        $bundle->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? $bundle->image,
            'bundle_price' => $data['bundle_price'],
            'is_active' => $request->boolean('is_active', true),
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $productData = [];
        foreach ($request->products as $p) {
            $productData[$p['id']] = $p['quantity'] ?? 1;
        }

        $this->bundleService->syncBundle($bundle, $productData);

        return redirect()->route('admin.bundles.index')->with('success', __('تم تحديث العرض المجمع بنجاح.'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $bundle = Bundle::findOrFail($id);
        if ($bundle->image && Storage::disk('public')->exists($bundle->image)) {
            Storage::disk('public')->delete($bundle->image);
        }
        $bundle->delete();

        return redirect()->route('admin.bundles.index')->with('success', __('تم حذف العرض المجمع بنجاح.'));
    }
}
