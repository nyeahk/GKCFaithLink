<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_login_to_staff_portal()
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'is_active' => true,
        ]);

        $response = $this->post('/staff/login', [
            'email' => $staff->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/staff/dashboard');
        
        // Check that we're logged in as staff
        $this->assertEquals('staff', auth()->user()->role);
    }

    public function test_admin_cannot_login_to_staff_portal()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->post('/staff/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}