<?php

namespace App\Http\Controllers;

use App\Models\Withdraw;
use App\Services\WithdrawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application withdarw.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $store = Auth::user()->store;
        $withdraws = Withdraw::where('store_id', $store->id)->get();

        return view('pages.withdraw.index', [
            'store' => $store,
            'withdraws' => $withdraws
        ]);
    }

    public function create()
    {
        $store = Auth::user()->store;
        return view('pages.withdraw.create', ['store' => $store]);
    }

    /**
     * Store withdraw
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request, WithdrawService $withdrawService)
    {
        $store = Auth::user()->store;
        if (is_null($store)) {
            abort(403, 'Unauthorized action.');
        }

        $response = $withdrawService->storeWithdraw($store, $request->all());
        if (!$response['success']) {
            return redirect()->back()->withErrors(['message', 'fail to request']);
        }

        return redirect()->route('withdraw.index');
    }

    public function get(string $transactionID, WithdrawService $withdrawService)
    {
        $disburse = $withdrawService->getWithdrawStatus($transactionID);
        return response()->json(['success'=> true, 'disburse' => $disburse]);
    }
}
