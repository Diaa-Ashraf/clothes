<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage-categories'])->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::query()
            ->select(['id', 'name', 'slug', 'image', 'created_at'])
            ->withCount('products')
            ->latest()
            ->paginate(15);

        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->input('name') ?? 'category-' . time());
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets/uploads/category', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', __('تم إنشاء القسم بنجاح!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if (!empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
        }

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('assets/uploads/category', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', __('تم تحديث القسم بنجاح!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', __('تم حذف القسم بنجاح!'));
    }
}
