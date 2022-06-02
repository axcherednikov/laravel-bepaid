<?php

namespace Excent\BePaidLaravel\Tests\Feature\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Excent\BePaidLaravel\Contracts\BePaidSubscriber;
use Excent\BePaidLaravel\Tests\TestCase;

class BePaidControllerTest extends TestCase
{
    /** @var array */
    private $config;
    private $basicAuth;

    public function testNotificationPayment()
    {
        $response = $this->postJson(route($this->config['urls']['notifications']['name']), [
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
        ], $this->basicAuth);

        $this->assertTrue($response->isOk());
        Event::assertDispatched(BePaidSubscriber::EVENT_NOTIFICATION_SUCCESS);
        Event::assertNotDispatched(BePaidSubscriber::EVENT_NOTIFICATION_FAIL);
    }

    public function testNotificationTrialSubscribe()
    {
        $response = $this->postJson(route($this->config['urls']['notifications']['name']), [
            "card" => [
                "token" => "5559786942408b77017a3aac8390d46d77d181e34554df527a71919a856d0f28"
            ],
            "created_at" => "2015-01-27T15:54:32.629Z",
            "customer" => [
                "id" => "cst_25cd5ed1b6f93cfb"
            ],
            "id" => "sbs_f4117438947a554e",
            "plan" => [
                "currency" => "USD",
                "id" => "pln_341fb00a159bbfdd",
                "plan" => [
                    "amount" => 20,
                    "interval" => 20,
                    "interval_unit" => "day"
                ],
                "title" => "Basic plan",
                "trial" => [
                    "amount" => 10,
                    "interval" => 10,
                    "interval_unit" => "hour"
                ]
            ],
            "renew_at" => "2015-01-28T01:54:32.684Z",
            "state" => "trial",
            "tracking_id" => "my_tracking_id",
            "transaction" => [
                "created_at" => "2015-01-12T09:04:59.000Z",
                "message" => "Successfully processed",
                "status" => "successful",
                "uid" => "4107-310b0da80b"
            ]
        ], $this->basicAuth);

        $this->assertTrue($response->isOk());
        Event::assertDispatched(BePaidSubscriber::EVENT_NOTIFICATION_SUCCESS);
        Event::assertNotDispatched(BePaidSubscriber::EVENT_NOTIFICATION_FAIL);
    }

    public function testNotificationActiveSubscribe()
    {
        $response = $this->postJson(route($this->config['urls']['notifications']['name']), [
            "card" => [
                "token" => "2ed0b389f63c9198160bd7b8e98f6b42eb4c56e3b659a8070248b28cd3376d9d"
            ],
            "created_at" => "2015-06-18T12:02:42.521Z",
            "customer" => [
                "id" => "cst_ae00d2582d001228"
            ],
            "device_id" => "any device_id",
            "id" => "sbs_f140af88af4aaf88",
            "last_transaction" => [
                "created_at" => "2015-01-12T09:04:59.000Z",
                "message" => "Successfully processed",
                "status" => "successful",
                "uid" => "4107-310b0da80b"
            ],
            "plan" => [
                "currency" => "USD",
                "id" => "pln_05e0756ed24eec5c",
                "plan" => [
                    "amount" => 20,
                    "interval" => 7,
                    "interval_unit" => "day"
                ],
                "title" => "Title 1",
                "trial" => [
                    "amount" => 10,
                    "interval" => 40,
                    "interval_unit" => "hour"
                ]
            ],
            "renew_at" => "2015-06-24T12:02:42.499Z",
            "state" => "active",
            "tracking_id" => "any tracking_id"
        ], $this->basicAuth);

        $this->assertTrue($response->isOk());
        Event::assertDispatched(BePaidSubscriber::EVENT_NOTIFICATION_SUCCESS);
        Event::assertNotDispatched(BePaidSubscriber::EVENT_NOTIFICATION_FAIL);
    }

    public function testNotificationCancelledSubscribe()
    {
        $response = $this->postJson(route($this->config['urls']['notifications']['name']), [
            "card" => [
                "token" => "9990edb8e6f2af5d93a6259b690c50a7410bf9f97235f2e051345e01b580f699"
            ],
            "created_at" => "2015-06-18T12:02:42.731Z",
            "customer" => [
                "id" => "cst_2a46e8b7ff87df2d"
            ],
            "device_id" => "any device_id",
            "id" => "sbs_1cc338f74bc9bfb7",
            "last_transaction" => null,
            "plan" => [
                "currency" => "USD",
                "id" => "pln_0b4ba2f1ab0c1988",
                "plan" => [
                    "amount" => 20,
                    "interval" => 7,
                    "interval_unit" => "day"
                ],
                "title" => "Title 1",
                "trial" => [
                    "amount" => 10,
                    "interval" => 40,
                    "interval_unit" => "hour"
                ]
            ],
            "renew_at" => null,
            "state" => "canceled",
            "tracking_id" => "any tracking_id"
        ], $this->basicAuth);

        $this->assertTrue($response->isOk());
        Event::assertDispatched(BePaidSubscriber::EVENT_NOTIFICATION_SUCCESS);
        Event::assertNotDispatched(BePaidSubscriber::EVENT_NOTIFICATION_FAIL);
    }

    public function testNotificationTokenExpired()
    {
        $response = $this->postJson(route($this->config['urls']['notifications']['name']), [
            "token" => "311300d08dc7f22ae37272fac6513921d4c99ca24dcaccf4392a2606fe8f1877",
            "shop_id" => 1,
            "transaction_type" => "payment",
            "gateway_response" => null,
            "order" => [
                "currency" => "BYN",
                "amount" => 4299,
                "description" => "Order description",
                "tracking_id" => null,
                "additional_data" => [
                ],
                "expired_at" => "2017-06-01T13:01:06.123Z"
            ],
            "settings" => [
                "success_url" => "http://127.0.0.1:4567/success",
                "fail_url" => "http://127.0.0.1:4567/fail",
                "decline_url" => "http://127.0.0.1:4567/decline",
                "notification_url" => "http://your_shop.com/notification",
                "cancel_url" => "http://127.0.0.1:4567/cancel",
                "language" => "en",
                "customer_fields" => [
                    "hidden" => [
                        "phone",
                        "address"
                    ],
                    "read_only" => [
                        "email"
                    ]
                ]
            ],
            "customer" => [
                "first_name" => null,
                "last_name" => null,
                "address" => null,
                "city" => null,
                "country" => null,
                "state" => null,
                "phone" => null,
                "zip" => null,
                "email" => "jake@example.com"
            ],
            "finished" => false,
            "expired" => true,
            "shop" => [
                "name" => "Shop",
                "url" => "http://127.0.0.1:3009",
                "contact_email" => "qwfpg@gmail.com",
                "contact_phone" => "123456789",
                "brands" => [
                    "visa",
                    "master",
                    "maestro",
                    "belkart",
                    "erip"
                ]
            ],
            "test" => false,
            "status" => "error",
            "message" => "Token is expired.",
            "payment_method" => [
                "id" => 9,
                "checkout_data_id" => 9,
                "types" => [
                    "erip"
                ],
                "data" => [
                    "erip" => [
                        "order_id" => "order_id",
                        "account_number" => "123",
                        "service_no" => "99999999"
                    ]
                ],
                "created_at" => "2017-06-01T13:00:14.506Z",
                "updated_at" => "2017-06-01T13:00:14.506Z"
            ]
        ], $this->basicAuth);

        $this->assertTrue($response->isOk());
        Event::assertDispatched(BePaidSubscriber::EVENT_NOTIFICATION_SUCCESS);
        Event::assertNotDispatched(BePaidSubscriber::EVENT_NOTIFICATION_FAIL);
    }

    public function testSuccess()
    {
        $response = $this->get(route($this->config['urls']['success']['name']));

        $this->assertTrue($response->isOk());
        Event::assertDispatched(BePaidSubscriber::EVENT_SUCCESS_URL);
    }

    public function testFail()
    {
        $response = $this->get(route($this->config['urls']['fail']['name']));

        $this->assertTrue($response->isOk());
        Event::assertDispatched(BePaidSubscriber::EVENT_FAIL_URL);
    }

    public function testCancel()
    {
        $response = $this->get(route($this->config['urls']['cancel']['name']));

        $this->assertTrue($response->isOk());
        Event::assertDispatched(BePaidSubscriber::EVENT_CANCEL_URL);
    }

    public function testReturn()
    {
        $response = $this->get(route($this->config['urls']['return']['name']));

        $this->assertTrue($response->isOk());
        Event::assertDispatched(BePaidSubscriber::EVENT_RETURN_URL);
    }

    public function testDecline()
    {
        $response = $this->get(route($this->config['urls']['decline']['name']));

        $this->assertTrue($response->isOk());
        Event::assertDispatched(BePaidSubscriber::EVENT_DECLINE_URL);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = $this->app['config']->get('bepaid');

        $this->basicAuth = [
            'PHP_AUTH_USER' => $this->config['shop_id'],
            'PHP_AUTH_PW' => $this->config['shop_key'],
        ];

        Event::fake();
    }
}
