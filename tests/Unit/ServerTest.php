<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Resources\User;

class ServerTest extends TestCase
{
    /**
     * Test if server saves energy budget
     *
     * @return void
     */

    // public function testEnergyBudget()
    // {
    //     $params = [
    //         "MN"=>"01223567AB", // This is the meter number
    //         "Msg"=> 1, // The message type of for regular meter reading
    //         "S"=>"2243575687", // POSIX Timestamp in seconds of the request message
    //         "DT"=>"2243575687", // POSIX Timestamp in seconds of the capture time request message
    //         "EB"=>"150.00", // Energy Balance
    //         "EF"=> 3
    //     ];
    //     $params = json_encode($params);
    //     $response = $this->withHeaders([
    //         'X-Header' => 'Value',
    //     ])->json(
    //         'POST',
    //         '/api/collector',
    //     ['Password'=> $params]
    // );

    //     \Log::info($response->json());
    //     $response
    //         ->assertStatus(200)
    //         ->assertJsonFragment([
    //             'MN' => '01223567AB'
    //         ]);
    // }

    public function testLogin()
    {
        $user = User::create(
        [
            'email'=>'test@test.com',
            'password'=>'testpass'   ,
            'name'=> 'Created By Test',
            'is_verified'=>true
        ]);
        $params = [
            "email"=>$user->email,
            "password"=> $user->password,
        ];
        $response = $this->withHeaders([
                        'X-Header' => 'Value',
                    ])->json(
                        'POST',
                        '/api/collector',
                    [$params]
                );

        \Log::info($response->json());
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
            'MN' => '01223567AB'
            ]);
    }

    // public function testToggleOn()
    // {
    // }

    // public function testRechargeMeter()
    // {
    // }

    // public function testIOTdata()
    // {
    // }

    // public function testBlog()
    // {
    // }

    // public function testSmartConnect()
    // {
    //     $params = [
    //         "MN"=>"01223567AB", // This is the meter number
    //         "Msg"=> 1, // The message type of for regular meter reading
    //         "S"=>"2243575687", // POSIX Timestamp in seconds of the request message
    //         "DT"=>"2243575687", // POSIX Timestamp in seconds of the capture time request message
    //         "CD"=> 0 , // For smart disconnect
    //     ];
    //     $params = json_encode($params);
    //     $response = $this->withHeaders([
    //         'X-Header' => 'Value',
    //     ])->json(
    //         'POST',
    //         '/api/collector',
    //     ['Password'=> $params]
    //     );

    //     \Log::info($response->json());
    //     $response
    //         ->assertStatus(200)
    //         ->assertJsonFragment([
    //             'MN' => '01223567AB'
    //         ]);
    // }
}
