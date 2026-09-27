<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Branch;
use App\Services\OrderService;
use App\Models\Product;
use App\Models\User;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function create(): View
    {
        $branches = Branch::ordered()->get();
        $products = Product::query()
            ->select(['id', 'name', 'price', 'quantity', 'stock_quantity', 'image', 'is_available', 'portion_unit'])
            ->where('is_available', true)
            ->orderBy('name', 'ASC')
            ->get();
        $users = User::select(['id', 'name', 'email', 'phone'])->latest()->limit(50)->get();

        return view('admin.orders.create', compact('branches', 'products', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'delivery_type' => 'required|in:delivery,pickup',
            'branch_id' => 'nullable|exists:branches,id',
            'address' => 'nullable|string|max:500',
            'payment_method' => 'required|string|max:50',
            'status' => 'required|in:pending,under_review,confirmed,processing,ready_for_pickup,on_the_way,delivered,cancelled',
            'order_source' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.portion_type' => 'nullable|string|max:50',
            'items.*.portion_label' => 'nullable|string|max:100',
            'items.*.price' => 'required|numeric|min:0',
        ], [
            'customer_name.required' => 'يرجى إدخال اسم العميل.',
            'phone.required' => 'يرجى إدخال رقم هاتف أو واتساب العميل.',
            'items.required' => 'يرجى إضافة صنف واحد على الأقل للطلب.',
            'items.min' => 'يرجى إضافة صنف واحد على الأقل للطلب.',
        ]);

        if ($request->delivery_type === 'delivery' && empty(trim($request->address ?? ''))) {
            return back()->withInput()->withErrors(['address' => 'يرجى تحديد عنوان التوصيل داخل منطقة العامرية.']);
        }

        $order = DB::transaction(function () use ($request) {
            $currency = function_exists('get_active_currency') ? get_active_currency() : 'EGP';
            $exchangeRate = function_exists('get_exchange_rate') ? get_exchange_rate() : 1.0;

            $sourcePrefix = match($request->order_source) {
                'whatsapp' => 'WA',
                'phone' => 'TEL',
                'branch' => 'POS',
                default => 'ADM',
            };

            $publicOrderId = 'TWH-' . $sourcePrefix . '-' . strtoupper(Str::random(5)) . '-' . date('d');
            $orderNumber = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);

            // Compute total
            $totalPrice = 0.0;
            foreach ($request->items as $item) {
                $qty = (float)($item['quantity'] ?? 1);
                $unitPrice = (float)($item['price'] ?? 0);
                $totalPrice += ($qty * $unitPrice);
            }

            // Append order source to notes
            $sourceLabel = match($request->order_source) {
                'whatsapp' => 'طلب واتساب مباشر (Manual WhatsApp)',
                'phone' => 'طلب هاتف مباشر (Manual Phone)',
                'branch' => 'طلب كاشير / فرع (In-Store)',
                default => 'طلب يدوي من لوحة التحكم',
            };
            $fullNotes = trim(($request->notes ? $request->notes . " | " : "") . "[مصدر الطلب: {$sourceLabel}]");

            $order = Order::create([
                'public_order_id' => $publicOrderId,
                'order_number' => $orderNumber,
                'user_id' => $request->user_id ?: null,
                'customer_name' => trim($request->customer_name),
                'total_price' => $totalPrice,
                'status' => $request->status,
                'delivery_type' => $request->delivery_type,
                'branch_id' => $request->branch_id ?: null,
                'address' => $request->address ?: ($request->delivery_type === 'pickup' ? 'استلام من الفرع' : ''),
                'phone' => trim($request->phone),
                'payment_method' => $request->payment_method,
                'notes' => $fullNotes,
                'currency' => $currency,
                'exchange_rate' => $exchangeRate,
                'confirmed_at' => in_array($request->status, ['confirmed', 'processing', 'ready_for_pickup', 'on_the_way', 'delivered']) ? now() : null,
                'delivered_at' => $request->status === 'delivered' ? now() : null,
            ]);

            foreach ($request->items as $itemData) {
                $qty = (float)($itemData['quantity'] ?? 1);
                $unitPrice = (float)($itemData['price'] ?? 0);
                $itemTotal = round($qty * $unitPrice, 2);

                $order->orderItems()->create([
                    'product_id' => $itemData['product_id'],
                    'price' => $unitPrice,
                    'total_price' => $itemTotal,
                    'quantity' => max(1, (int)round($qty)),
                    'portion_type' => $itemData['portion_type'] ?? 'piece',
                    'portion_label' => $itemData['portion_label'] ?? null,
                    'custom_amount' => $qty,
                    'notes' => $itemData['notes'] ?? null,
                ]);

                // Deduct stock if product exists
                $product = Product::lockForUpdate()->find($itemData['product_id']);
                if ($product) {
                    $newStock = max(0, $product->stock_quantity - (int)ceil($qty));
                    $product->update([
                        'stock_quantity' => $newStock,
                        'is_available' => $newStock > 0,
                    ]);
                }
            }

            return $order;
        });

        return redirect()->route('orders.show', $order->id)->with('success', __('تم تسجيل الطلب اليدوي بنجاح برقم: ') . $order->public_order_id);
    }

    public function index(Request $request): View
    {
        $query = Order::query()
            ->select([
                'id',
                'public_order_id',
                'order_number',
                'user_id',
                'customer_name',
                'total_price',
                'status',
                'delivery_type',
                'branch_id',
                'address',
                'phone',
                'payment_method',
                'payment_proof_image',
                'instapay_reference',
                'notes',
                'cancel_reason',
                'currency',
                'created_at',
            ])
            ->with([
                'user' => fn($q) => $q->select('id', 'name', 'email', 'phone'),
                'branch' => fn($q) => $q->select('id', 'name'),
                'orderItems.product',
            ])
            ->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('delivery_type')) {
            $query->where('delivery_type', $request->delivery_type);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('public_order_id', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('instapay_reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15)->withQueryString();
        $branches = Branch::select('id', 'name')->ordered()->get();

        return view('admin.orders.index', compact('orders', 'branches'));
    }

    public function show(int $id): View
    {
        $order = Order::query()
            ->with([
                'user' => fn($q) => $q->select('id', 'name', 'email', 'phone'),
                'branch' => fn($q) => $q->select('id', 'name', 'address', 'phone'),
                'orderItems.product',
            ])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, int $id): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $order = Order::findOrFail($id);
        $this->orderService->updateStatus($order, $request->status, $request->cancel_reason);

        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            $statusColors = [
                'under_review' => 'bg-amber-100 text-amber-900 border-amber-300 font-bold',
                'pending' => 'bg-amber-100 text-amber-900 border-amber-300 font-bold',
                'confirmed' => 'bg-blue-100 text-blue-900 border-blue-300 font-bold',
                'processing' => 'bg-indigo-100 text-indigo-900 border-indigo-300 font-bold',
                'ready_for_pickup' => 'bg-cyan-100 text-cyan-900 border-cyan-300 font-bold',
                'on_the_way' => 'bg-purple-100 text-purple-900 border-purple-300 font-bold',
                'shipped' => 'bg-purple-100 text-purple-900 border-purple-300 font-bold',
                'delivered' => 'bg-emerald-100 text-emerald-900 border-emerald-400 font-bold',
                'cancelled' => 'bg-rose-100 text-rose-900 border-rose-300 font-bold',
            ];
            $statusLabels = [
                'under_review' => '⏳ قيد المراجعة',
                'pending' => '📦 قيد الانتظار',
                'confirmed' => '✓ تم التأكيد',
                'processing' => '⚙️ قيد التجهيز',
                'ready_for_pickup' => '🏬 جاهز للاستلام',
                'on_the_way' => '🛵 في الطريق مع المندوب',
                'shipped' => '🚚 تم الشحن',
                'delivered' => '✅ تم التسليم بنجاح',
                'cancelled' => '❌ ملغي',
            ];

            return response()->json([
                'success' => true,
                'message' => __('تم تحديث حالة الطلب إلى (:status) بنجاح ومزامنة المخزون.', ['status' => $statusLabels[$order->status] ?? $order->status]),
                'status' => $order->status,
                'status_label' => $statusLabels[$order->status] ?? $order->status,
                'color_class' => $statusColors[$order->status] ?? 'bg-gray-50 text-gray-700 border-gray-200',
            ]);
        }

        return redirect()->back()->with('success', __('تم تحديث حالة الطلب بنجاح ومزامنة المخزون تلقائياً.'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
            // If order was active (not cancelled), restore inventory before deleting
            if ($order->status !== Order::STATUS_CANCELLED) {
                $this->orderService->restoreStockForOrder($order);
            }

            $order->orderItems()->delete();
            $order->delete();
        });

        return redirect()->route('admin.orders.index')->with('success', __('تم حذف الطلب واسترجاع المخزون بنجاح.'));
    }
}
