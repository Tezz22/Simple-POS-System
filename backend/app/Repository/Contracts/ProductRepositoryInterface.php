<?php

namespace App\Repository\Contracts;

interface ProductRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function deactivate($id);
    public function lowStock();
}