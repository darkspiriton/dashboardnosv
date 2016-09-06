<?php

use Dashboard\Models\Experimental\Client;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuxClientTest extends TestCase
{
    use WithoutMiddleware;

    // public function setUp(){
    //     parent::setUp();
        
    // }

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
    
    public function testClientDelete(){

        $client=$this->call('GET','/api/auxclient/test666');
        
        $id=json_decode($client->content(),true)[0]["id"];               

        $response = $this->call('DELETE','/api/auxclient/'.$id);
        // DB::table('auxclients')->where('name','Test666')->delete();
        $this->assertEquals(200,$response->status());
    }    

    /**
     * [testClientStoreFails description]
     * @return [type] [description]
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
