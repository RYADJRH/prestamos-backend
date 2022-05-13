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
        Schema::create('individual_payments', function (Blueprint $table) {
            $table->id('id_payment');
            $table->integer('num_payment');
            $table->dateTime('date_payment');
            $table->bigInteger('amount_payment_period');
            $table->bigInteger('remaining_balance');
            $table->string('state_payment', 15)->default('in_proccess');
            $table->foreignId('id_borrow')->constrained('individual_borrows', 'id_borrow')->onDelete('cascade');
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
        Schema::dropIfExists('individual_payments');
    }
};
