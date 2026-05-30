<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopUpWalletRequest;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

/**
 * Class AdminWalletController
 *
 * Responsible for admin-level wallet operations such as topping up worker wallets.
 */
class AdminWalletController extends Controller
{
    /**
     * Wallet service instance.
     *
     * @var WalletService
     */
    public function __construct(
        protected WalletService $service
    ) {}

    /**
     * Top up a worker wallet balance.
     *
     * @param TopUpWalletRequest $request Validated request containing amount and note
     * @param int $workerId Target worker ID
     * @return JsonResponse
     */
    public function topUp(
        TopUpWalletRequest $request,
        int $workerId
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | Execute Wallet Top-Up
        |--------------------------------------------------------------------------
        */

        $tx = $this->service->topUp(
            workerId: $workerId,
            amount: $request->amount,
            adminId: Auth::id(),
            note: $request->note
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'Top up success',
            'data'    => $tx
        ]);
    }
}