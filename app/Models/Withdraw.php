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
}
