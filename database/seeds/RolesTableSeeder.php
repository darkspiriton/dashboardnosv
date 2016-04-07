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
            DB::table('roles')->insert(['abrev' => 'GOD', 'name' => 'GOD']);
            DB::table('roles')->insert(['abrev' => 'ADM', 'name' => 'Administracion']);
            DB::table('roles')->insert(['abrev' => 'VEN', 'name' => 'Vendedor']);
            DB::table('roles')->insert(['abrev' => 'JVE', 'name' => 'Jefe de ventas']);
            DB::table('roles')->insert(['abrev' => 'MOT', 'name' => 'Motorizado']);
        });
    }
}
