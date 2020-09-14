<?php
namespace App\Services;

use App\Jobs\WithdrawStatusChecker;
use App\Models\Store;
use App\Models\Withdraw;
use App\Services\SlightlyBigFlip\Client;
use Illuminate\Support\Facades\Log;

class WithdrawService
{
    const ERR_NOT_ENOUGH_BALANCE = 'not enaough balance';
    const ERR_3RD_PARTY_ERROR = 'fail to access 3rd party api';

    /**
     * Store withdraw service
     *
     * @param array
     * @return array
     */
    public function storeWithdraw(Store $store, array $data): array
    {
        try {
            // validate balance
            if ($store->saldo < ($data['amount'] + env('DEFAULT_DISBURSE_FEE', 4000))) {
                return [
                    'success' => false,
                    'message' => self::ERR_NOT_ENOUGH_BALANCE,
                ];
            }

            // call 3rd party
            $bigFlip = app()->make(Client::class);
            $disburse = $bigFlip->createDisbursement([
                'account_number' => $store->account_number,
                'bank_code' => $store->bank_code,
                'remark' => $data['remark'],
                'amount' => $data['amount'],
            ]);

            $disburse['time_served'] = null;
            // save response to db
            $withdraw = Withdraw::createWithdraw($store->id, $disburse);

            // update saldo
            $store->saldo = $store->saldo - ($data['amount'] + $disburse['fee']);
            $store->save();

            // init job for status checker
            WithdrawStatusChecker::dispatch($disburse['id']);
            return [ 'success' => true, 'withdraw' => $withdraw,];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ['message' => $th->getMessage(), 'success' => false];
        }
    }

    /**
     * Get withdraw status of transaction
     *
     * @param string
     * @return array
     */
    public function getWithdrawStatus(string $transactionID): array
    {
        try {
            $bigFlip = app()->make(Client::class);
            $disburse = $bigFlip->getDisbursementByTransactioID($transactionID);

            Withdraw::where('transaction_id', $transactionID)
                ->update([
                    'status' => $disburse['status'],
                    'receipt' => $disburse['receipt'],
                    'time_served' => $disburse['time_served'],
                ]);

            return ['success' => true, 'disburse' => $disburse];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ['success' => false];
        }
    }

}
