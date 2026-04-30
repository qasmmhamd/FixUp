<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePriceOfferRequest;
use App\Services\PriceOfferService;
use App\Http\Resources\PriceOfferResource;
use App\Notifications\NewPriceOfferNotification;


class PriceOfferController extends Controller
{
      public function __construct(
        private PriceOfferService $service
    ) {}
     public function index($orderId, Request $request)
    {
        $order = $this->service->getOrderWithOffers(
            $orderId,
          $request->user()->id
        );

        return response()->json([
            'order_id' => $order->id,
            'price_offers' => PriceOfferResource::collection($order->priceOffers),
        ]);
    }

    

    public function store(StorePriceOfferRequest $request)
    {
        $offer = $this->service->create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Offer created successfully',
            'data' => $offer
        ]);
    }
    

}
