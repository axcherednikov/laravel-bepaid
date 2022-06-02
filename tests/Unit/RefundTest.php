<?php

namespace Excent\BePaidLaravel\Tests\Unit;

use BeGateway\GatewayTransport;
use Excent\BePaidLaravel\Dtos\PaymentDto;
use Excent\BePaidLaravel\Dtos\RefundDto;
use Excent\BePaidLaravel\Tests\TestCase;

class RefundTest extends TestCase
{
    /** @var \Excent\BePaidLaravel\Refund */
    private $refund;
    private $data = [
        'money' => [
            'amount' => 333.33,
        ],
        'reason' => 'Customer request',
        'parent_uid' => 'test_parent_uid',
    ];
    /** @var \Excent\BePaidLaravel\Payment */
    private $payment;
    private $paymentData = [
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

    public function testFill()
    {
        $refundDto = new RefundDto($this->data);

        $result = $this->refund->fill($refundDto);

        $this->assertEquals($this->data['money']['amount'], $result->operation->money->getAmount());
        $this->assertEquals($this->data['reason'], $result->operation->getReason());
        $this->assertEquals($this->data['parent_uid'], $result->operation->getParentUid());
    }

    public function testSubmitWithData()
    {
        \Mockery::mock('alias:' . GatewayTransport::class)
            ->makePartial()
            ->shouldReceive('submit')
            ->twice()
            ->andReturn(
                '{"transaction":{"uid":"37728767-e10aa95f2b","status":"successful","amount":33333,"currency":"BYN","description":"Test desc","type":"payment","payment_method_type":"credit_card","tracking_id":"test_tracking_id_1234","message":"Successfully processed","test":true,"created_at":"2020-07-16T18:05:58.119Z","updated_at":"2020-07-16T18:06:04.435Z","paid_at":"2020-07-16T18:06:04.347+00:00","expired_at":null,"closed_at":null,"settled_at":null,"language":"ru","redirect_url":"https:\/\/demo-gateway.begateway.com\/process\/37728767-e10aa95f2b","credit_card":{"holder":"JOHN DOE","stamp":"b3839d334ba40e89168d60cd9f9d1390aee3fe67dd4d5c41adbf3998043eaef8","brand":"visa","last_4":"0000","first_1":"4","bin":"420000","issuer_country":"US","issuer_name":"VISA Demo Bank","product":"F","exp_month":1,"exp_year":2030,"token_provider":null,"token":"05196a13-d838-4ed5-a596-17527ff80384"},"id":"37728767-e10aa95f2b","additional_data":{"receipt_text":["Some text"],"contract":[],"meta":[]},"be_protected_verification":{"status":"successful","message":null,"white_black_list":{"email":"absent","ip":"absent","card_number":"white"}},"payment":{"auth_code":"654321","bank_code":"05","rrn":"999","ref_id":"777888","message":"Payment was approved","amount":33333,"currency":"BYN","billing_descriptor":"test descriptor","gateway_id":477,"status":"successful"},"avs_cvc_verification":{"avs_verification":{"result_code":"1"},"cvc_verification":{"result_code":"1"}},"customer":{"ip":"127.0.0.1","email":"test@example.com","device_id":null,"birth_date":"1970-01-01"},"billing_address":{"first_name":"John","last_name":"Doe","address":"4876 Gallegos Vista Apt. 382\\nLake Christine, VA 92929","country":"BY","city":"Minsk","zip":"220100","state":null,"phone":"+375291234567"}}}',
                '{"transaction":{"uid":"37728810-8774ed0d79","status":"successful","amount":33333,"currency":"BYN","reason":"Customer request","type":"refund","message":"Successfully processed","test":true,"created_at":"2020-07-16T18:06:14.788Z","updated_at":"2020-07-16T18:06:17.108Z","paid_at":"2020-07-16T18:06:17.095+00:00","closed_at":null,"settled_at":null,"parent_uid":"37728767-e10aa95f2b","id":"37728810-8774ed0d79","be_protected_verification":{"status":"successful","message":null,"rules":{"1_368_beGateway Test Shop":{},"1_Demo Test":{"1":{"Authorization transaction count more or equal than 4 per IP address in 2 hours":"passed"}},"Demo PSP":{"abc":{"BIN is in Blocked_BINs":"passed"}}}},"refund":{"message":"Refund was approved","ref_id":"8889999","rrn":null,"auth_code":null,"gateway_id":477,"status":"successful"}}}'
            );

        $refundDto = new RefundDto($this->data);
        $paymentDto = new PaymentDto($this->paymentData);

        $paymentResponse = $this->payment->submit($paymentDto);
        $refundDto->parent_uid = $paymentResponse->getUid();

        $result = $this->refund->submit($refundDto);
        $transaction = $result->getResponse()->transaction;

        $this->assertTrue($result->isSuccess());
        $this->assertTrue($result->isValid());

        $this->assertEquals($this->data['money']['amount'], ($transaction->amount / 100));
        $this->assertEquals($this->data['reason'], $transaction->reason);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->refund = $this->app->get('bepaid.refund');
        $this->payment = $this->app->get('bepaid.payment');
    }
}
