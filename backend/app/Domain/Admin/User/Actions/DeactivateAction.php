<?php

namespace App\Domain\Admin\User\Actions;

use App\Repository\Contracts\UserRepositoryInterface;

class DeactivateAction
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function execute($id)
    {
        return $this->userRepository->deactivate($id);
    }
}