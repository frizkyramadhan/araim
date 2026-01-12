<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('migrations')->insert([
            'migration' => '2022_05_11_013918_add_level_and_userstatus_column_at_users_table',
            'batch' => 1
        ]);

        DB::table('migrations')->insert([
            'migration' => '2022_05_11_020821_rename_column_name_to_fullname_on_employees_table',
            'batch' => 1
        ]);

        DB::table('migrations')->insert([
            'migration' => '2022_05_17_054637_rename_column_model_to_modelasset_on_inventories_table',
            'batch' => 1
        ]);

        DB::table('migrations')->insert([
            'migration' => '2022_05_24_015024_add_transfer_status_column_to_inventories_table',
            'batch' => 1
        ]);

        DB::table('migrations')->insert([
            'migration' => '2022_08_08_032657_add_is_active_to_inventories_table',
            'batch' => 1
        ]);
    }
}
