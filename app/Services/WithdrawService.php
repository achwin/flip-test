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
        return true;
    }
}
