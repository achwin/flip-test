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
            'status'    => Withdraw::STATUS_PENDING,
            'store_id'  => $store->id,
            'amount'    => $data['amount'],
            'bank_code' => $store->bank_code
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

        return $response;
    }

}
