<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users =[
            [
                'name'=>'Admin',
                'email'=>'admin@example.com',
                'role'=>'admin',
                'password'=>'12345678',
            ],
            [
                'name'=>'Instructor',
                'email'=>'instructor@example.com',
                'role'=>'instructor',
                'password'=>'12345678',
            ],
            [
                'name'=>'Student',
                'email'=>'student@example.com',
                'role'=>'student',
                'password'=>'12345678',
            ],
        ];
        foreach ($users as $user){
            User::forceCreate($user);
            
        }
        
    }
}
