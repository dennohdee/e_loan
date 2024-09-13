<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id');
            $table->decimal('amount', 10, 2);
            $table->string('transaction_type')->nullable(); //mpesa, bank transfer, cash
            $table->string('reference')->nullable();
            $table->string('status')->default('pending'); //successful, pending, failed
            $table->timestamps();
            //added soft deletes for archiving/safety
            $table->softDeletes();

            $table->foreign('loan_id')->references('id')->on('loans')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_repayments');
    }
}
