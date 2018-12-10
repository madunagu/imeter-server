<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalibrationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCalibration()
    {

        $params = ['Msg' => '9','ID'=>'3298531944835','MT'=>'5','SIM'=>'+2348100594781','sys'=>'somesis','cal'=>'some cal','S'=>'1539025265'];
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
            ->assertJsonFragment(['Msg' => '9']);

    }
}
