<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CompleteOrderController extends Controller
{

    public function requestCompletion(Order $order)
    {
        $updated = app(OrderService::class)
            ->requestCompletion($order->id, Auth::id());

        return response()->json([
            'message' => 'Completion requested successfully',
            'order' => $updated
        ]);
    }
    public function complete($orderId)
    {
        $order = app(OrderService::class)
            ->markAsCompleted($orderId, Auth::id());

        return response()->json([
            'message' => 'Order marked as completed',
            'order' => $order
        ]);
    }
}
