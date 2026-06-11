<?php

namespace App\Domain\Admin\User\Queries;

use App\Repository\Contracts\UserRepositoryInterface;

class UserQuery
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function getAll()
    {
        return $this->userRepository->all();
    }

    public function getById($id)
    {
        return $this->userRepository->find($id);
    }

    public function getByRole($role)
    {
        return $this->userRepository->byRole($role);
    }
}