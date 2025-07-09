<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\user\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function index(Request $request)
    {
        $users = $this->userService->getUsers(
            $request->only(['name', 'email', 'role', 'active']),
            $request->get('per_page', 15)
        );

        return UserResource::collection($users);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user->load('resident'));
    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $updatedUser = $this->userService->updateUser($user, $request->validated());
        
        return new UserResource($updatedUser->load('resident'));
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    public function deactivate(User $user): UserResource
    {
        $this->userService->deactivateUser($user);

        return new UserResource($user->fresh());
    }

    public function activate(User $user): UserResource
    {
        $this->userService->activateUser($user);

        return new UserResource($user->fresh());
    }

    public function profile(Request $request): UserResource
    {
        return new UserResource($request->user()->load('resident'));
    }

    public function updateProfile(UpdateUserRequest $request): UserResource
    {
        $updatedUser = $this->userService->updateUser($request->user(), $request->validated());
        
        return new UserResource($updatedUser->load('resident'));
    }
}