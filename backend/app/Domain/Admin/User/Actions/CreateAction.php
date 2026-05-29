<?php

namespace App\Domain\Admin\User\Actions;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class CreateAction
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(array $data)
    {
        // Enkripsi password sebelum disimpan ke database
        $data['password'] = Hash::make($data['password']);
        
        // Pastikan user baru berstatus aktif secara default
        $data['is_active'] = $data['is_active'] ?? true;

        return $this->userRepository->create($data);
    }
}