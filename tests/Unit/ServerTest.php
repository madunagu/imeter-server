<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class ServerTest extends TestCase
{

    public function testRegister()
    {
        $faker = Faker::create();

        $params = [
            "name"=> $faker->name,
            "email"=> $faker->email,
            "password"=> 'test',
        ];
        $response = $this->json(
            'POST',
            '/api/register',
            $params
        );
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                "success"=> true,
            ]);;
    }



    public function testLogin()
    {
        $faker = Faker::create();

        $user =   User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password'=>bcrypt('test'),
            'is_verified'=>true
        ]);

        $params = [
            "email"=>$user->email,
            "password"=> 'test',
        ];
        $response = $this->post(
            'api/login',
            $params
        );
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                "success"=> true,
            ]);
    }

    /**
     * Test if server saves energy budget
     *
     * @return void
     */

    public function testEnergyBudget()
    {
        Passport::actingAs(
            User::find(1)
        );

        $response = $this->post(
            '/api/energy-budget',
            [
                "amount"=>"2000",
                "enforcement"=> 'W',
            ]
        );
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => true
            ]);
    }


     public function testRechargeMeter()
     {
         Passport::actingAs(
             User::find(1)
         );

         $response = $this->post(
             '/api/energy-budget',
             [
                 "amount"=>"2000",
                 "enforcement"=> 'W',
             ]
         );
         $response
             ->assertStatus(200)
             ->assertJsonFragment([
                 'success' => true
             ]);
     }

    // public function testIOTdata()
    // {
    // }

    // public function testBlog()
    // {
    // }

     public function testSmartConnect()
     {
         Passport::actingAs(
             User::find(1)
         );

         $response = $this->post('/api/toggle-on');

         $response->assertStatus(200);

     }
}
