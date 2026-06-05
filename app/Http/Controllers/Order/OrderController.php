<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * Class OrderController
 *
 * Handles order lifecycle operations:
 * - Creating orders
 * - Listing user orders
 * - Fetching notifications
 * - Testing notification system
 */
class OrderController extends Controller
{
    /**
     * Order service instance.
     *
     * @var OrderService
     */
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Create a new order.
     *
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Create Order
        |--------------------------------------------------------------------------
        */

        $order = $this->orderService->create(
            $request->validated(),
            Auth::id()
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'Order created successfully',
            'data'    => $order
        ], 201);
    }

    /**
     * Get authenticated user orders.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Fetch Orders
        |--------------------------------------------------------------------------
        */

         $orders = $this->orderService->getUserOrders(
                Auth::id(),
                $request->status
            );

            return response()->json([
                'data' => OrderResource::collection($orders)
            ]);
    }

    /**
     * Get user notifications.
     *
     * @return JsonResponse
     */
    public function notifications(): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Fetch Notifications
        |--------------------------------------------------------------------------
        */

        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'data' => $notifications
        ]);
    }

      
    /**
     * Cancel order (owner only).
     *
     * Cancels an order if it belongs to the authenticated user.
     *
     * @param int $orderId
     * @return JsonResponse
     *
     * POST /api/orders/{orderId}/cancel
     */
    public function cancel(int $orderId): JsonResponse
    {
        $order = $this->orderService->cancelOrder(
            $orderId,
            Auth::id()
        );

        return response()->json([
            'message' => 'Order cancelled successfully',
            'data' => $order
        ]);
    }
}