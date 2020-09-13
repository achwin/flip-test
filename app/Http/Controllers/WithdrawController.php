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

    public function store(Request $request, WithdrawService $withdrawService)
    {
        // validation
        $data = $request->all();
        $withdrawService->storeWithdraw($data);
    }
}
