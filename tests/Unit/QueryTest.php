<?php

namespace Excent\BePaidLaravel\Tests\Unit;

use BeGateway\{GatewayTransport, QueryByPaymentToken, QueryByTrackingId, QueryByUid, Response, ResponseCheckout};
use Excent\BePaidLaravel\Dtos\{QueryByPaymentTokenDto, QueryByTrackingIdDto, QueryByUidDto};
use Excent\BePaidLaravel\Tests\TestCase;

class QueryTest extends TestCase
{
    /** @var \Excent\BePaidLaravel\Query */
    private $query;
    /** @var \Excent\BePaidLaravel\Payment */
    private $payment;
    /** @var \Excent\BePaidLaravel\PaymentToken */
    private $paymentToken;

    public function testFillQueryByPaymentToken()
    {
        $token = 'test_token_12345';

        $dto = new QueryByPaymentTokenDto(compact('token'));

        $result = $this->query->fill($dto);

        $this->assertNotNull($result->operation);
        $this->assertInstanceOf(QueryByPaymentToken::class, $result->operation);

        $this->assertEquals($token, $result->operation->getToken());
    }

    public function testFillQueryByTrackingId()
    {
        $tracking_id = 'test_tracking_id_12345';

        $dto = new QueryByTrackingIdDto(compact('tracking_id'));

        $result = $this->query->fill($dto);

        $this->assertNotNull($result->operation);
        $this->assertInstanceOf(QueryByTrackingId::class, $result->operation);

        $this->assertEquals($tracking_id, $result->operation->getTrackingId());
    }

    public function testFillQueryByUid()
    {
        $uid = 'test_uid_12345';

        $dto = new QueryByUidDto(compact('uid'));

        $result = $this->query->fill($dto);

        $this->assertNotNull($result->operation);
        $this->assertInstanceOf(QueryByUid::class, $result->operation);

        $this->assertEquals($uid, $result->operation->getUid());
    }

    public function testSubmitQueryByUid()
    {
        \Mockery::mock('alias:' . GatewayTransport::class, [
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

        $paymentResponse = $this->payment->submit();
        $transaction = $paymentResponse->getResponse()->transaction;

        $dto = new QueryByUidDto((array)$transaction);

        $response = $this->query->submit($dto);
        $trx = $response->getResponse()->transaction;

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($transaction->uid, $trx->uid);
    }

    public function testSubmitQueryByTrackingId()
    {
        \Mockery::mock('alias:' . GatewayTransport::class, [
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

        $paymentResponse = $this->payment->submit();
        $transaction = $paymentResponse->getResponse()->transaction;

        $dto = new QueryByTrackingIdDto((array)$transaction);

        $response = $this->query->submit($dto);
        $trx = $response->getResponse()->transaction;

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($transaction->tracking_id, $trx->tracking_id);
    }

    public function testSubmitQueryByPaymentToken()
    {
        \Mockery::mock('alias:' . GatewayTransport::class, [
            'submit' => '{
                      "checkout":{
                        "token":"2d579c5625da92b088f12d41c0c7548472e7a5f4477c4d579ca8976a53ecf6d2",
                        "redirect_url":"https:\/\/checkout.begateway.com\/v2\/checkout?token=2d579c5625da92b088f12d41c0c7548472e7a5f4477c4d579ca8976a53ecf6d2"
                      }
                    }',
        ])->makePartial();

        $paymentResponse = $this->paymentToken->submit();
        $checkout = $paymentResponse->getResponse()->checkout;

        $dto = new QueryByPaymentTokenDto((array)$checkout);

        $result = $this->query->submit($dto);
        $queryCheckout = $result->getResponse()->checkout;

        $this->assertInstanceOf(ResponseCheckout::class, $result);
        $this->assertNotNull($queryCheckout);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->query = $this->app->get('bepaid.query');
        $this->paymentToken = $this->app->get('bepaid.paymentToken');
        $this->payment = $this->app->get('bepaid.payment');
    }
}
