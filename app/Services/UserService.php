<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getUsers(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = User::query();

        if (isset($filters['name']) && $filters['name'] !== '') {
            $query->where('name', 'like', "%{$filters['name']}%");
        }

        if (isset($filters['email']) && $filters['email'] !== '') {
            $query->where('email', 'like', "%{$filters['email']}%");
        }

        if (isset($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function createUser(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return User::create($data);
    }

    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        
        return $user->fresh();
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    public function deactivateUser(User $user): bool
    {
        return $user->update(['active' => false]);
    }

    public function activateUser(User $user): bool
    {
        return $user->update(['active' => true]);
    }
}