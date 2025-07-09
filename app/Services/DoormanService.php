<?php

namespace App\Services;

use App\Models\Doorman;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class DoormanService
{
    public function getDoormen(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Doorman::with('user');

        if (isset($filters['badge_number']) && $filters['badge_number'] !== '') {
            $query->where('badge_number', 'like', "%{$filters['badge_number']}%");
        }

        if (isset($filters['shift'])) {
            $query->where('shift', $filters['shift']);
        }

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        return $query->orderBy('badge_number')->paginate($perPage);
    }

    public function createDoorman(array $data): Doorman
    {
        return Doorman::create($data);
    }

    public function findById(int $id): Doorman
    {
        return Doorman::with('user')->findOrFail($id);
    }

    public function findByUser(User $user): ?Doorman
    {
        return Doorman::where('user_id', $user->id)->first();
    }

    public function updateDoorman(Doorman $doorman, array $data): Doorman
    {
        $doorman->update($data);
        return $doorman->fresh(['user']);
    }

    public function deleteDoorman(Doorman $doorman): bool
    {
        return $doorman->delete();
    }
}