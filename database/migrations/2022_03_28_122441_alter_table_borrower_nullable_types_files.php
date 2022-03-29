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
        Schema::table('borrowers', function (Blueprint $table) {
            $table->string('name_file_ine_borrower')->nullable(true)->change();
            $table->string('name_file_proof_address_borrower')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrowers', function (Blueprint $table) {
            $table->string('name_file_ine_borrower')->unsigned()->nullable(false)->change();
            $table->string('name_file_proof_address_borrower')->unsigned()->nullable(false)->change();
        });
    }
};
