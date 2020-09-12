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
        Schema::create('withdraw', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount');
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED']);
            $table->string('banc_code');
            $table->string('account_number');
            $table->string('beneficiary_name');
            $table->string('remark');
            $table->string('receipt');
            $table->timestamp('time_served', 0);
            $table->bigInteger('fee');
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
