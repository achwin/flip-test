<?php
namespace App\Services;

use App\Models\Store;
use App\Models\Withdraw;

class WithdrawService
{
    /**
     * Store withdarw service
     *
     * @param array
     * @return array
     */
    public function storeWithdraw(Store $store, array $data): array
    {
        if ($store->saldo < $data['amount']) {
            return [
                'success' => false,
                'message' => 'not enough balance',
                'withdraw' => null,
            ];
        }

        $response = $this->callApiSlighlyBigFlip([
            'account_number' => $store->account_number,
            'bank_code' => $store->bank_code,
            'remark' => $data['remark'],
            'amount' => $data['amount'],
        ]);

        // check status from response api
        // save response to db
        $withdraw = Withdraw::create([
            'account_number' => $store->account_number,
            'status'    => Withdraw::STATUS_PENDING,
            'store_id'  => $store->id,
            'amount'    => $data['amount'],
            'bank_code' => $store->bank_code,
            'transaction_id' => $response->id,
            'beneficiary_name' => $response->beneficiary_name,
            'remark' => $response->remark,
            'receipt' => $response->receipt,
            'time_served' => $response->time_served,
            'fee' => $response->fee,
        ]);

        return [
            'success' => true,
            'withdraw' => $withdraw,
        ];
    }

    public function callApiSlighlyBigFlip(array $data)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', env('API_DISBURSE_SERVICE') . '/disburse', [
            'json' => [
                'bank_code'         => $data['bank_code'],
                'amount'            => $data['amount'],
                'remark'            => $data['remark'],
                'account_number'    => $data['account_number'],
            ],
            'auth' => [
                env('API_BASIC_AUTH_USERNAME'),
                env('API_BASIC_AUTH_PASSWORD'),
            ]
        ]);
    
        return json_decode($response->getBody());
    }

    public function getWithdrawStatus(string $transactionID)
    {
        $disburse = $this->callApiGetDisburse($transactionID);
        // dd($disburse);
        $withdraw = Withdraw::
            where('transaction_id',$transactionID)->
            update([
                'status' => $disburse->status,
                'receipt' => $disburse->receipt,
                'time_served' => $disburse->time_served,
            ]);
        return $disburse;
    }

    public function callApiGetDisburse(string $transactionID)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', env('API_DISBURSE_SERVICE') . '/disburse/'.$transactionID, [
            'auth' => [
                env('API_BASIC_AUTH_USERNAME'),
                env('API_BASIC_AUTH_PASSWORD'),
            ]
        ]);
        return json_decode($response->getBody());
    }
}
