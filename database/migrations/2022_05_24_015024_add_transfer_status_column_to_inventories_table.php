<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransferStatusColumnToInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->string('transfer_status')->nullable();
        });
        Schema::table('specifications', function (Blueprint $table) {
            $table->string('spec_status')->nullable();
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
            $table->dropColumn('transfer_status');
        });
        Schema::table('specifications', function (Blueprint $table) {
            $table->dropColumn('spec_status');
        });
    }
}
