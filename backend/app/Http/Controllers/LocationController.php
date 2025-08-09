<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Resources\LocationResource;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function __construct(private LocationService $locationService) {}

    public function index(Request $request): JsonResponse
    {
        $locations = $this->locationService->getAllWithFilters($request);
        return response()->json([
            'data' => LocationResource::collection($locations)
        ]);
    }

    public function store(StoreLocationRequest $request): JsonResponse
    {
        $location = $this->locationService->create($request->validated());
        return response()->json(new LocationResource($location), 201);
    }
}