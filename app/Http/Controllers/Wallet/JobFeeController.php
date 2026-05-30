<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobFeeRuleRequest;
use App\Models\JobFeeRule;
use Illuminate\Http\JsonResponse;

/**
 * Class JobFeeController
 *
 * Handles CRUD operations for job fee rules used in wallet deduction logic.
 * These rules define the platform fee per career.
 */
class JobFeeController extends Controller
{
    /**
     * Create a new job fee rule.
     *
     * @param JobFeeRuleRequest $request Validated request data
     * @return JsonResponse
     */
    public function store(JobFeeRuleRequest $request): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Create Job Fee Rule
        |--------------------------------------------------------------------------
        */

        $rule = JobFeeRule::create(
            $request->validated()
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'Job fee rule created successfully',
            'data'    => $rule
        ], 201);
    }

    /**
     * Update an existing job fee rule.
     *
     * @param JobFeeRuleRequest $request Validated request data
     * @param int $id Job fee rule ID
     * @return JsonResponse
     */
    public function update(
        JobFeeRuleRequest $request,
        int $id
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | Find and Update Rule
        |--------------------------------------------------------------------------
        */

        $rule = JobFeeRule::findOrFail($id);

        $rule->update(
            $request->validated()
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'Job fee rule updated successfully',
            'data'    => $rule
        ]);
    }
}