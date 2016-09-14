<?php

use Dashboard\Models\Experimental\Client;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AuxClientTest extends TestCase
{
    use WithoutMiddleware;

    // public function setUp(){
    //     parent::setUp();
        
    // }

    /**
     * [testClientConection description]
     * @return [type] [description]
     */
    public function testClientConection(){
            try{
                DB::connection('mysql')->getDatabaseName();
            }catch(PDOException $e){
                $this->assertNotTrue(true);
            }
            $this->assertTrue(true);
        }

    /**
     * Prueba el retorno de todos los clientes registrados en el sistemas
     * @return [void] [Valida si el retorno es exitoso]
     */
    public function testClientIndex(){
        $clients=$this->call('GET','/api/auxclient');
        $this->assertEquals(200,$clients->status());
    }

    /**
     * Se prueba el registro de un usuario nuevo
     * @return [void] [Valida si el retorno es exitoso]
     */
    public function testClientStoreSucess(){
        $response = $this->call('POST','/api/auxclient',[
                'name' => 'test666',
                'email' => 'test6660@gmail.com',
                'phone' => '016666666',
                'dni' => '66666666',                                
                'address' => 'Lima 666',
                'reference' => 'Lima 666',
                'facebook_id' => 666666,
                'facebook_name' => 'Test 666',                
            ]);
        $this->assertEquals(200, $response->status());
    }

    /**
     * Se prueba que el cliente test666 esta registrado correctamente
     * @return [void] [Valida si el retorno es exitoso]
     */
    public function testClientShow(){
        $client=$this->call('GET','/api/auxclient/test666');
        $name=json_decode($client->content(),true)[0]["name"];
        $this->get('/api/auxclient/'.$name)
            ->seeJson([
                "address"=>"Lima 666",
                "deleted_at"=>null,
                "dni"=>"66666666",
                "email"=>"test6660@gmail.com",
                "facebook_id"=>666666,
                "facebook_name"=>"Test 666",
                "name"=>"Test666",
                "phone"=>"016666666",
                "reference"=>"Lima 666",
            ]);
    }
    
    /**
     * Este test se elimina el cliente test666 modo soft
     * @return [void] [Valida si el retorno es exitoso]
     */
    public function testClientDelete(){

        $client=$this->call('GET','/api/auxclient/test666');        
        $id=json_decode($client->content(),true)[0]["id"];               

        $response = $this->call('DELETE','/api/auxclient/'.$id);
        
        $this->assertEquals(200,$response->status());
    }

    /**
     * Este test restaura el cliente test666 del modo soft
     * @return [void] [Valida si el retorno es exitoso]
     */
    public function testClientRestore(){
       $client=$this->call('GET','/api/auxclient/get/delete');       
       $id=json_decode($client->content(),true)["clients"][0]["id"];

       $response = $this->call('POST','/api/auxclient/delete/restore/'.$id);

       
       $this->assertEquals(200,$response->status());
    }   

    /**
     * Este test retorna los movimientos relacionados de un cliente
     * @return [void] [Valida si el retorno es exitoso]
     */
    public function testClientMovement(){
        $client=$this->call('GET','/api/auxclient/test666');        
        $id=json_decode($client->content(),true)[0]["id"];
        $movement = $this->call('GET','/api/auxclient/get/movement/'.$id,["status"=>"1","year"=>2016,"month"=>8]);
        DB::table('auxclients')->where('name','Test666')->delete();
        $this->assertEquals(200,$movement->status());
    }

    /**
     * Este test valida que los paramentros para guardar un cliente funciona
     * @return [void] [Valida si el retorno es exitoso]
     */
    public function testClientStoreFails(){
        $client=$this->call('POST','/api/auxclient',[                
                'email' => 'cesar200526@gmail.com',
                'phone' => '015751307',
                'dni' => '42655079',
                'address' => 'Lima',
                'reference' => 'Lima',
                'facebook_id' => '11111111111',
                'facebook_name' => 'Cesar Moreno Test',
        ]);
        $this->assertEquals(403,$client->status());
    }

    // public function tearDown()
    // {        
    //     parent::tearDown();
    // }
}
