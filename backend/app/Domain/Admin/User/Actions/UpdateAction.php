<?php

namespace App\Domain\Admin\User\Actions;

use App\Repository\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UpdateAction
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($id, array $data)
    {
        // Jika password diisi (ingin diganti), lakukan hashing
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Jika kosong, hapus key password agar tidak menimpa password lama dengan string kosong
            unset($data['password']);
        }

        return $this->userRepository->update($id, $data);
    }
}