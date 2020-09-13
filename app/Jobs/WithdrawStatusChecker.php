<?php

namespace App\Jobs;

use App\Models\Withdraw;
use App\Services\WithdrawService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WithdrawStatusChecker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    protected $transactionID;
    protected const INTERVAL_CHECKER = 1; // one minute

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $transactionID)
    {
        $this->transactionID = $transactionID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $withdrawService = app()->make(WithdrawService::class);
        $result = $withdrawService->getWithdrawStatus($this->transactionID);

        if (
            is_null($result)
            || is_null($result['disburse'])
            || $result['disburse']->status === Withdraw::STATUS_PENDING
        ) {
            // worker to re-check status after one minute
            $this->dispatch($this->transactionID)->delay(now()->addMinutes(self::INTERVAL_CHECKER));
        }
    }
}
