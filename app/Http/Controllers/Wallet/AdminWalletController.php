<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopUpWalletRequest;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminWalletController extends Controller
{
    public function __construct(
        protected WalletService $service
    ) {}

    public function topUp(TopUpWalletRequest $request, $workerId)
    {
        $tx = $this->service->topUp(
            $workerId,
            $request->amount,
            Auth::id(),
            $request->note
        );

        return response()->json([
            'message' => 'Top up success',
            'data' => $tx
        ]);
    }
}
