<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionsByGroup = [
            'User Management' => [
                'view_users',
                'create_users',
                'edit_users',
                'delete_users',
            ],

            'Role / Permissions Management' => [
                'view_roles',
                'create_roles',
                'edit_roles',
                'delete_roles',
                'view_permissions',
                'create_permissions',
                'edit_permissions',
                'delete_permissions',
            ],

            'Product Management' => [
                'view_products',
                'create_products',
                'edit_products',
                'delete_products',
                'bulk_products',
                'category_create',
                'category_edit',
                'category_delete',
                'category_view',
                'barcode_print',
            ],

            'Purchase Management' => [
                'purchase_create',
                'purchase_edit',
                'purchase_delete',
                'purchase_view',
                'supplier_create',
                'supplier_edit',
                'supplier_delete',
                'supplier_view',
            ],

            'Invoices Management' => [
                'invoice_create',
                'invoice_edit',
                'invoice_delete',
                'invoice_view',
                'invoice_print',

                'invoice_return',
                'invoice_return_print',
                'invoice_return_delete',
                'invoice_return_edit',
                'invoice_return_view',

                'pos',

                'invoice_hold',
                'invoice_hold_print',
                'invoice_hold_delete',
                'invoice_hold_edit',
                'invoice_hold_view',

                'invoice_due',
                'invoice_due_print',
                'invoice_due_delete',
                'invoice_due_edit',
                'invoice_due_view',
            ],

            'Reports' => [
                'stock_report',
                'sales_report',
                'purchase_report',
                'profit_loss_report',
            ],

            'Discounts' => [
                'apply_discount',
                'override_price',
            ],

            'Dashboard' => [
                'monthly_sales',
                'sales_overview',
            ],

            'Settings' => [
                'manage_settings',
                'view_logs',
            ],

            'Audit & Security' => [
                'view_audit_logs',
                'export_reports',
            ],
        ];

        foreach ($permissionsByGroup as $group => $permissions) {
            foreach ($permissions as $permission) {
               Permission::updateOrCreate(
                    ['name' => $permission, 'guard_name' => 'web'], 
                    ['group' => $group]
                );
            }
        }
    }
}
