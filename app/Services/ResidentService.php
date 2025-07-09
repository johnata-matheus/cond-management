<?php

namespace App\Services;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ResidentService
{
    public function getResidents(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Resident::with('user');

        if (isset($filters['apartment'])) {
            $query->where('apartment', 'like', "%{$filters['apartment']}%");
        }

        if (isset($filters['block'])) {
            $query->where('block', $filters['block']);
        }

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        return $query->orderBy('block')
                    ->orderBy('apartment')
                    ->paginate($perPage);
    }

    public function createResident(array $data): Resident
    {
        return Resident::create($data);
    }

    public function findById(int $id): Resident
    {
        return Resident::with(['user', 'visits'])->findOrFail($id);
    }

    public function findByUser(User $user): ?Resident
    {
        return Resident::where('user_id', $user->id)->first();
    }

    public function updateResident(Resident $resident, array $data): Resident
    {
        $resident->update($data);
        return $resident->fresh(['user']);
    }

    public function deleteResident(Resident $resident): bool
    {
        return $resident->delete();
    }
}