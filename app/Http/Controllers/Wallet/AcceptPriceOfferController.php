<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Services\AcceptPriceOfferService;
use Illuminate\Http\Request;

class AcceptPriceOfferController extends Controller
{
 public function accept(
        int $orderId,
        int $offerId,
        AcceptPriceOfferService $service
    ) {

        $order = $service->execute(
            orderId: $orderId,
            offerId: $offerId
        );

        return response()->json([
            'message' => 'Offer accepted successfully',
            'order' => $order
        ]);
    }
    }
