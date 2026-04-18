<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Career;
use App\Http\Requests\StoreCareerRequest;
use App\Http\Resources\CareerResource;
use App\Http\Requests\UpdateCareerRequest;

class ManagingCareersController extends Controller
{
      public function index()
    {
        return CareerResource::collection(Career::latest()->get());
    }

    public function store(StoreCareerRequest $request)
    {
        $career = Career::create($request->validated());

        return new CareerResource($career);
    }

    public function show(Career $career)
    {
        return new CareerResource($career);
    }

    public function update(UpdateCareerRequest $request, Career $career)
    {
        $career->update($request->validated());

        return new CareerResource($career);
    }

    public function destroy(Career $career)
    {
        $career->delete();

        return response()->json([
            'message' => 'Career deleted successfully'
        ]);
    }
}
