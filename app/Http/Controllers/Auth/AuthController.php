<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\Auth\LoginRequest;

use App\Http\Requests\Auth\RegisterRequest;

use App\Services\AuthService;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /*
    | Register
    */

    public function register(RegisterRequest $request)
    {
        return response()->json(
            $this->authService->register($request)
        );
    }

    /*
    | Login
    */

    public function login(LoginRequest $request)
    {
        return response()->json(
            $this->authService->login($request)
        );
    }

    /*
    | Current User
    */

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->load([
                'roles',
                'applicant',
            ])
        ]);
    }

    /*
    | Logout
    */

    public function logout(Request $request)
    {
        return response()->json(
            $this->authService->logout($request)
        );
    }
}
