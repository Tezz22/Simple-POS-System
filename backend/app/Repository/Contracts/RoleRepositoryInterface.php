<?php

namespace App\Repositories\Contracts;

interface RoleRepositoryInterface
{
    public function all();
    public function find($id);
    public function findByName($name);
}