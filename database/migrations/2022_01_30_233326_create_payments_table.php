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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id_payment');
            $table->integer('num_payment');
            $table->string('state_payment', 15)->default('in_proccess');
            $table->dateTime('date_payment');
            $table->bigInteger('amount_payment_period');
            $table->bigInteger('remaining_balance');
            $table->foreignId('id_group_borrower')->constrained('group_borrowers', 'id_group_borrower')->onDelete('cascade');
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
        Schema::dropIfExists('payments');
    }
};
