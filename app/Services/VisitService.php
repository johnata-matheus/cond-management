<?php

namespace App\Services;

use App\Models\Visit;
use App\Models\Resident;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class VisitService
{
    public function getVisits(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Visit::with(['resident.user']);

        if (isset($filters['resident_id'])) {
            $query->where('resident_id', $filters['resident_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['visit_date'])) {
            $query->whereDate('visit_date', $filters['visit_date']);
        }

        return $query->orderBy('visit_date', 'desc')
                    ->orderBy('start_time')
                    ->paginate($perPage);
    }

    public function createVisit(array $data): Visit
    {
        return Visit::create($data);
    }

    public function findById(int $id): Visit
    {
        return Visit::with(['resident.user'])->findOrFail($id);
    }

    public function updateVisit(Visit $visit, array $data): Visit
    {
        $visit->update($data);
        return $visit->fresh(['resident.user']);
    }

    public function cancelVisit(Visit $visit): bool
    {
        return $visit->update(['status' => 'cancelled']);
    }

    public function confirmVisit(Visit $visit): bool
    {
        return $visit->update(['status' => 'confirmed']);
    }

    public function deleteVisit(Visit $visit): bool
    {
        return $visit->delete();
    }

    public function getVisitsByResident(Resident $resident): Collection
    {
        return $resident->visits()->orderBy('visit_date', 'desc')->get();
    }
}