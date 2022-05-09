<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_no');
            $table->foreignId('employee_id')->references('id')->on('employees');
            $table->foreignId('asset_id')->references('id')->on('assets');
            $table->foreignId('project_id')->references('id')->on('projects');
            $table->foreignId('department_id')->references('id')->on('departments');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('part_no')->nullable();
            $table->string('po_no')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('spesifikasi')->nullable();
            $table->string('remarks')->nullable();
            $table->dateTime('input_date')->nullable();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->string('reference_no')->nullable();
            $table->dateTime('reference_date')->nullable();
            $table->string('location')->nullable();
            $table->string('qrcode')->nullable();
            $table->string('inventory_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
