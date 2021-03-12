<?php
namespace Tests\meleton;

use Tests\TestCase;

class ConnectionTest  extends TestCase
{
    public function testErrorConnect()
    {
        $response = $this->get('/api/v1/rates');
        $response->assertStatus(403);
    }
    public function testSuccessConnect()
    {
        $response = $this->call(
            'GET',
            '/api/v1/rates',
            [],
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . env('AUTH_TOCKEN')]
        );
        $response->assertStatus(200);
    }

    public function testSuccessConvert()
    {
        $response = $this->call(
            'POST',
            '/api/v1/convert',
            [
                'currency_from' => 'USD',
                'currency_to' => 'BTC',
                'value' => 20
            ],
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . env('AUTH_TOCKEN')]
        );
        $response->assertStatus(200);
    }

    public function testErrConvertValid()
    {
        $response = $this->call(
            'POST',
            '/api/v1/convert',
            [
                'currency_from' => 'USD',
//                'currency_to' => 'BTC',
                'value' => 20
            ],
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . env('AUTH_TOCKEN')]
        );
        $response->assertStatus(403);
    }
    public function testErrConvertValid2()
    {
        $response = $this->call(
            'POST',
            '/api/v1/convert',
            [
                'currency_from' => 'USD1',
                'currency_to' => 'BTC',
                'value' => 20
            ],
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . env('AUTH_TOCKEN')]
        );
        $response->assertStatus(403);
    }
}
