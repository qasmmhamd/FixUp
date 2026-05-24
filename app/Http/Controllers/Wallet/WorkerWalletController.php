<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WorkerWalletController extends Controller
{
    public function wallet()
    {
        return Auth::user()->wallet;
    }

    public function transactions()
    {
        return WalletTransaction::whereHas('wallet', function ($q) {
            $q->where('user_id', Auth::id());
        })->latest()->paginate(20);
    }
}
