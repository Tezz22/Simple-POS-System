<?php

namespace App\Domain\Admin\User\Actions;

use App\Repository\Contracts\UserRepositoryInterface;

class DeactivateAction
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($id)
    {
        return $this->userRepository->deactivate($id);
    }
}