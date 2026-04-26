<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\WorkerService;
use Illuminate\Support\Facades\Auth;
class WorkerOrderController extends Controller
{
    private OrderService $orderService;

public function __construct(OrderService $orderService)
{
    $this->orderService = $orderService;
}
public function workerOrders()
{
    $orders = $this->orderService->getMatchingOrdersForWorker(Auth::user()->id);

    return response()->json([
        'data' => $orders
    ]);
}
}
