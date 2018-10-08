<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CollectorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testTamperReport(){
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/api/collector', ['MN' => '01223567AB','Msg'=>'4','DT'=>'1539025265','T'=>'8','S'=>'1539025265']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'meter_no' => '01223567AB',
            ]);
    }
}
