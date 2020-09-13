<?php

namespace App\Http\Controllers;

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

        $withdrawService->storeWithdraw($store, $request->all());
    }

    public function get(string $transactionID, WithdrawService $withdrawService)
    {
        // validation
        $disburse = $withdrawService->getWithdrawStatus($transactionID);
        dd($disburse);
        return $disburse;
    }
}
