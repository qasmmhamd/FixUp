<?php
/*
namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceRequest;
use App\Services\NotificationService;
use App\Models\Worker;
use Illuminate\Http\Request;



class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

   

    /**
     * إنشاء طلب جديد
     */
  /*  public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->create(
            $request->validated(),
            $request->user()->id
        );
       


        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }
     public function index()
{
    $orders = Auth::user()
    ->orders()
    ->with(['services', 'address', 'offers', 'images'])
    ->get();
    return response()->json($orders);
}
public function createRequest(Request $request)
{
    $serviceRequest = Order::create([
        'user_id' => Auth::id(),
    ]);

    $workers = Worker::with('user')
        ->where('status', 'active')
        ->get();

    foreach ($workers as $worker) {

        if ($worker->user) {

            app(NotificationService::class)->send(
                $worker->user,
                "طلب جديد 🛠️",
                "يوجد طلب خدمة جديد",
                "new_request",
                [
                    "request_id" => $serviceRequest->id
                ]
            );
        }
    }

    return response()->json(['success' => true]);
}
public function indx()
{
    return Auth::user()
        ->notifications()
        ->latest()
        ->get();
}
} 
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
            ->orders()
            ->with(['services', 'address', 'offers', 'images'])
            ->get();
    }

    public function notifications()
    {
        return Auth::user()
            ->notifications()
            ->latest()
            ->get();
    }
} */

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
            ->orders()
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