<?php


namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->create(
            $request->validated(),
            Auth::id()
        );

        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }

    public function index()
    {
        return Auth::user()
            ->order()
            ->with(['services', 'address', 'offers', 'images'])
            ->latest()
            ->get();
    }

    /**
     * 🔥 Notifications (DB only)
     */
    public function notifications()
    {
        return Auth::user()
            ->notifications()
            ->latest()
            ->get();
    }

    /**
     * 🧪 TEST endpoint (مهم جدًا للاختبار)
     */
    public function testNotification()
    {
        app(\App\Services\NotificationService::class)->send(
            Auth::user(),
            "Test 🔥",
            "Notification system is working",
            "test",
            ["time" => now()]
        );

        return response()->json([
            'message' => 'Test notification sent'
        ]);
    }
} 