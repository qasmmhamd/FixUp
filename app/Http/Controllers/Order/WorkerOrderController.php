<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Class WorkerOrderController
 *
 * Handles worker-specific order retrieval operations.
 * Provides matching orders based on worker services and career.
 */
class WorkerOrderController extends Controller
{
    /**
     * Order service instance.
     *
     * @var OrderService
     */
    private OrderService $orderService;

    /**
     * Inject dependencies.
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Get orders matching the authenticated worker profile.
     *
     * @return JsonResponse
     */
    public function workerOrders(): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Fetch Matching Orders
        |--------------------------------------------------------------------------
        */

        $orders = $this->orderService->getMatchingOrdersForWorker(
            Auth::user()->id
        );

        /*
        |--------------------------------------------------------------------------
        | Load Relations
        |--------------------------------------------------------------------------
        */

        $orders->load([
            'career',
            'services',
            'images',
            'address.areaAddress'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'data' => $orders
        ]);
    }
}