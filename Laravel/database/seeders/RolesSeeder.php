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
            'id_rol' => '1',
            'descripcion' =>'Administrador del sistema',
        ]);

        DB::table('roles')->insert([
            'id_rol' => '2',
            'descripcion' =>'Oncólogo',
        ]);
    }
}
