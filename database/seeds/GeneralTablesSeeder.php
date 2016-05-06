<?php

use Illuminate\Database\Seeder;

class GeneralTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {

            DB::table('status_call')->insert([
                ['name' => 'Atendido'],
                ['name' => 'No contesta'],
                ['name' => 'Contesta, volver a llamar'],
                ['name' => 'Contesta, no esta interesado']
            ]);

            DB::table('status_order')->insert([
                ['name' => 'Emitida'],
                ['name' => 'Pagada'],
                ['name' => 'Cancelada']
            ]);

            DB::table('types_shipments')->insert([
                ['name' => 'Local'],
                ['name' => 'Provincia (Olva)'],
                ['name' => 'Provincia (Serpost)']
            ]);

            DB::table('shipments_status')->insert([
                ['name' => 'Local'],
                ['name' => 'Provincia (Olva)'],
                ['name' => 'Provincia (Serpost)']
            ]);
        });

        factory(\Dashboard\Models\Order\Order::class, 25)->create()->each(function($order){
            $order->shipment()->saveMany(factory(\Dashboard\Models\Shipment\Shipment::class, 2)->make());
            $order->calls()->saveMany(factory(\Dashboard\Models\Order\Call::class, 3)->make());
            $order->details()->saveMany(factory(\Dashboard\Models\Order\Detail::class, 3)->make());
        });
    }
}
