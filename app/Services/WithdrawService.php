<?php
namespace App\Services;

use App\Models\Withdraw;

class WithdrawService
{
    /**
     * Store withdarw service
     *
     * @param array
     * @return array
     */
    public function storeWithdraw(array $data): array
    {
        $this->callApiSlighlyBigFlip($data);

        // save response to db
        $withdraw = Withdraw::create([
            'status'    => Withdraw::STATUS_PENDING,
            'store_id'  => $data['store_id'],
            'amount'    => $data['amount'],
            'bank_code' => $data['bank_code'],
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
                'bank_code' => 'bar',
                'amount' => 100,
                'remark' => 'sample remark',
                'account_number' => '1234567890',
            ],
            'auth' => [
                env('API_BASIC_AUTH_USERNAME'),
                env('API_BASIC_AUTH_PASSWORD'),
            ]
        ]);

        return $response;
    }

}
