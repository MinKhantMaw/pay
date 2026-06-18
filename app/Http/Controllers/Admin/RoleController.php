<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->where('guard_name', 'admin_user')->orderBy('name')->get();
        $permissions = Permission::where('guard_name', 'admin_user')->orderBy('name')->get()->groupBy(fn ($permission) => explode('.', $permission->name)[0]);

        return view('backend.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where('guard_name', 'admin_user')],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')->where('guard_name', 'admin_user')],
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'admin_user']);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('create', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        abort_unless($role->guard_name === 'admin_user', 404);

        $permissions = Permission::where('guard_name', 'admin_user')->orderBy('name')->get()->groupBy(fn ($permission) => explode('.', $permission->name)[0]);
        $role->load('permissions');

        return view('backend.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        abort_unless($role->guard_name === 'admin_user', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where('guard_name', 'admin_user')->ignore($role->id)],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')->where('guard_name', 'admin_user')],
        ]);

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('update', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        abort_unless($role->guard_name === 'admin_user', 404);
        abort_if($role->name === 'Super Admin', 403);

        $role->delete();

        return redirect()->route('admin.roles.index')->with('update', 'Role deleted successfully.');
    }
}
