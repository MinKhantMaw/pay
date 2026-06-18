<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\UserService;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user.view')->only(['index', 'ssd', 'show']);
        $this->middleware('permission:user.create')->only(['create', 'store']);
        $this->middleware('permission:user.update')->only(['edit', 'update']);
        $this->middleware('permission:user.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('backend.users.index');
    }

    public function ssd()
    {
        $users = User::query();

        return DataTables::of($users)
            ->addColumn('profile', function ($e) {
                $profileUrl = $e->profile
                    ? asset('storage/'.$e->profile)
                    : 'https://ui-avatars.com/api/?name='.urlencode($e->name).'&background=0D8ABC&color=fff';

                return '<img src="'.$profileUrl.'" alt="'.e($e->name).'" class="rounded-circle" width="42" height="42" style="object-fit: cover;">';
            })
            ->addColumn('status', function ($e) {
                $status = $e->status?->value ?? 'InActive';
                $badgeClass = $e->status?->badgeClass() ?? 'badge-danger';

                return '<span class="badge '.$badgeClass.'">'.$status.'</span>';
            })
            ->editColumn('user_agent', function ($e) {
                if ($e->user_agent) {
                    $agent = new Agent;
                    $agent->setUserAgent($e->user_agent);
                    $device = $agent->device();
                    $platform = $agent->platform();
                    $browser = $agent->browser();

                    return '<table class="table">
             <tbody>
                <tr><td>Device</td><td>'.$device.'</td></tr> .
                <tr><td>Platform</td><td>'.$platform.'</td></tr> .
                <tr><td>Browser</td><td>'.$browser.'</td></tr>
             </tbody>
            </table>';
                }

                return '-';
            })
            ->addColumn('action', function ($each) {
                $admin = auth('admin_user')->user();
                $show_icon = $admin->can('user.view') ? '<a href="'.route('user.user.show', $each->id).'" class="text-info"><i class="fas fa-eye"></i></a>' : '';
                $edit_icon = $admin->can('user.update') ? '<a href="'.route('user.user.edit', $each->id).'" class="text-warning"><i class="fas fa-edit"></i></a>' : '';
                $delete_icon = $admin->can('user.delete') ? '<a href="#" class="text-danger delete" data-id="'.$each->id.'"><i class="fas fa-trash"></i></a>' : '';

                return '<div class="action-icon">'.$show_icon.$edit_icon.$delete_icon.'</div>';
            })
            ->rawColumns(['profile', 'status', 'user_agent', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(StoreUser $request, UserService $userService, AuditLogService $auditLogService)
    {
        try {
            $user = $userService->create($request->validated());
            $auditLogService->log('user_created', 'User', 'User created.', null, $user->only(['id', 'name', 'email', 'phone', 'status']));

            return redirect()->route('user.user.index')->with('create', 'Successfully Created');
        } catch (\Exception $err) {
            Log::error('User creation failed.', [
                'admin_user_id' => auth('admin_user')->id(),
                'exception' => $err,
            ]);

            return back()->withErrors(['fail' => 'The user account could not be created. Please check the details and try again.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::with('wallet')->findOrFail($id);

        return view('backend.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('backend.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateUser $request, $id, UserService $userService, AuditLogService $auditLogService)
    {
        try {
            $user = User::findOrFail($id);
            $oldValues = $user->only(['name', 'email', 'phone', 'status']);
            $userService->update($user, $request->validated());
            $freshUser = $user->fresh();
            $newValues = $freshUser->only(['name', 'email', 'phone', 'status']);
            $auditLogService->log('user_updated', 'User', 'User updated.', $oldValues, $newValues);

            if (($oldValues['status']?->value ?? $oldValues['status']) !== UserStatus::InActive->value && $freshUser->status === UserStatus::InActive) {
                $auditLogService->log('account_disabled', 'User', 'User account disabled.', $oldValues, $newValues);
            }

            return redirect()->route('user.user.index')->with('update', 'User was Successfully Update');
        } catch (\Exception $e) {
            Log::error('User update failed.', [
                'admin_user_id' => auth('admin_user')->id(),
                'user_id' => $id,
                'exception' => $e,
            ]);

            return back()->withErrors(['fail' => 'The user account could not be updated. Please check the details and try again.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, AuditLogService $auditLogService)
    {
        $user = User::findOrFail($id);
        $oldValues = $user->only(['id', 'name', 'email', 'phone', 'status']);
        $user->delete();
        $auditLogService->log('user_deleted', 'User', 'User deleted.', $oldValues, null);

        return 'success';
    }
}
