<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobFeeRuleRequest;
use App\Models\JobFeeRule;
use Illuminate\Http\Request;

class JobFeeController extends Controller
{
    public function store(JobFeeRuleRequest $request)
    {
        return JobFeeRule::create($request->validated());
    }

    public function update(JobFeeRuleRequest $request, $id)
    {
        $rule = JobFeeRule::findOrFail($id);
        $rule->update($request->validated());
        return $rule;
    }
}
