<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Exception;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $token = $this->authService->register($request->validated());

            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->authService->login($request->validated());

            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
