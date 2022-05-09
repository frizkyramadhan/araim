<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBapbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bapbs', function (Blueprint $table) {
            $table->id();
            $table->string('bapb_no');
            $table->string('bapb_reg');
            $table->dateTime('bapb_date');
            $table->foreignId('bapb_submit')->references('id')->on('employees');
            $table->foreignId('bapb_receive')->references('id')->on('employees');
            $table->foreignId('inventory_id')->references('id')->on('inventories');
            $table->string('duration')->nullable();
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
        Schema::dropIfExists('bapbs');
    }
}
