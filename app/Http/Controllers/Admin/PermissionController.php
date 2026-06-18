<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('guard_name', 'admin_user')->orderBy('name')->get()->groupBy(fn ($permission) => explode('.', $permission->name)[0]);

        return view('backend.permissions.index', compact('permissions'));
    }
}
