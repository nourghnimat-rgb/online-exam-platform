<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{

    public function register(array $data): User
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],

        ]);
        request()->session()->regenerate();
        Auth::login($user);

        return $user;
    }
    public function login(array $data)
    {

        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ])) {
            throw ValidationException::withMessages([
                'email' => 'invalid login email or password. ',
            ]);
        }

        request()->session()->regenerate();
        return Auth::user();
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
