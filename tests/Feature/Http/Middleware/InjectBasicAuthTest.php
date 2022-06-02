<?php

namespace Excent\BePaidLaravel\Tests\Feature\Http\Middleware;

use Illuminate\Support\Facades\Event;
use Excent\BePaidLaravel\Tests\TestCase;

class InjectBasicAuthTest extends TestCase
{
    private $config;

    public function testHandleServerVars()
    {
        $this->postJson(route($this->config['urls']['notifications']['name']), [
            "transaction" => [
                "customer" => [
                    "ip" => "127.0.0.1",
                    "email" => "john@example.com"
                ],
                "credit_card" => [
                    "holder" => "John Doe",
                    "stamp" => "3709786942408b77017a3aac8390d46d77d181e34554df527a71919a856d0f28",
                    "token" => "d46d77d181e34554df527a71919a856d0f283709786942408b77017a3aac8390",
                    "brand" => "visa",
                    "last_4" => "0000",
                    "first_1" => "4",
                    "exp_month" => 5,
                    "exp_year" => 2015
                ],
                "billing_address" => [
                    "first_name" => "John",
                    "last_name" => "Doe",
                    "address" => "1st Street",
                    "country" => "US",
                    "city" => "Denver",
                    "zip" => "96002",
                    "state" => "CO",
                    "phone" => null
                ],
                "payment" => [
                    "auth_code" => "654321",
                    "bank_code" => "05",
                    "rrn" => "999",
                    "ref_id" => "777888",
                    "message" => "Payment was approved",
                    "gateway_id" => 317,
                    "billing_descriptor" => "TEST GATEWAY BILLING DESCRIPTOR",
                    "status" => "successful"
                ],
                "uid" => "1-310b0da80b",
                "status" => "successful",
                "message" => "Successfully processed",
                "amount" => 100,
                "test" => true,
                "currency" => "USD",
                "description" => "Test order",
                "tracking_id" => "my_tracking_id",
                "type" => "payment"
            ]
        ], [
            'PHP_AUTH_USER' => $this->config['shop_id'],
            'PHP_AUTH_PW' => $this->config['shop_key'],
        ]);

        $this->assertTrue(!empty($_SERVER['PHP_AUTH_USER']));
        $this->assertTrue(!empty($_SERVER['PHP_AUTH_PW']));
        $this->assertEquals((int)$this->config['shop_id'], $_SERVER['PHP_AUTH_USER']);
        $this->assertEquals($this->config['shop_key'], $_SERVER['PHP_AUTH_PW']);
    }

    public function testHandleBasicAuthHeader()
    {
        $auth = 'Basic MzYxOmI4NjQ3YjY4ODk4YjA4NGI4MzY0NzRlZDhkNjFmZmUxMTdjOWEwMTE2OGQ4NjdmMjQ5NTNiNzc2ZGRjYjEzNGQ=';

        $this->postJson(route($this->config['urls']['notifications']['name']), [
            "transaction" => [
                "customer" => [
                    "ip" => "127.0.0.1",
                    "email" => "john@example.com"
                ],
                "credit_card" => [
                    "holder" => "John Doe",
                    "stamp" => "3709786942408b77017a3aac8390d46d77d181e34554df527a71919a856d0f28",
                    "token" => "d46d77d181e34554df527a71919a856d0f283709786942408b77017a3aac8390",
                    "brand" => "visa",
                    "last_4" => "0000",
                    "first_1" => "4",
                    "exp_month" => 5,
                    "exp_year" => 2015
                ],
                "billing_address" => [
                    "first_name" => "John",
                    "last_name" => "Doe",
                    "address" => "1st Street",
                    "country" => "US",
                    "city" => "Denver",
                    "zip" => "96002",
                    "state" => "CO",
                    "phone" => null
                ],
                "payment" => [
                    "auth_code" => "654321",
                    "bank_code" => "05",
                    "rrn" => "999",
                    "ref_id" => "777888",
                    "message" => "Payment was approved",
                    "gateway_id" => 317,
                    "billing_descriptor" => "TEST GATEWAY BILLING DESCRIPTOR",
                    "status" => "successful"
                ],
                "uid" => "1-310b0da80b",
                "status" => "successful",
                "message" => "Successfully processed",
                "amount" => 100,
                "test" => true,
                "currency" => "USD",
                "description" => "Test order",
                "tracking_id" => "my_tracking_id",
                "type" => "payment"
            ]
        ], [
            'Authorization' => $auth,
        ]);

        $this->assertTrue(!empty($_SERVER['HTTP_AUTHORIZATION']));
        $this->assertEquals($auth, $_SERVER['HTTP_AUTHORIZATION']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = $this->app['config']->get('bepaid');

        Event::fake();
    }
}
