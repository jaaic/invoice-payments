<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReminderNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('content');
            $table->string('invoice_id');
            $table->string('account_nr');
            $table->string('to');
            $table->string('type');
            $table->string('channel');
            $table->date('process_at')->nullable(true);
            $table->boolean('is_triggered');
            $table->date('triggered_at')->nullable(true);
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
        Schema::dropIfExists('reminder_notifications');
    }
}
