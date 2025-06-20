<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUserWithRole extends Command
{
    protected $signature = 'user:create {email} {name} {password} {role}';
    protected $description = 'Create a new user with a specific role';

    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->argument('name');
        $password = $this->argument('password');
        $roleSlug = $this->argument('role');

        $role = Role::where('slug', $roleSlug)->first();

        if (!$role) {
            $this->error("Role '{$roleSlug}' not found!");
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole($role);

        $this->info("User created successfully with role '{$role->name}'!");
        return 0;
    }
} 