<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Tests\Unit;

use BeGateway\GatewayTransport;
use Excent\BePaidLaravel\Dtos\PaymentDto;
use Excent\BePaidLaravel\Payment;
use Excent\BePaidLaravel\Tests\TestCase;
use Illuminate\Routing\UrlGenerator;
use Mockery;

class PaymentTest extends TestCase
{
    private Payment $payment;

    private array $data = [
        'money' => [
            'amount' => 333.33,
        ],
        'description' => 'Test desc',
        'tracking_id' => 'test_tracking_id_1234',
        'card' => [
            'card_number' => '4200000000000000',
            'card_holder' => 'JOHN DOE',
            'card_exp_month' => 1,
            'card_exp_year' => 2030,
            'card_cvc' => '123',
        ],
        'customer' => [
            'ip' => '127.0.0.1',
            'email' => 'test@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address' => '4876 Gallegos Vista Apt. 382\nLake Christine, VA 92929',
            'city' => 'Minsk',
            'country' => 'BY',
            'state' => 'Minsk',
            'zip' => '220100',
            'phone' => '+375291234567',
            'birth_date' => '1970-01-01',
        ],
        'additional_data' => [
            'receipt' => ['Some text'],
        ],
    ];

    public function testLoadedClass()
    {
        $config = $this->app['config']->get('bepaid');

        /** @var UrlGenerator $router */
        $router = $this->app['url'];

        $this->assertEquals($config['test_mode'], $this->payment->operation->getTestMode());
        $this->assertEquals($config['currency'], $this->payment->operation->money->getCurrency());
        $this->assertEquals($config['lang'], $this->payment->operation->getLanguage());
        $this->assertEquals($router->route($config['urls']['notifications']['name'], [], true), $this->payment->operation->getNotificationUrl());
    }

    public function testFill()
    {
        $paymentDto = new PaymentDto($this->data);

        $result = $this->payment->fill($paymentDto);

        $this->assertEquals($this->data['description'], $result->operation->getDescription());
        $this->assertEquals($this->data['tracking_id'], $result->operation->getTrackingId());
        $this->assertEquals($this->data['money']['amount'], $result->operation->money->getAmount());
        $this->assertEquals($this->data['additional_data']['receipt'], $result->operation->additional_data->getReceipt());
        $this->assertSameSize($this->data['customer'], (array)$result->operation->customer);

        foreach ($result->operation->customer as $key => $value) {
            $this->assertEquals($this->data['customer'][$key], $value);
        }

        foreach ($result->operation->card as $key => $value) {
            $this->assertEquals($this->data['card'][$key], $value);
        }
    }

    public function testSubmit()
    {
        $paymentDto = new PaymentDto($this->data);

        $response = $this->payment->submit($paymentDto);
        $transaction = $response->getResponse()->transaction;

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isError());

        $this->assertEquals($this->data['tracking_id'], $transaction->tracking_id);
        $this->assertEquals($this->data['description'], $transaction->description);
        $this->assertEquals($this->data['additional_data']['receipt'], $transaction->additional_data->receipt_text);

        foreach ($transaction->customer as $key => $value) {
            if (isset($this->data['customer'][$key])) {
                $this->assertEquals($this->data['customer'][$key], $transaction->customer->{$key});
            }
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->payment = $this->app->get('bepaid.payment');

        Mockery::mock('alias:' . GatewayTransport::class, [
            'submit' => '{
              "transaction":{
                "uid":"37746555-bbdad687b0",
                "status":"successful",
                "amount":33333,
                "currency":"BYN",
                "description":"Test desc",
                "type":"payment",
                "payment_method_type":"credit_card",
                "tracking_id":"test_tracking_id_1234",
                "message":"Successfully processed",
                "test":true,
                "created_at":"2020-07-16T20:50:41.549Z",
                "updated_at":"2020-07-16T20:50:47.769Z",
                "paid_at":"2020-07-16T20:50:47.723+00:00",
                "expired_at":null,
                "closed_at":null,
                "settled_at":null,
                "language":"ru",
                "redirect_url":"https:\/\/demo-gateway.begateway.com\/process\/37746555-bbdad687b0",
                "credit_card":{
                  "holder":"JOHN DOE",
                  "stamp":"b3839d334ba40e89168d60cd9f9d1390aee3fe67dd4d5c41adbf3998043eaef8",
                  "brand":"visa",
                  "last_4":"0000",
                  "first_1":"4",
                  "bin":"420000",
                  "issuer_country":"US",
                  "issuer_name":"VISA Demo Bank",
                  "product":"F",
                  "exp_month":1,
                  "exp_year":2030,
                  "token_provider":null,
                  "token":"fcbc2be9-9ade-4915-abca-38ffed577abd"
                },
                "id":"37746555-bbdad687b0",
                "additional_data":{
                  "receipt_text":[
                    "Some text"
                  ],
                  "contract":[
            
                  ],
                  "meta":[
            
                  ]
                },
                "be_protected_verification":{
                  "status":"successful",
                  "message":null,
                  "white_black_list":{
                    "email":"absent",
                    "ip":"absent",
                    "card_number":"white"
                  }
                },
                "payment":{
                  "auth_code":"654321",
                  "bank_code":"05",
                  "rrn":"999",
                  "ref_id":"777888",
                  "message":"Payment was approved",
                  "amount":33333,
                  "currency":"BYN",
                  "billing_descriptor":"test descriptor",
                  "gateway_id":477,
                  "status":"successful"
                },
                "avs_cvc_verification":{
                  "avs_verification":{
                    "result_code":"1"
                  },
                  "cvc_verification":{
                    "result_code":"1"
                  }
                },
                "customer":{
                  "ip":"127.0.0.1",
                  "email":"test@example.com",
                  "device_id":null,
                  "birth_date":"1970-01-01"
                },
                "billing_address":{
                  "first_name":"John",
                  "last_name":"Doe",
                  "address":"4876 Gallegos Vista Apt. 382\\nLake Christine, VA 92929",
                  "country":"BY",
                  "city":"Minsk",
                  "zip":"220100",
                  "state":null,
                  "phone":"+375291234567"
                }
              }
            }',
        ])->makePartial();
    }
}
