<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage-orders'])->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index(Request $request): View
    {
        $query = Client::query()
            ->select(['id', 'name', 'email', 'phone', 'address', 'created_at'])
            ->withCount('orders')
            ->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $clients = $query->paginate(15)->withQueryString();

        return view('admin.clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('admin.clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        Client::create($data);

        return redirect()->route('admin.clients.index')->with('success', 'تمت إضافة العميل بنجاح.');
    }

    public function show(int $id): View
    {
        $client = Client::query()
            ->select(['id', 'name', 'email', 'phone', 'address', 'created_at'])
            ->with([
                'orders' => fn($q) => $q->select(['id', 'user_id', 'total_price', 'status', 'created_at'])->latest(),
            ])
            ->findOrFail($id);

        return view('admin.clients.show', compact('client'));
    }

    public function edit(int $id): View
    {
        $client = Client::select(['id', 'name', 'email', 'phone', 'address'])->findOrFail($id);

        return view('admin.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, int $id): RedirectResponse
    {
        $client = Client::findOrFail($id);
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $client->update($data);

        return redirect()->route('admin.clients.index')->with('success', 'تم تحديث بيانات العميل بنجاح.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'تم حذف العميل بنجاح.');
    }
}
