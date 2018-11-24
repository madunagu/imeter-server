<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Storage;


class ServerTest extends TestCase
{


    public function testLogin()
    {
        $user = User::where('id',1)->first();
        $params = [
            "email"=>$user->email,
            "password"=> $user->password,
        ];
        $response = $this->withHeaders([
                        'X-Header' => 'Value',
                    ])->json(
                        'POST',
                        '/api/collector',
                    $params
                );
        Storage::put('login.json', json_encode($response->json()));

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
        $login = Storage::get('login.json');

        $bearer = json_decode($login)->data->token;
        $response = $this->withHeaders([
            'Authorization' => "Bearer $bearer",
        ])->json(
            'POST',
            '/api/energy-budget',
            [
                "amount"=>"2000",
                "enforcement"=> 'W',
            ]
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
