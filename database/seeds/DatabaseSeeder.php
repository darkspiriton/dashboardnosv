<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();       
        
//        $this->call(UbigeoTableSeeder::class);
       // $this->call(UsersTableSeeder::class);
        // $this->call(UsersSystemTableSeeder::class);
//        $this->call(PlanillaTableSeeder::class);
//        $this->call(QuestionnairesTableSeeder::class);
       // $this->call(ProductsTableSeeder::class);
       // $this->call(PrestashopSeed::class);
//        $this->call(GeneralTablesSeeder::class);
       $this->call(AuxProductsTableSeeder::class);
        Model::reguard();
    }
}
