<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

/**
 * Class WorkerWalletController
 *
 * Handles worker wallet overview and transaction history retrieval.
 */
class WorkerWalletController extends Controller
{
    /**
     * Get authenticated worker wallet details.
     *
     * @return mixed
     */
    public function wallet()
    {
        /*
        |--------------------------------------------------------------------------
        | Return Wallet
        |--------------------------------------------------------------------------
        */

        return Auth::user()->wallet;
    }

    /**
     * Get authenticated worker wallet transactions.
     *
     * @return JsonResponse
     */
    public function transactions(): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Fetch Transactions
        |--------------------------------------------------------------------------
        */

        $transactions = WalletTransaction::whereHas('wallet', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->latest()
            ->paginate(20);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'data' => $transactions
        ]);
    }
}