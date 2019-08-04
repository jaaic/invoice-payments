<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Invoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_nr')->unique();
            $table->string('title');
            $table->string('tenant_phone_number');
            $table->string('tenant_email');
            $table->string('owner_phone_number');
            $table->string('owner_email');
            $table->string('path');
            $table->unsignedDecimal('amount', 15, 3);
            $table->string('currency');
            $table->date('due_date');
            $table->boolean('isPaid');
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
        Schema::dropIfExists('invoices');
    }
}
