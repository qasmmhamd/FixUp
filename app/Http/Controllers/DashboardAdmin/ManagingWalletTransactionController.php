<?php

namespace App\Http\Controllers\dashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class ManagingWalletTransactionController extends Controller
{
    public function index()
{
    $transactions = WalletTransaction::query()
        ->with([
            'order',
            'order.user',
            'wallet',
            'wallet.user',
            'wallet.user',
        ])
        ->latest()
        ->get();

    return response()->json($transactions);
}
}
