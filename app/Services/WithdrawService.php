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

        if (!$response['success']) {
            return [
                'success' => false,
            ];
        }

        $disburse = $response['data'];
        // check status from response api
        // save response to db
        $withdraw = Withdraw::create([
            'account_number' => $store->account_number,
            'status'    => Withdraw::STATUS_PENDING,
            'store_id'  => $store->id,
            'amount'    => $data['amount'],
            'bank_code' => $store->bank_code,
            'transaction_id' => $disburse->id,
            'beneficiary_name' => $disburse->beneficiary_name,
            'remark' => $disburse->remark,
            'receipt' => $disburse->receipt,
            'time_served' => $disburse->time_served,
            'fee' => $disburse->fee,
        ]);

        $store->saldo = $store->saldo - $data['amount'];
        $store->save();

        return [
            'success' => true,
            'withdraw' => $withdraw,
        ];
    }

    public function callApiSlighlyBigFlip(array $data)
    {
        try {
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
        
            return [
                'data' => json_decode($response->getBody()),
                'success' => true,
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false
            ];
        }
    }

    public function getWithdrawStatus(string $transactionID)
    {
        $response = $this->callApiGetDisburse($transactionID);
        if (!$response['success']) {
            return '';
        }

        $disburse = $response['data'];
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
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', env('API_DISBURSE_SERVICE') . '/disburse/'.$transactionID, [
                'auth' => [
                    env('API_BASIC_AUTH_USERNAME'),
                    env('API_BASIC_AUTH_PASSWORD'),
                ]
            ]);

            return [
                'data' => json_decode($response->getBody()),
                'success' => true,
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false
            ];
        }
    }
}
