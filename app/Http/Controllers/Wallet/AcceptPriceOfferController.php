<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Services\AcceptPriceOfferService;
use Illuminate\Http\JsonResponse;

/**
 * Class AcceptPriceOfferController
 *
 * Handles accepting a price offer and finalizing the order process.
 * Acts as a thin layer between HTTP request and business logic service.
 */
class AcceptPriceOfferController extends Controller
{
    /**
     * Accept a price offer for a specific order.
     *
     * @param int $orderId The ID of the order
     * @param int $offerId The ID of the price offer
     * @param AcceptPriceOfferService $service Business logic service
     * @return JsonResponse
     */
    public function accept(
        int $orderId,
        int $offerId,
        AcceptPriceOfferService $service
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | Execute Business Logic
        |--------------------------------------------------------------------------
        */

        $order = $service->execute(
            orderId: $orderId,
            offerId: $offerId
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'Offer accepted successfully',
            'order'   => $order
        ]);
    }
}