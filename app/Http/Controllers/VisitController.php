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
        $user = $request->user();
        $resident = $user->resident()->first();

        if (!$resident) {
            return response()->json(['error' => 'Usuário não possui morador vinculado.'], 403);
        }

        $visits = $this->visitService->getVisits([
            'resident_id' => $resident->id,
            'visit_date' => $request->get('visit_date')
        ]);

        return VisitResource::collection($visits);
    }

    public function store(Request $request)
    {
        try {
            $user = $request->user();
            $resident = $user->resident()->first();

            if (!$resident) {
                return response()->json(['error' => 'Usuário não possui morador vinculado.'], 403);
            }

            $data = $request->all();
            $data['resident_id'] = $resident->id;

            $visit = $this->visitService->createVisit($data);

            return new VisitResource($visit);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Erro ao criar visita',
                'message' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTrace() : null,
            ], 500);
        }
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
        
        return new VisitResource($visit);
    }

    public function cancel(Visit $visit): VisitResource
    {
        $this->visitService->cancelVisit($visit);
        
        return new VisitResource($visit);
    }

    public function all(Request $request)
    {
        $visits = $this->visitService->getVisits();
        return VisitResource::collection($visits);
    }
}