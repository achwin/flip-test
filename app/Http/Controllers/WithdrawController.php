<?php

namespace App\Http\Controllers;

use App\Models\Withdraw;
use App\Services\WithdrawService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{

    protected const PER_PAGE = 5;

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application withdarw.
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $store = Auth::user()->store;

        $withdraws = Withdraw::where('store_id', $store->id)
            ->orderBy('created_at', 'desc')
            ->simplePaginate(self::PER_PAGE);

        return view('pages.withdraw.index', [
            'store' => $store,
            'withdraws' => $withdraws
        ]);
    }

    /**
     * Show form withdraw
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $store = Auth::user()->store;
        if (is_null($store)) {
            abort(403, 'Unauthorized action.');
        }

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
            return redirect()
                ->back()
                ->with(['error' => $response['message']])
                ->withInput();
        }

        return redirect()->route('withdraw.index');
    }

    /**
     * Get status by transaction_id
     * @return \Illuminate\Http\Response
     */
    public function get(string $transactionID, WithdrawService $withdrawService)
    {
        $disburse = $withdrawService->getWithdrawStatus($transactionID);
        return response()->json(['success'=> true, 'disburse' => $disburse]);
    }
}
