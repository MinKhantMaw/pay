<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $auditLogs = AuditLog::query()
            ->when($request->module, fn ($query) => $query->where('module', $request->module))
            ->when($request->action, fn ($query) => $query->where('action', 'like', '%'.$request->action.'%'))
            ->latest('created_at')
            ->paginate(20);

        $modules = AuditLog::query()->select('module')->distinct()->orderBy('module')->pluck('module');

        return view('backend.audit_logs.index', compact('auditLogs', 'modules'));
    }
}
