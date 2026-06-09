<?php

namespace App\Repository\Eloquent;

use App\Models\Role;
use App\Repository\Contracts\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function all()
    {
        return Role::all();
    }

    public function find($id)
    {
        return Role::findOrFail($id);
    }

    public function findByName($name)
    {
        return Role::where('name', $name)->first();
    }
}