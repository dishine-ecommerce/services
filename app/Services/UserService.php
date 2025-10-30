<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    protected $userRepo;
    
    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function getById(int $id)
    {
        return $this->userRepo->getById($id);
    }

    public function verifyEmail(array $data)
    {
        $user = $this->userRepo->getById($data['id']);
        if (!$user) {
            throw new \Exception('User not found', 404);
        }
        if ($user->email_verified_at) {
            throw new \Exception('Email already verified',  200);
        }
        $this->userRepo->verify($user);
        return $user;
    }
}