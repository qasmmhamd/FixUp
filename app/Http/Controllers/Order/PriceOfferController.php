<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceOfferRequest;
use App\Services\PriceOfferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceOfferController extends Controller
{
    public function __construct(
        private PriceOfferService $service
    ) {}

    /**
     * 🔥 إنشاء عرض سعر
     */
    public function store(StorePriceOfferRequest $request)
    {
        $offer = $this->service->create(
            $request->validated()
        );

        return response()->json([
            'status' => true,
            'message' => 'Offer created successfully',
            'data' => $offer
        ], 201);
    }

    /**
     * 🔥 عرض عروض السعر الخاصة بالزبون (مؤمّن)
     */
    public function index($orderId, Request $request)
    {
        $order = $this->service->getOrderWithOffers(
            $orderId,
            Auth::id()
        );

        return response()->json([
            'order_id' => $order->id,
            'price_offers' => $order->priceOffers()->latest()->get()
        ]);
    }

    /**
     * 🧪 Test notification (مهم للاختبار)
     */
    public function test()
    {
        app(\App\Services\NotificationService::class)->send(
            Auth::user(),
            "Test Offer 🔥",
            "Testing price offer notification system",
            "test",
            [
                "time" => now()
            ]
        );

        return response()->json([
            'message' => 'Test notification sent'
        ]);
    }
    public function notifications()
{
    return response()->json([
        'data' => Auth::user()
            ->notifications()
            ->where('type', 'price_offer')
            ->latest()
            ->get()
    ]);
}
}