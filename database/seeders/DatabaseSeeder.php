<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $treasurerRole = DB::table('user_role')->where('role_name', 'treasurer')->first();
        $staffRole = DB::table('user_role')->where('role_name', 'staff')->first();

        User::create([
          'first_name' => 'Church',
          'last_name' => 'Admin',
          'email' => 'admin@example.com',
          'email_verified_at' => now(),
          'password' => bcrypt('12345678'),
          'role' => $adminRole->id,
          'is_active' => true,
        ]);

         User::create([
          'first_name' => 'Church',
          'last_name' => 'Treasurer',
          'email' => 'treasurer@example.com',
          'email_verified_at' => now(),
          'password' => bcrypt('12345678'),
          'role' => $treasurerRole->id,
          'is_active' => true,
        ]);  

        User::create([
          'first_name' => 'Church',
          'last_name' => 'Staff',
          'email' => 'staff@example.com',
          'email_verified_at' => now(),
          'password' => bcrypt('12345678'),
          'role' => $staffRole->id,
          'is_active' => true,
        ]);
    }
}