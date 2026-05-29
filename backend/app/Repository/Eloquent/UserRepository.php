<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        // Mengambil semua user beserta data rolenya
        return User::with('role')->get();
    }

    public function find($id)
    {
        return User::with('role')->findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => false]);
        
        // Aturan bisnis: Token Sanctum dicabut saat user dinonaktifkan
        $user->tokens()->delete(); 
        return $user;
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true]);
        return $user;
    }

    public function byRole($role)
    {
        return User::whereHas('role', function ($query) use ($role) {
            $query->where('name', $role);
        })->get();
    }
}