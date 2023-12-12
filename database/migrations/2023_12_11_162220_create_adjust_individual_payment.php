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
        Schema::create('adjust_individual_payment', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('before_amount');
            $table->bigInteger('after_amount');
            $table->dateTime('date_adjust_payment');
            $table->foreignId('id_payment')->constrained('individual_payments','id_payment')->onDelete('cascade');
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
        Schema::dropIfExists('adjust_individual_payment');
    }
};
