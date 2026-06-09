<?php

namespace App\Domain\Admin\User\Queries;

use App\Repository\Contracts\UserRepositoryInterface;

class UserQuery
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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