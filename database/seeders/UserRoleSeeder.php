<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        \DB::table('user_role')->insert([
            ['role_name' => 'admin'],
            ['role_name' => 'treasurer'],
            ['role_name' => 'member'],
            ['role_name' => 'staff'],
        ]);
    }
}
