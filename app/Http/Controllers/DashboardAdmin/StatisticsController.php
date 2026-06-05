<?php

namespace App\Http\Controllers\dashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\PriceOffer;
use App\Models\Profession;

use Illuminate\Http\Request;

class StatisticsController extends Controller
{
     public function index()
    {
        return response()->json([
            'services_count'    => Service::count(),

            'careers_count'     => Career::count(),

            'users_count'       => User::where('role', 'customer')->count(),

            'workers_count'     => User::where('role', 'worker')->count(),

            'orders_count'      => Order::count(),

            'offers_count'      => PriceOffer::count(),

            'login_count'       => DB::table('personal_access_tokens')->count(),
        ]);
    }
    public function stsatisticsHomepage(){

          return response()->json([
            

            'users_count'       => User::where('role', 'customer')->count(),

            'workers_count'     => User::where('role', 'worker')->count(),

          ]);
            
    }
}
