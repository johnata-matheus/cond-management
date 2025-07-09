<?php

namespace App\Http\Controllers;

use App\Http\Requests\visit\StoreVisitRequest;
use App\Http\Requests\visit\UpdateVisitRequest;
use App\Services\VisitService;
use App\Http\Resources\VisitResource;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VisitController extends Controller
{
    public function __construct(
        private VisitService $visitService
    ) {}

    public function index(Request $request)
    {
        $visits = $this->visitService->getVisits(
            $request->only(['resident_id', 'status', 'visit_date']),
            $request->get('per_page', 15)
        );

        return VisitResource::collection($visits);
    }

    public function store(StoreVisitRequest $request): VisitResource
    {
        $visit = $this->visitService->createVisit($request->validated());
        
        return new VisitResource($visit->load('resident.user'));
    }

    public function show(Visit $visit): VisitResource
    {
        $visit = $this->visitService->findById($visit->id);
        
        return new VisitResource($visit);
    }

    public function update(UpdateVisitRequest $request, Visit $visit): VisitResource
    {
        $updatedVisit = $this->visitService->updateVisit($visit, $request->validated());
        
        return new VisitResource($updatedVisit);
    }

    public function destroy(Visit $visit): JsonResponse
    {
        $this->visitService->deleteVisit($visit);

        return response()->json([
            'message' => 'Visit deleted successfully'
        ]);
    }

    public function confirm(Visit $visit): VisitResource
    {
        $this->visitService->confirmVisit($visit);
        
        return new VisitResource($visit->fresh(['resident.user']));
    }

    public function cancel(Visit $visit): VisitResource
    {
        $this->visitService->cancelVisit($visit);
        
        return new VisitResource($visit->fresh(['resident.user']));
    }
}