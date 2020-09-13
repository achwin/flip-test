<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{

    const STATUS_PENDING = 'PENDING';
    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_FAILED  = 'FAILED';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'status',
        'nominal',
        'store_id',
        'amount',
        'bank_code',
        'remark',
        'account_number',
        'beneficiary_name',
        'receipt',
        'fee',
        'time_served',
    ];

    /**
     * create withdraw data
     * @return Withdraw
     */
    public static function createWithdraw($storeID, $disburse)
    {
        return Withdraw::create([
            'status'            => Withdraw::STATUS_PENDING,
            'store_id'          => $storeID,
            'account_number'    => $disburse['account_number'],
            'amount'            => $disburse['amount'],
            'bank_code'         => $disburse['bank_code'],
            'transaction_id'    => $disburse['id'],
            'beneficiary_name'  => $disburse['beneficiary_name'],
            'remark'            => $disburse['remark'],
            'receipt'           => $disburse['receipt'],
            'time_served'       => $disburse['time_served'],
            'fee'               => $disburse['fee'],
        ]);
    }
}
