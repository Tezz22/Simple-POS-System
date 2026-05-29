<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Domain\Admin\User\Queries\UserQuery;
use App\Domain\Admin\User\Actions\CreateAction;
use App\Domain\Admin\User\Actions\UpdateAction;
use App\Domain\Admin\User\Actions\ActivateAction;
use App\Domain\Admin\User\Actions\DeactivateAction;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userQuery;

    public function __construct(UserQuery $userQuery)
    {
        $this->userQuery = $userQuery;
    }

    // 1. GET /api/admin/users (Ambil semua user)
    public function index(): JsonResponse
    {
        $users = $this->userQuery->getAll();
        return response()->json([
            'success' => true,
            'message' => 'Daftar pengguna berhasil diambil.',
            'data'    => UserResource::collection($users)
        ]);
    }

    // 2. POST /api/admin/users (Simpan user baru)
    public function store(StoreUserRequest $request, CreateAction $createAction): JsonResponse
    {
        $user = $createAction->execute($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Pengguna baru berhasil dibuat.',
            'data'    => new UserResource($user)
        ], 201);
    }

    // 3. GET /api/admin/users/{id} (Detail satu user)
    public function show($id): JsonResponse
    {
        $user = $this->userQuery->getById($id);
        return response()->json([
            'success' => true,
            'message' => 'Detail pengguna berhasil ditemukan.',
            'data'    => new UserResource($user)
        ]);
    }

    // 4. PUT /api/admin/users/{id} (Update data user)
    public function update(UpdateUserRequest $request, $id, UpdateAction $updateAction): JsonResponse
    {
        $user = $updateAction->execute($id, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diperbarui.',
            'data'    => new UserResource($user)
        ]);
    }

    // 5. PATCH /api/admin/users/{id}/toggle-status (Aktif/Nonaktifkan user)
    public function toggleStatus($id, ActivateAction $activate, DeactivateAction $deactivate): JsonResponse
    {
        $user = $this->userQuery->getById($id);

        if ($user->is_active) {
            $deactivate->execute($id);
            $message = 'Pengguna berhasil dinonaktifkan.';
        } else {
            $activate->execute($id);
            $message = 'Pengguna berhasil diaktifkan kembali.';
        }

        // Ambil data terbaru setelah status berubah
        $updatedUser = $this->userQuery->getById($id);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => new UserResource($updatedUser)
        ]);
    }
}
