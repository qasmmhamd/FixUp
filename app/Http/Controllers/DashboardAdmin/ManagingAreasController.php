<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AreaAddresses;
use App\Http\Resources\AreaAddressResource;
use App\Http\Requests\StoreAreaAddressRequest;

class ManagingAreasController extends Controller
{
    public function index()
    {
        return AreaAddressResource::collection(AreaAddresses::latest()->paginate(10));
    }
    public function store(StoreAreaAddressRequest $request)
    {
        $area = AreaAddresses::create($request->validated());

        return new AreaAddressResource($area);
    }
    public function destroy(AreaAddresses $area)
    {
        $area->delete();

        return response()->json([
            'message' => 'Area deleted successfully'
        ]);
    }

}
