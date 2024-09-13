<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('loan_product_id');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');//pending, approved, rejected, disbursed, repaid
            $table->date('due_date');
            $table->timestamps();
            //add soft deletes for archiving/safety
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('loan_product_id')->references('id')->on('loan_products')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
