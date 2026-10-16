<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(Request $request): View
    {
        $brands = Brand::query()
            ->select(['id', 'name', 'slug', 'logo', 'website', 'is_active', 'sort_order', 'created_at'])
            ->withCount('products')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(StoreBrandRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('name'));
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($request->input('sort_order', 0));

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'تمت إضافة العلامة التجارية بنجاح.');
    }

    public function edit(int $id): View
    {
        $brand = Brand::query()
            ->select(['id', 'name', 'slug', 'logo', 'description', 'website', 'is_active', 'sort_order'])
            ->findOrFail($id);

        return view('admin.brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, int $id): RedirectResponse
    {
        $brand = Brand::findOrFail($id);
        $data = $request->validated();
        $data['slug'] = $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('name'));
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($request->input('sort_order', 0));

        if ($request->hasFile('logo')) {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'تم تحديث بيانات العلامة التجارية بنجاح.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'تم حذف العلامة التجارية بنجاح.');
    }
}
