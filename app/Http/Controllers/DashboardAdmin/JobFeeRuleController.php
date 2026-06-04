<?php

namespace App\Http\Controllers\dashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\JobFeeRule;
use Illuminate\Http\Request;

class JobFeeRuleController extends Controller
{
    public function index()
    {
        $rules = JobFeeRule::with('career')->get();

        return response()->json([
            'message' => 'Job fee rules retrieved successfully',
            'data' => $rules
        ]);
    }
}
