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
        Schema::create('group_borrowers', function (Blueprint $table) {
            $table->id('id_group_borrower');
            $table->foreignId('id_borrower')->constrained('borrowers', 'id_borrower')->onDelete('cascade');
            $table->foreignId('id_group')->constrained('groups', 'id_group')->onDelete('cascade');
            $table->unsignedBigInteger('amount_pay');
            $table->unsignedBigInteger('amount_borrow');
            $table->unsignedBigInteger('amount_interest');
            $table->string('state_borrow', 15);
            $table->unique(['id_borrower', 'id_group'],'borrower_group_unique');
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
        Schema::dropIfExists('group_borrowers');
    }
};
