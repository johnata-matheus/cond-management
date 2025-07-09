<?php

namespace App\Http\Controllers;

use App\Http\Requests\resident\StoreResidentRequest;
use App\Http\Requests\resident\UpdateResidentRequest;
use App\Services\ResidentService;
use App\Http\Resources\ResidentResource;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResidentController extends Controller
{
    public function __construct(
        private ResidentService $residentService
    ) {}

    public function index(Request $request)
    {
        $residents = $this->residentService->getResidents(
            $request->only(['apartment', 'block', 'active']),
            $request->get('per_page', 15)
        );

        return ResidentResource::collection($residents);
    }

    public function store(StoreResidentRequest $request): ResidentResource
    {
        $resident = $this->residentService->createResident($request->validated());
        
        return new ResidentResource($resident->load('user'));
    }

    public function show(Resident $resident): ResidentResource
    {
        $resident = $this->residentService->findById($resident->id);
        
        return new ResidentResource($resident);
    }

    public function update(UpdateResidentRequest $request, Resident $resident): ResidentResource
    {
        $updatedResident = $this->residentService->updateResident($resident, $request->validated());
        
        return new ResidentResource($updatedResident);
    }

    public function destroy(Resident $resident): JsonResponse
    {
        $this->residentService->deleteResident($resident);

        return response()->json([
            'message' => 'Resident deleted successfully'
        ]);
    }
}