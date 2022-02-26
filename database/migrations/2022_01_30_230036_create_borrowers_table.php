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
        Schema::create('borrowers', function (Blueprint $table) {
            $table->id('id_borrower');
            $table->string('name_borrower', 50);
            $table->string('last_name_borrower', 100);
            $table->string('name_file_ine_borrower');
            $table->string('name_file_proof_address_borrower');
            $table->foreignId('id_beneficiary')->constrained('beneficiaries', 'id_beneficiary')->onDelete('cascade');
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
        Schema::dropIfExists('borrowers');
    }
};
