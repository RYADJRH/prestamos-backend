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
            $table->unsignedBigInteger('amount_payment');
            $table->dateTime('created_payment');
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
