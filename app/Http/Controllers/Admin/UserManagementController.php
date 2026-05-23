<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserManagement\StoreManagementUserRequest;
use App\Http\Requests\Admin\UserManagement\ToggleManagementUserStatusRequest;
use App\Http\Requests\Admin\UserManagement\UpdateManagementUserRequest;
use App\Services\UserManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * User management service.
     */
    protected UserManagementService $userManagementService;

    /**
     * Constructor.
     */
    public function __construct(
        UserManagementService $userManagementService
    ) {
        $this->userManagementService =
            $userManagementService;
    }

    /**
     * Get all management users.
     */
    public function index(
        Request $request
    ): JsonResponse {

        $users = $this
            ->userManagementService
            ->list(
                $request->only([
                    'search',
                    'role',
                    'is_active',
                    'per_page',
                ])
            );

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully.',
            'data' => $users,
        ]);
    }

    /**
     * Get user detail.
     */
    public function show(
        int $id
    ): JsonResponse {

        $user = $this
            ->userManagementService
            ->getById($id);

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully.',
            'data' => $user,
        ]);
    }

    /**
     * Create management user.
     */
    public function store(
        StoreManagementUserRequest $request
    ): JsonResponse {

        $user = $this
            ->userManagementService
            ->create(
                $request->validated()
            );

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $user,
        ], 201);
    }

    /**
     * Update management user.
     */
    public function update(
        UpdateManagementUserRequest $request,
        int $id
    ): JsonResponse {

        $user = $this
            ->userManagementService
            ->update(
                $id,
                $request->validated()
            );

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $user,
        ]);
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(
        ToggleManagementUserStatusRequest $request,
        int $id
    ): JsonResponse {

        $user = $this
            ->userManagementService
            ->toggleStatus(
                $id,
                $request->validated()['is_active']
            );

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
            'data' => $user,
        ]);
    }
}