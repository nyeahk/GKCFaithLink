<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminRole = DB::table('user_role')->where('role_name', 'admin')->first();

        User::create([
          'username' => 'elsa_admin',
          'email' => 'admin@example.com',
          'email_verified_at' => now(),
          'password' => bcrypt('12345678'),
          'role' => $adminRole->id,
          'is_active' => true,
        ]);
    }
}