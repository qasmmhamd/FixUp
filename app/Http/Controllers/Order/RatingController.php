<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(StoreRatingRequest $request)
    {
        $rating = $this->orderService->createRating(
            Auth::id(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Rating added successfully.',
            'data' => $rating,
        ], 201);
    }
}
