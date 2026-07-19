<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::where('guard_name', 'employee')
            ->whereNotIn('name', [
                'manage-dashboard',
                'manage-cars',
                'manage-brands',
                'manage-brand-types',
                'manage-car-categories',
                'manage-car-types',
                'manage-specifications',
                'manage-features',
                'manage-safety-features',
                'manage-offers',
                'manage-leads',
                'manage-calculator-leads',
                'manage-contact-sources',
                'manage-newsletter',
                'manage-bookings',
                'manage-tracking',
                'manage-calculator-settings',
                'manage-tasks',
                'manage-employees',
                'manage-roles',
                'manage-reports',
                'manage-blog',
                'manage-designs',
                'manage-partners',
                'manage-testimonials',
                'manage-faqs',
                'manage-translations',
                'manage-settings',
                'manage-settings-integrations',
                'manage-home-sections',
                'manage-hero-slides',
                'manage-promo-cards',
                'manage-finance-steps',
                'manage-budget-ranges',
            ])->delete();

        $permissions = [
            'manage-dashboard',
            'manage-cars',
            'manage-brands',
            'manage-brand-types',
            'manage-car-categories',
            'manage-car-types',
            'manage-specifications',
            'manage-safety-features',
            'manage-features',
            'manage-offers',
            'manage-leads',
            'manage-calculator-leads',
            'manage-contact-sources',
            'manage-newsletter',
            'manage-bookings',
            'manage-tracking',
            'manage-calculator-settings',
            'manage-tasks',
            'manage-employees',
            'manage-roles',
            'manage-reports',
            'manage-blog',
            'manage-designs',
            'manage-partners',
            'manage-testimonials',
            'manage-faqs',
            'manage-translations',
            'manage-settings',
            'manage-settings-integrations',
            'manage-home-sections',
            'manage-hero-slides',
            'manage-promo-cards',
            'manage-finance-steps',
            'manage-budget-ranges',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'employee']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'employee']);
        $adminRole->syncPermissions($permissions);

        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'employee']);
        $employeeRole->syncPermissions([
            'manage-dashboard',
            'manage-cars',
            'manage-leads',
            'manage-bookings',
            'manage-tracking',
            'manage-tasks',
        ]);

        foreach (Employee::all() as $emp) {
            if ($emp->role === 'admin') {
                $emp->assignRole('admin');
            } else {
                $emp->assignRole('employee');
            }
        }
    }
}
