<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::table('roles')->insert(['abrev' => 'GOD', 'description' => 'GOD']);
            DB::table('roles')->insert(['abrev' => 'ADM', 'description' => 'Administracion']);
            DB::table('roles')->insert(['abrev' => 'VEN', 'description' => 'Vendedor']);
            DB::table('roles')->insert(['abrev' => 'JVE', 'description' => 'Jefe de ventas']);
            DB::table('roles')->insert(['abrev' => 'MOT', 'description' => 'Motorizado']);
        });
    }
}
