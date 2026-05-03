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

   

    /**
     * إنشاء طلب جديد
     */
    public function store(StoreOrderRequest $request)
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
}   