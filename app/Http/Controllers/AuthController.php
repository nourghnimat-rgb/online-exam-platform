<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function showRegister(): View
    {
        return view('auth.register');
    }
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        return redirect()->route('dashboard');
    }
    public function login(LoginRequest $request)
    {
        $user = $this->authService->login($request->validated());
        return redirect()->route('dashboard');
    }
}
