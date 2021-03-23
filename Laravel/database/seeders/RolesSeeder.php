<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('roles')->insert([
            'id_role' => '1',
            'description' =>'Administrador del sistema',
        ]);

        DB::table('roles')->insert([
            'id_role' => '2',
            'description' =>'Oncólogos',
        ]);
    }
}
