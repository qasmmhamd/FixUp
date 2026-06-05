<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceOfferRequest;
use App\Services\PriceOfferService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PriceOfferController
 *
 * Handles price offer operations:
 * - Creating offers (worker side)
 * - Viewing offers (customer & worker)
 * - Filtering accepted offers
 * - Notifications related to offers
 * - Testing notification system
 */
class PriceOfferController extends Controller
{
    /**
     * Price offer service instance.
     *
     * @var PriceOfferService
     */
    public function __construct(
        private PriceOfferService $service
    ) {}

    /**
     * Create a new price offer.
     *
     * @param StorePriceOfferRequest $request
     * @return JsonResponse
     */
    public function store(StorePriceOfferRequest $request): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Create Offer
        |--------------------------------------------------------------------------
        */

        $offer = $this->service->create(
            $request->validated()
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'status'  => true,
            'message' => 'Offer created successfully',
            'data'    => $offer
        ], 201);
    }

    /**
     * Get price offers for a specific order (customer side).
     *
     * @param int $orderId
     * @param Request $request
     * @return JsonResponse
     */
    public function index(int $orderId, Request $request): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Fetch Order With Offers
        |--------------------------------------------------------------------------
        */

        $order = $this->service->getOrderWithOffers(
            $orderId,
            Auth::id()
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'order_id'     => $order->id,
            'price_offers' => $order->priceOffers()->latest()->get()
        ]);
    }

    /**
     * Get all offers for authenticated worker.
     *
     * @return JsonResponse
     */
    public function myOffers(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data'   => $this->service->getWorkerOffers(
                Auth::id()
            )
        ]);
    }

    /**
     * Get accepted offers for worker.
     *
     * @return JsonResponse
     */
    public function acceptedOffers(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data'   => $this->service->getAcceptedOffers(
                Auth::id()
            )
        ]);
    }

   
    

    /**
     * Get price offer notifications for authenticated user.
     *
     * @return JsonResponse
     */
    public function notifications(): JsonResponse
    {
        return response()->json([
            'data' => Notification::where('user_id', Auth::id())
                ->where('type', 'price_offer')
                ->latest()
                ->get()
        ]);
    }
}