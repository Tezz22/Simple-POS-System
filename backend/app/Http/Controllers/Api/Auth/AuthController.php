<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 1. POST /api/login
    public function login(LoginRequest $request): JsonResponse
    {
        // Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // Validasi kecocokan user dan password
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Kredensial yang Anda masukkan salah.'],
            ]);
        }

        // Aturan Bisnis: Cek apakah user dalam status aktif
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda dinonaktifkan. Silakan hubungi admin.'
            ], 403);
        }

        // Update tanggal login terakhir
        $user->update([
            'last_login_at' => now()
        ]);

        // Generate token Sanctum dengan kemampuan (abilities) sesuai nama rolenya
        $token = $user->createToken('auth_token', [$user->role->name])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ]);
    }

    // 2. GET /api/me (Proteksi via Sanctum)
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource($request->user())
        ]);
    }

    // 3. POST /api/logout (Proteksi via Sanctum)
    public function logout(Request $request): JsonResponse
    {
        // Hapus token yang saat ini digunakan untuk login
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil. Token telah dihapus.'
        ]);
    }
}
