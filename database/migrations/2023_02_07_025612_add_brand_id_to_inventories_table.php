<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandIdToInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->after('department_id');
            $table->unsignedBigInteger('location_id')->nullable()->after('reference_date');

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('brand_id');
            $table->dropColumn('location_id');
        });
    }
}
