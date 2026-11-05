<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;

class UserService
{

    public function paginate($search)
    {

        $query = User::orderBy('name');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }
        return $query->paginate(15);
    }

    public function create(array $data): User
    {

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->role = $data['role'];
        $user->save();
        
        return $user;
    }
    public function updateRole(User $user, string $role)
    {

        $user->role = $role;
        $user->save();
    }
    public function delete(User $user)
    {
        $user->delete();
    }
}
