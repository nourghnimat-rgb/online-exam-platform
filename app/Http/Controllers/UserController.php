<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request, UserService $service)
    {
        $users = $service->paginate($request->search);
        return view('users.index', ['users' => $users]);
    }
    public function create()
    {
        return view('users.create');
    }
    public function store(StoreUserRequest $request, UserService $service)
    {
        $service->create($request->validated());
        return redirect()->route('users.index')->with('success', 'user ceated');
    }
    public function updateRole(UpdateUserRequest $request, UserService $service, User $user)
    {
        $service->updateRole($user, $request->role);
        return back()->with('success', 'role updated');
    }
    public function destroy(User $user, UserService $service)
    {
        $service->delete($user);
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}
