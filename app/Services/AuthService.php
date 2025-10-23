<?php

namespace App\Services;

use App\Helpers\Password;
use App\Helpers\VerificationEncryption;
use App\Jobs\SendVerificationEmail;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthService
{
    protected $userRepo;
    protected $roleService;
    protected $mailService;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->roleService = new RoleService();
        $this->mailService = new MailService();
    }

    public function register(array $data)
    {
        $user = DB::transaction(function () use ($data) {
            // create user
            $data['password'] = Password::hash($data['password']);
            $user = $this->userRepo->create($data);

            // assign role (customer)
            $customerRole = $this->roleService->getBySlug('customer');
            $user->roles()->attach($customerRole->id);

            return $user;
        });

        $urlVerification = VerificationEncryption::encrypt($user);

        SendVerificationEmail::dispatch($user, $urlVerification);

        return [
            'user' => $user,
            'email_verification_sent' => true,
        ];
    }

    public function login(string $email, string $password)
    {
        $user = $this->userRepo->getByEmail($email);
        if (!$user) {
            throw new \Exception("Invalid Credentials", 401);
        }
        
        if (!Password::check($password, $user->password)) {
            throw new \Exception("Invalid Credentials", 401);
        }
    
        if (!$this->userRepo->isVerified($user)) {
            throw new \Exception("Email not verified", 403);
        }

        $token = $user->createToken('auth')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(Request $request)
    {
        // Revoke the current access token
        $request->user()->currentAccessToken()->delete();
        return ['message' => 'Logged out successfully'];
    }
}