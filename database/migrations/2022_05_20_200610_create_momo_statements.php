<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('momo_statements', function (Blueprint $table) {
            $table->id();
            $table->datetime('transaction_date');
            $table->string('from_acct');
            $table->string('from_name')->nullable();
            $table->string('from_no');
            $table->string('transaction_type');
            $table->double('amount', 8, 2);
            $table->double('fees', 8, 2);
            $table->double('e-levy', 8, 2);
            $table->double('bal_before', 8, 2);
            $table->double('bal_after', 8, 2);
            $table->string('to_no')->nullable();
            $table->string('to_name')->nullable();
            $table->string('to_acct');
            $table->string('f_id');
            $table->string('ref')->nullable();
            $table->string('ova');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('statement_file_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('statement_file_id')->references('id')->on('statement_files');
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
        Schema::dropIfExists('momo_statements');
    }
};
