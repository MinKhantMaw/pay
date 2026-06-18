<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'wallet.view',
            'wallet.adjust_balance',
            'wallet.disable',
            'transaction.view',
            'transaction.refund',
            'transaction.reverse',
            'cashin.view',
            'cashin.approve',
            'cashin.reject',
            'cashout.view',
            'cashout.approve',
            'cashout.reject',
            'notification.view',
            'notification.send',
            'report.view',
            'report.export',
            'role.view',
            'role.manage',
            'approval.view',
            'audit.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin_user']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin_user']);
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin_user']);
        $finance = Role::firstOrCreate(['name' => 'Finance', 'guard_name' => 'admin_user']);
        $support = Role::firstOrCreate(['name' => 'Support', 'guard_name' => 'admin_user']);

        $superAdmin->syncPermissions($permissions);
        $admin->syncPermissions([
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'wallet.view',
            'wallet.adjust_balance',
            'wallet.disable',
            'transaction.view',
            'transaction.refund',
            'transaction.reverse',
            'notification.view',
            'notification.send',
            'approval.view',
            'audit.view',
        ]);
        $finance->syncPermissions([
            'wallet.view',
            'transaction.view',
            'cashin.view',
            'cashin.approve',
            'cashin.reject',
            'cashout.view',
            'cashout.approve',
            'cashout.reject',
            'report.view',
            'report.export',
            'approval.view',
            'audit.view',
        ]);
        $support->syncPermissions([
            'user.view',
            'wallet.view',
            'transaction.view',
            'notification.view',
            'approval.view',
        ]);

        Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);

        $firstAdmin = AdminUser::orderBy('id')->first();
        if ($firstAdmin && ! $firstAdmin->hasRole('Super Admin')) {
            $firstAdmin->assignRole('Super Admin');
        }

        User::query()->whereDoesntHave('roles')->each(function (User $user) {
            $user->assignRole('User');
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
