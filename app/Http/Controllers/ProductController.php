<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\ProductVariant;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()
            ->select([
                'id',
                'name',
                'sku',
                'category_id',
                'brand_id',
                'gender',
                'season',
                'image',
                'price',
                'discount_price',
                'stock_quantity',
                'featured',
                'is_active',
                'is_available',
                'created_at',
            ])
            ->with(['category:id,name', 'brand:id,name'])
            ->withCount('variants')
            ->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('featured')) {
            $query->where('featured', $request->boolean('featured'));
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::select(['id', 'name', 'parent_id'])->ordered()->get();
        $brands = Brand::select(['id', 'name'])->ordered()->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create(): View
    {
        $categories = Category::select(['id', 'name', 'parent_id'])->ordered()->get();
        $brands = Brand::select(['id', 'name'])->active()->ordered()->get();
        $attributes = Attribute::select(['id', 'name', 'code', 'type'])
            ->with(['values' => fn($q) => $q->select(['id', 'attribute_id', 'value', 'color_code', 'sort_order'])->ordered()])
            ->ordered()
            ->get();

        return view('admin.products.create', compact('categories', 'brands', 'attributes'));
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
        $data['is_weighted'] = false;
        $data['unit_type'] = 'piece';
        $data['created_by'] = auth()->id();

        DB::transaction(function () use ($data, $request) {
            $variantsData = $data['variants'] ?? null;
            unset($data['variants']);

            $product = Product::create($data);

            if (!empty($variantsData) && is_array($variantsData)) {
                $totalStock = 0;
                foreach ($variantsData as $index => $varItem) {
                    if (empty($varItem['attribute_values']) && empty($varItem['sku'])) {
                        continue;
                    }

                    $varSku = !empty($varItem['sku']) 
                        ? $varItem['sku'] 
                        : ($product->sku ? $product->sku . '-V' . ($index + 1) : 'SKU-' . $product->id . '-V' . ($index + 1));

                    $varStock = (int)($varItem['stock_quantity'] ?? 0);
                    $totalStock += $varStock;

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $varSku,
                        'barcode' => $varItem['barcode'] ?? null,
                        'price' => !empty($varItem['price']) ? (float)$varItem['price'] : null,
                        'stock_quantity' => $varStock,
                        'is_active' => true,
                    ]);

                    if (!empty($varItem['attribute_values']) && is_array($varItem['attribute_values'])) {
                        $variant->attributeValues()->sync($varItem['attribute_values']);
                    }
                }

                if ($totalStock > 0) {
                    $product->update([
                        'stock_quantity' => $totalStock,
                        'quantity' => $totalStock,
                        'is_available' => true,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', __('تمت إضافة منتج الأزياء بنجاح.'));
    }

    public function edit(int $id): View
    {
        $product = Product::with([
            'category:id,name', 
            'brand:id,name', 
            'variants.attributeValues',
        ])->findOrFail($id);

        $categories = Category::select(['id', 'name', 'parent_id'])->ordered()->get();
        $brands = Brand::select(['id', 'name'])->active()->ordered()->get();
        $attributes = Attribute::select(['id', 'name', 'code', 'type'])
            ->with(['values' => fn($q) => $q->select(['id', 'attribute_id', 'value', 'color_code', 'sort_order'])->ordered()])
            ->ordered()
            ->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'attributes'));
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
        $data['is_weighted'] = false;
        $data['unit_type'] = 'piece';
        $data['updated_by'] = auth()->id();

        DB::transaction(function () use ($product, $data, $request) {
            $variantsData = $data['variants'] ?? null;
            unset($data['variants']);

            $product->update($data);

            if ($variantsData !== null && is_array($variantsData)) {
                $keptVariantIds = [];
                $totalStock = 0;

                foreach ($variantsData as $index => $varItem) {
                    if (empty($varItem['attribute_values']) && empty($varItem['sku'])) {
                        continue;
                    }

                    $varSku = !empty($varItem['sku']) 
                        ? $varItem['sku'] 
                        : ($product->sku ? $product->sku . '-V' . ($index + 1) : 'SKU-' . $product->id . '-V' . ($index + 1));

                    $varStock = (int)($varItem['stock_quantity'] ?? 0);
                    $totalStock += $varStock;

                    $variant = null;
                    if (!empty($varItem['id'])) {
                        $variant = ProductVariant::where('product_id', $product->id)->find($varItem['id']);
                    }

                    if ($variant) {
                        $variant->update([
                            'sku' => $varSku,
                            'barcode' => $varItem['barcode'] ?? $variant->barcode,
                            'price' => !empty($varItem['price']) ? (float)$varItem['price'] : null,
                            'stock_quantity' => $varStock,
                        ]);
                    } else {
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $varSku,
                            'barcode' => $varItem['barcode'] ?? null,
                            'price' => !empty($varItem['price']) ? (float)$varItem['price'] : null,
                            'stock_quantity' => $varStock,
                            'is_active' => true,
                        ]);
                    }

                    $keptVariantIds[] = $variant->id;

                    if (!empty($varItem['attribute_values']) && is_array($varItem['attribute_values'])) {
                        $variant->attributeValues()->sync($varItem['attribute_values']);
                    }
                }

                // Delete variants removed by admin
                if (!empty($keptVariantIds)) {
                    ProductVariant::where('product_id', $product->id)
                        ->whereNotIn('id', $keptVariantIds)
                        ->delete();

                    if ($totalStock > 0) {
                        $product->update([
                            'stock_quantity' => $totalStock,
                            'quantity' => $totalStock,
                            'is_available' => true,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', __('تم تحديث بيانات منتج الأزياء بنجاح.'));
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
