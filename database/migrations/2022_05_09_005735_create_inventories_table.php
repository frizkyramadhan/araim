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
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('restrict');
            $table->foreignId('asset_id')->nullable()->constrained('assets')->onDelete('restrict');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('restrict');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('restrict');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('restrict');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('restrict');
            // $table->string('brand')->nullable();
            $table->string('model_asset')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('part_no')->nullable();
            $table->string('po_no')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('spesifikasi')->nullable();
            $table->string('remarks')->nullable();
            $table->date('input_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->string('reference_no')->nullable();
            $table->date('reference_date')->nullable();
            // $table->string('location')->nullable();
            $table->string('qrcode')->nullable();
            $table->string('inventory_status')->nullable();
            $table->string('transfer_status')->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('inventories');
    }
}
