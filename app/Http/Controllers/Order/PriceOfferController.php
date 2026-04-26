<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePriceOfferRequest;
use App\Services\PriceOfferService;

class PriceOfferController extends Controller
{
      public function __construct(
        private PriceOfferService $service
    ) {}

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
