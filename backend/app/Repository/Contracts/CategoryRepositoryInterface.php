<?php

namespace App\Repository\Contracts;

interface CategoryRepositoryInterface
{
    public function all();
    public function find($id);
}