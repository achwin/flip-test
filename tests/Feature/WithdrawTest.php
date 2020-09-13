<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\Withdraw;
use App\Services\WithdrawService;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WithdrawTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test for withdraw feature
     * @return void
     */
    public function testWithdrawService()
    {
        // prepare data
        $store = factory(Store::class)->create([
            'user_id' => factory(User::class)->create()
        ]);

        $withdraw = [
            'store_id'  => $store->id,
            'amount'    => 100,
            'bank_code' => 'bca',
        ];

        // call function
        $withdrawService = $this->app->make(WithdrawService::class);
        $result = $withdrawService->storeWithdraw($withdraw);

        // assert
        $this->assertEquals(true, $result['success']);
        $this->assertDatabaseHas('withdraws', [
            'id' => $result['withdraw']->id,
            'status' => Withdraw::STATUS_PENDING
        ]);
    }
}
