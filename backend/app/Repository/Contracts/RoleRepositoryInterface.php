<?php

namespace App\Repository\Contracts;

interface RoleRepositoryInterface
{
    public function all();
    public function find($id);
    public function findByName($name);
}