<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class EnableUser extends Command
{
    protected $signature = 'user:enable {email}';
    protected $description = 'Enable a disabled user account';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        $user->is_active = true;
        $user->save();

        $this->info("User {$email} has been enabled successfully.");
        return 0;
    }
}