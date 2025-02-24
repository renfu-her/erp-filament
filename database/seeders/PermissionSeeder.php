<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 創建基本權限
        $permissions = [
            // 用戶管理權限
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // 角色管理權限
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',

            // 會員管理權限
            'view_members',
            'create_members',
            'edit_members',
            'delete_members',

            // 產品管理權限
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',

            // 訂單管理權限
            'view_orders',
            'create_orders',
            'edit_orders',
            'delete_orders',

            // 促銷管理權限
            'view_promotions',
            'create_promotions',
            'edit_promotions',
            'delete_promotions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 創建超級管理員角色
        $superAdminRole = Role::create(['name' => 'super-admin']);

        // 創建一般管理員角色
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // 找到 admin 用戶並賦予超級管理員角色
        $admin = User::where('email', 'admin@admin.com')->first();
        if ($admin) {
            $admin->assignRole('super-admin');
        }
    }
}
