<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAttributeRequest;
use App\Http\Requests\Admin\StoreAttributeValueRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttributeController extends Controller
{
    public function index(): View
    {
        $attributes = Attribute::query()
            ->select(['id', 'name', 'code', 'type', 'is_filterable', 'sort_order'])
            ->with(['values'])
            ->ordered()
            ->paginate(15);

        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(StoreAttributeRequest $request): RedirectResponse
    {
        Attribute::create($request->validated());

        return redirect()->route('admin.attributes.index')->with('success', 'تمت إضافة الخاصية بنجاح.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $attribute = Attribute::findOrFail($id);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'in:button,color,select,text'],
            'is_filterable' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'اسم الخاصية مطلوب.',
        ]);

        $attribute->update($validated);

        return redirect()->route('admin.attributes.index')->with('success', 'تم تحديث الخاصية بنجاح.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

        return redirect()->route('admin.attributes.index')->with('success', 'تم حذف الخاصية وجميع قيمها بنجاح.');
    }

    public function storeValue(StoreAttributeValueRequest $request): RedirectResponse
    {
        AttributeValue::create($request->validated());

        return redirect()->route('admin.attributes.index')->with('success', 'تمت إضافة القيمة بنجاح.');
    }

    public function destroyValue(int $id): RedirectResponse
    {
        $value = AttributeValue::findOrFail($id);
        $value->delete();

        return redirect()->route('admin.attributes.index')->with('success', 'تم حذف القيمة بنجاح.');
    }
}
