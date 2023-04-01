<?php

namespace App\Http\Controllers\User;

use App\Helpers\WalletGenerate;
use App\Models\User;
use App\Models\Wallet;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.users.index');
    }

    public function ssd()
    {
        $users = User::query();
        return DataTables::of($users)
            ->editColumn('user_agent', function ($e) {
                if ($e->user_agent) {
                    $agent = new Agent();
                    $agent->setUserAgent($e->user_agent);
                    $device = $agent->device();
                    $platform = $agent->platform();
                    $browser = $agent->browser();

                    return '<table class="table">
             <tbody>
                <tr><td>Device</td><td>' . $device . '</td></tr> .
                <tr><td>Platform</td><td>' . $platform . '</td></tr> .
                <tr><td>Browser</td><td>' . $browser . '</td></tr>
             </tbody>
            </table>';
                }

                return '-';
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '<a href="' . route('user.user.edit', $each->id) . '" class="text-warning"><i class="fas fa-edit"></i></a>';
                $delete_icon = '<a href="#" class="text-danger delete" data-id="' . $each->id . '"><i class="fas fa-trash"></i></a>';

                return '<div class="action-icon">' . $edit_icon . $delete_icon . '</div>';
            })
            ->rawColumns(['user_agent', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {

        DB::beginTransaction();
        try {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);

            $user->save();

            Wallet::firstOrCreate(

                [
                    'user_id' => $user->id,
                ],

                [
                    'account_number' => WalletGenerate::accountNumber(),
                    'amount' => 0,
                ]

            );
            DB::commit();
            return redirect()->route('user.user.index')->with('create', 'Successfully Created');
        } catch (\Exception $err) {
            DB::rollBack();
            return back()->withErrors(['fails' => 'Account Create not success !'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->update();

            Wallet::firstOrCreate(

                [
                    'user_id' => $user->id,
                ],

                [
                    'account_number' => WalletGenerate::accountNumber(),
                    'amount' => 0,
                ]

            );
            DB::commit();
            return redirect()->route('user.user.index')->with('update', 'User was Successfully Update');
        } catch (\Exception $e) {
            //throw $e;
            DB::rollBack();
            return back()->withErrors(['fails' => 'This Account is already exit...!' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return 'success';
    }
}
