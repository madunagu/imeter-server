<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CollectorTest extends TestCase
{
    /**
     * A basic http test for tamper.
     *
     * @return void
     */
    public function testTamperReport()
    {
        $params = ['MN' => '01223567AB','Msg'=>'4','CT'=>'1539025265','T'=>'8','S'=>'1539025265'];
        $params = json_encode($params);
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'POST',
            '/api/collector',
            ['Password'=> $params]
        );

        \Log::info($response->json());
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['MN' => '01223567AB']);
    }

    public function testHourlyUsage()
    {
        $params = [
            "MN"=>"01223567AB", // This is the meter number
            "Msg"=> 1, // The message type of for regular meter reading
            "S"=>"2243575687", // POSIX Timestamp in seconds of the request message
            "CT"=>"2243575687", // POSIX Timestamp in seconds of the capture time request message
            "Bal"=>"113.85", // Balance in Watt-hour
            "WH"=>"343.85", // Consumed energy in the previous hour
            "H"=>"13" // The period during which WH above was consumed (i.e. 12:00 - 13:00)
        ];
        $params = json_encode($params);
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'POST',
            '/api/collector',
            ['Password'=> $params]
        );

        \Log::info($response->json());
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'MN' => '01223567AB'
            ]);
    }

    public function testDailyUsage()
    {
        $params = [
            "MN"=>"01223567AB", // This is the meter number
            "Msg"=> 1, // The message type of for regular meter reading
            "S"=>"2243575687", // POSIX Timestamp in seconds of the request message
            "CT"=>"2243575687", // POSIX Timestamp in seconds of the capture time request message
            "Bal"=>"113.85", // Balance in Watt-hour
            "WH"=>[33.2234,152.1048,3.2464,84.8493,1.3442,0.3902,0.0000,0.0000,0.11,14.0023,77.0236,49.3349, 33.2234,152.1048,0.000,0.0000,0.0000,0.000,0.0000,0.0000,0.11,14.0023,77.0236,49.3349], // Consumed energy in the previous hour
            "Vac"=>"224.87",
            "A"=>"2.1031",
            "P"=>"425.6317",
            "Hz"=> "60",
            "temp"=>"242",
            "TOU"=> "31.00",
        ];
        $params = json_encode($params);
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'POST',
            '/api/collector',
            ['Password'=> $params]
        );

        \Log::info($response->json());
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'MN' => '01223567AB'
            ]);
    }

    public function testServerQuery()
    {
        $params = [
            "MN"=>"01223567AB", // This is the meter number
            "Msg"=> 2, // The message type of for regular meter reading
            "S"=>"2243575687", // POSIX Timestamp in seconds of the request message
            "CT"=>"2243575687", // POSIX Timestamp in seconds of the capture time request message
        ];
        $params = json_encode($params);
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'POST',
            '/api/collector',
            ['Password'=> $params]
        );

        \Log::info($response->json());
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'MN' => '01223567AB'
            ]);
    }

}
