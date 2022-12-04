<?php

namespace App\Http\Controllers\Wallet;

use Carbon\Carbon;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class WalletController extends Controller
{
    public function index()
    {
        return view('backend.wallets.index');
    }

    public function ssd()
    {
        $wallets = Wallet::query();

        return DataTables::of($wallets)
            ->addColumn('account_person', function ($e) {
                $user = $e->user;
                if ($user) {
                    return '<p>Name : ' . $user->name . ' </p> <p>Email : ' . $user->email . ' </p> <p>Phone : ' . $user->phone . '</p>';
                }
                return '-';
            })
            ->editColumn('amount', function ($e) {
                return number_format($e->amount, 2);
            })
            ->editColumn('created_at', function ($e) {
                return Carbon::parse($e->created_at)->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function ($e) {
                return Carbon::parse($e->updated_at)->format('Y-m-d H:i:s');
            })
            ->rawColumns(['account_person'])
            ->make(true);
    }
}
