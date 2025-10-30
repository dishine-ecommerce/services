<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());
        return response()->json($result, 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        try {
            $result = $this->authService->login(
                $validated['email'], 
                $validated['password']
            );
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ],  $e->getCode() ?: 500);
        }
    }

    public function user(Request $request) {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $result = $this->authService->logout($request);
        return response()->json($result);
    }
}