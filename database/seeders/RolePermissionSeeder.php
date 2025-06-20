<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Can manage members, assign roles, and disable accounts'
            ],
            [
                'name' => 'Staff for Events',
                'slug' => 'staff-events',
                'description' => 'Can manage calendar, announcements, and view member list'
            ],
            [
                'name' => 'Treasurer',
                'slug' => 'treasurer',
                'description' => 'Can record and verify donations'
            ],
            [
                'name' => 'Member',
                'slug' => 'member',
                'description' => 'Can view donation status, manage profile, and donate'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create permissions
        $permissions = [
            // Admin permissions
            ['name' => 'Manage Members', 'slug' => 'manage-members'],
            ['name' => 'Assign Roles', 'slug' => 'assign-roles'],
            ['name' => 'Disable Accounts', 'slug' => 'disable-accounts'],
            
            // Staff for Events permissions
            ['name' => 'Manage Calendar', 'slug' => 'manage-calendar'],
            ['name' => 'Manage Announcements', 'slug' => 'manage-announcements'],
            ['name' => 'View Member List', 'slug' => 'view-member-list'],
            
            // Treasurer permissions
            ['name' => 'Record Donations', 'slug' => 'record-donations'],
            ['name' => 'Verify Donations', 'slug' => 'verify-donations'],
            
            // Member permissions
            ['name' => 'View Donation Status', 'slug' => 'view-donation-status'],
            ['name' => 'Manage Profile', 'slug' => 'manage-profile'],
            ['name' => 'Make Donations', 'slug' => 'make-donations'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign permissions to roles
        $admin = Role::where('slug', 'admin')->first();
        $staffEvents = Role::where('slug', 'staff-events')->first();
        $treasurer = Role::where('slug', 'treasurer')->first();
        $member = Role::where('slug', 'member')->first();

        // Admin permissions
        $admin->permissions()->attach(Permission::whereIn('slug', [
            'manage-members',
            'assign-roles',
            'disable-accounts',
            'manage-calendar',
            'manage-announcements',
            'view-member-list',
            'record-donations',
            'verify-donations',
            'view-donation-status',
            'manage-profile',
            'make-donations'
        ])->pluck('id'));

        // Staff for Events permissions
        $staffEvents->permissions()->attach(Permission::whereIn('slug', [
            'manage-calendar',
            'manage-announcements',
            'view-member-list'
        ])->pluck('id'));

        // Treasurer permissions
        $treasurer->permissions()->attach(Permission::whereIn('slug', [
            'record-donations',
            'verify-donations',
            'view-donation-status'
        ])->pluck('id'));

        // Member permissions
        $member->permissions()->attach(Permission::whereIn('slug', [
            'view-donation-status',
            'manage-profile',
            'make-donations'
        ])->pluck('id'));
    }
} 