<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\Withdraw;
use App\Services\SlightlyBigFlip\Client;
use App\Services\WithdrawService;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Mockery;

class WithdrawTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test for withdraw feature
     * @return void
     */
    public function testCreateWithdrawService()
    {
        Queue::fake();

        // prepare data
        $store = factory(Store::class)->create([
            'user_id' => factory(User::class)->create()
        ]);

        $withdraw = [
            'store_id'  => $store->id,
            'amount'    => 100,
            'bank_code' => 'bca',
            'remark'    => 'Butuh duit nih'
        ];

        $fakeResponse = (array) [
            "id" => 5535152564,
            "amount" => 10000,
            "status" => "PENDING",
            "timestamp" => "2019-05-21 09:12:42",
            "bank_code" => "bni",
            "account_number" => "1234567890",
            "beneficiary_name" => "PT FLIP",
            "remark" => "sample remark",
            "receipt" => null,
            "time_served" => "0000-00-00 00:00:00",
            "fee" => 4000
        ];

        // mocking
        $mock = $this->mockAlternatively(Client::class);
        $mock->shouldReceive('createDisbursement')->andReturn($fakeResponse);

        // call function
        $withdrawService = $this->app->make(WithdrawService::class);
        $result = $withdrawService->storeWithdraw($store, $withdraw);

        // assert
        $this->assertEquals(true, $result['success']);
        $this->assertDatabaseHas('withdraws', [
            'id' => $result['withdraw']->id,
            'status' => Withdraw::STATUS_PENDING
        ]);
    }

    public function mockAlternatively($class)
    {
        $this->mock = Mockery::mock($class);
        $this->app->instance($class, $this->mock);

        return $this->mock;
    }
}
