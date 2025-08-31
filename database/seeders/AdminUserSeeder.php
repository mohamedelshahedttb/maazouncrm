<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create permissions for all major operations
        $permissions = [
            // User management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.manage_roles',
            
            // Client management
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            'clients.manage_status',
            
            // Service management
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',
            'services.manage_categories',
            
            // Appointment management
            'appointments.view',
            'appointments.create',
            'appointments.edit',
            'appointments.delete',
            'appointments.manage_schedule',
            
            // Task management
            'tasks.view',
            'tasks.create',
            'tasks.edit',
            'tasks.delete',
            'tasks.assign',
            'tasks.manage_workflow',
            
            // Product management
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'products.manage_inventory',
            
            // Supplier management
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.delete',
            'suppliers.manage_orders',
            
            // Partner management
            'partners.view',
            'partners.create',
            'partners.edit',
            'partners.delete',
            'partners.manage_commissions',
            
            // Report management
            'reports.view',
            'reports.generate',
            'reports.export',
            'reports.manage_analytics',
            
            // System settings
            'settings.view',
            'settings.edit',
            'settings.manage_integrations',
            'settings.manage_backups',
            
            // Financial management
            'invoices.view',
            'invoices.create',
            'invoices.edit',
            'invoices.delete',
            'invoices.manage_payments',
            
            // Workflow management
            'workflows.view',
            'workflows.create',
            'workflows.edit',
            'workflows.delete',
            'workflows.manage_execution',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to admin role
        $adminRole->givePermissionTo($permissions);

        // Create admin user
        $adminUser = User::create([
            'name' => 'Mohamed Elshahed',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'phone' => '+201234567890',
            'specialization' => 'System Administrator',
            'notes' => 'Full system administrator with complete access to all features',
        ]);

        // Assign admin role to the user
        $adminUser->assignRole($adminRole);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: admin123');
        $this->command->info('Role: Admin with full permissions');
    }
}
