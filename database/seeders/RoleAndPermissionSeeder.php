<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Complete system permissions grouped by module/table
        $permissions = [
            // 1. Dashboard & Analytics
            ['name' => 'view-dashboard', 'display_name' => 'عرض لوحة التحكم الرئيسية', 'table' => 'dashboard'],
            ['name' => 'view-analytics', 'display_name' => 'عرض تحليلات الواتساب والزيارات', 'table' => 'analytics'],
            ['name' => 'manage-reports', 'display_name' => 'عرض وإدارة تقارير المبيعات الشاملة', 'table' => 'reports'],

            // 2. Catalog & Products
            ['name' => 'manage-products', 'display_name' => 'إدارة المنتجات والأصناف والأسعار', 'table' => 'products'],
            ['name' => 'manage-categories', 'display_name' => 'إدارة أقسام وتصنيفات السوبرماركت', 'table' => 'categories'],
            ['name' => 'manage-stocks', 'display_name' => 'إدارة المخزون والكميات والجرد', 'table' => 'stocks'],

            // 3. Orders & Carts
            ['name' => 'manage-orders', 'display_name' => 'إدارة الطلبات وتحديث حالات التوصيل', 'table' => 'orders'],
            ['name' => 'manage-abandoned-carts', 'display_name' => 'متابعة السلات المتروكة واسترجاعها', 'table' => 'abandoned_carts'],

            // 4. Marketing & Offers
            ['name' => 'manage-deals', 'display_name' => 'إدارة التخفيضات والعروض الفردية', 'table' => 'deals'],
            ['name' => 'manage-bundles', 'display_name' => 'إدارة باقات وعروض التوفير المجمعة', 'table' => 'bundles'],
            ['name' => 'manage-magazines', 'display_name' => 'إدارة مجلات العروض الأسبوعية والكتالوج', 'table' => 'magazines'],
            ['name' => 'manage-coupons', 'display_name' => 'إدارة كوبونات وقسائم الخصم الترويجية', 'table' => 'coupons'],
            ['name' => 'manage-banners', 'display_name' => 'إدارة البانرات وإعلانات الواجهة الرئيسية', 'table' => 'banners'],

            // 5. Customers & Support
            ['name' => 'manage-clients', 'display_name' => 'إدارة بيانات وحسابات عملاء المتجر', 'table' => 'clients'],
            ['name' => 'manage-contact-messages', 'display_name' => 'إدارة رسائل واستفسارات تواصل معنا', 'table' => 'contact_messages'],
            ['name' => 'manage-job-applications', 'display_name' => 'إدارة طلبات التوظيف والسير الذاتية', 'table' => 'job_applications'],
            ['name' => 'manage-notifications', 'display_name' => 'إدارة واستقبال الإشعارات والتنبيهات', 'table' => 'notifications'],

            // 6. Settings & Administration
            ['name' => 'manage-branches', 'display_name' => 'إدارة الفروع ومناطق وأرقام التوصيل', 'table' => 'branches'],
            ['name' => 'manage-settings', 'display_name' => 'إدارة إعدادات المتجر العامة والتواصل', 'table' => 'settings'],
            ['name' => 'manage-users', 'display_name' => 'إدارة حسابات المديرين والموظفين', 'table' => 'users'],
            ['name' => 'manage-roles', 'display_name' => 'إدارة الأدوار والصلاحيات', 'table' => 'roles'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                [
                    'display_name' => $perm['display_name'],
                    'table' => $perm['table'],
                ]
            );
        }

        $allPermissions = Permission::all();

        // 1. Super Admin Role (All Permissions)
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['display_name' => 'المدير العام']
        );
        $superAdminRole->syncPermissions($allPermissions);

        // 2. Admin Role (Full Operational Management)
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['display_name' => 'مدير النظام']
        );
        $adminRole->syncPermissions($allPermissions);

        // 3. Customer Role
        Role::firstOrCreate(
            ['name' => 'customer', 'guard_name' => 'web'],
            ['display_name' => 'عميل المتجر']
        );
    }
}
