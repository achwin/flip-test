<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->bigInteger('amount')->default(0);
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED']);
            $table->string('bank_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('remark')->nullable();
            $table->string('receipt')->nullable();
            $table->timestamp('time_served', 0);
            $table->bigInteger('fee')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraw');
    }
}
