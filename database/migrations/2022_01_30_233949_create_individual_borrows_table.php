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
        Schema::create('individual_borrows', function (Blueprint $table) {
            $table->id('id_borrow');
            $table->dateTime('created_borrow');
            $table->unsignedBigInteger('amount_borrow');
            $table->unsignedBigInteger('amount_interest');
            $table->integer('day_payment');
            $table->string('state_payment', 15);
            $table->foreignId('id_borrower')->constrained('borrowers', 'id_borrower')->onDelete('cascade');
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
        Schema::dropIfExists('individual_borrows');
    }
};
