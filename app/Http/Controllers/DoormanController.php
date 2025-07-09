<?php

namespace App\Http\Controllers;

use App\Services\DoormanService;
use App\Http\Requests\doorman\StoreDoormanRequest;
use App\Http\Requests\doorman\UpdateDoormanRequest;
use App\Http\Resources\DoormanResource;
use App\Models\Doorman;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DoormanController extends Controller
{
    public function __construct(
        private DoormanService $doormanService
    ) {}

    public function index(Request $request)
    {
        $doormen = $this->doormanService->getDoormen(
            $request->only(['badge_number', 'shift', 'active']),
            $request->get('per_page', 15)
        );

        return DoormanResource::collection($doormen);
    }

    public function store(StoreDoormanRequest $request): JsonResponse
    {
        $doorman = $this->doormanService->createDoorman($request->validated());

        return response()->json([
            'doorman' => new DoormanResource($doorman->load('user'))
        ], 201);
    }

    public function show(Doorman $doorman): DoormanResource
    {
        $doorman = $this->doormanService->findById($doorman->id);
        
        return new DoormanResource($doorman);
    }

    public function update(UpdateDoormanRequest $request, Doorman $doorman): DoormanResource
    {
        $updatedDoorman = $this->doormanService->updateDoorman($doorman, $request->validated());
        
        return new DoormanResource($updatedDoorman);
    }

    public function destroy(Doorman $doorman): JsonResponse
    {
        $this->doormanService->deleteDoorman($doorman);

        return response()->json(null, 204);
    }
}