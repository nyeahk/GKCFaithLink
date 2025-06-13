<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UpdateDonationsSeeder extends Seeder
{
    public function run()
    {
        // Get the first admin user or create one if none exists
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $admin = User::create([
                'name' => 'System Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
        }

        // Update all existing donations with the admin user ID and transaction date
        Donation::whereNull('user_id')->each(function ($donation) use ($admin) {
            $donation->update([
                'user_id' => $admin->id,
                'transaction_date' => $donation->created_at ?? Carbon::now()
            ]);
        });
    }
} 