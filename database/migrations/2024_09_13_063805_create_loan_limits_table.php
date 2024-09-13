<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->decimal('limit_amount', 10, 2);
            $table->timestamps();
            // add soft deletes for archiving/safety
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_limits');
    }
}
