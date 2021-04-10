<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->insert([
            'nombre' => 'Admin',
            'apellidos' =>'administrador',
            'email' => 'administrador@gmail.com',
            'contrasena' => Hash::make('1234'),
            'id_rol' => 1,
        ]);
    }
}
