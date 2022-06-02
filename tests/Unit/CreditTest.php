<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Tests\Unit;

use BeGateway\GatewayTransport;
use Excent\BePaidLaravel\{Credit, Dtos\PaymentDto, Payment};
use Excent\BePaidLaravel\Dtos\CreditDto;
use Excent\BePaidLaravel\Tests\TestCase;

class CreditTest extends TestCase
{
    private Credit $credit;

    private Payment $payment;

    private array $data = [
        'money' => [
            'amount' => 333.33,
        ],
        'description' => 'Test desc',
        'tracking_id' => 'test_credit_tracking_id_12345',
        'card' => [
            'card_number' => '4200000000000000',
            'card_holder' => 'JOHN DOE',
            'card_exp_month' => 1,
            'card_exp_year' => 2030,
            'card_cvc' => '123',
        ],
    ];

    private array $paymentData = [
        'money' => [
            'amount' => 333.33,
        ],
        'description' => 'Test desc',
        'tracking_id' => 'test_payment_tracking_id_1234',
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

    /**
     * @param  array  $paymentData
     * @return CreditTest
     */
    public function setPaymentData(array $paymentData): CreditTest
    {
        $this->paymentData = $paymentData;
        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->credit = $this->app->get('bepaid.credit');
        $this->payment = $this->app->get('bepaid.payment');
    }

    public function testFill()
    {
        $creditDto = new CreditDto($this->data);

        $result = $this->credit->fill($creditDto);

        $this->assertEquals($this->data['money']['amount'], $result->operation->money->getAmount());
        $this->assertEquals($this->data['description'], $result->operation->getDescription());
        $this->assertEquals($this->data['tracking_id'], $result->operation->getTrackingId());

        foreach ($result->operation->card as $key => $value) {
            $this->assertEquals($this->data['card'][$key], $value);
        }
    }

    public function testSubmit()
    {
        \Mockery::mock('alias:' . GatewayTransport::class)
            ->makePartial()
            ->shouldReceive('submit')
            ->twice()
            ->andReturn(
                '{"transaction":{"uid":"38693219-7f96e9d708","status":"successful","amount":33333,"currency":"BYN","description":"Test desc","type":"payment","payment_method_type":"credit_card","tracking_id":"test_payment_tracking_id_1234","message":"Successfully processed","test":true,"created_at":"2020-07-23T20:51:26.224Z","updated_at":"2020-07-23T20:51:31.375Z","paid_at":"2020-07-23T20:51:31.351+00:00","expired_at":null,"closed_at":null,"settled_at":null,"language":"ru","redirect_url":"https:\/\/demo-gateway.begateway.com\/process\/38693219-7f96e9d708","credit_card":{"holder":"JOHN DOE","stamp":"b3839d334ba40e89168d60cd9f9d1390aee3fe67dd4d5c41adbf3998043eaef8","brand":"visa","last_4":"0000","first_1":"4","bin":"420000","issuer_country":"US","issuer_name":"VISA Demo Bank","product":"F","exp_month":1,"exp_year":2030,"token_provider":null,"token":"e98688fa-b0b8-4497-8744-d6570e30a875"},"id":"38693219-7f96e9d708","additional_data":{"receipt_text":["Some text"],"contract":[],"meta":[]},"be_protected_verification":{"status":"successful","message":null,"white_black_list":{"email":"absent","ip":"absent","card_number":"white"}},"payment":{"auth_code":"654321","bank_code":"05","rrn":"999","ref_id":"777888","message":"Payment was approved","amount":33333,"currency":"BYN","billing_descriptor":"test descriptor","gateway_id":477,"status":"successful"},"avs_cvc_verification":{"avs_verification":{"result_code":"1"},"cvc_verification":{"result_code":"1"}},"customer":{"ip":"127.0.0.1","email":"test@example.com","device_id":null,"birth_date":"1970-01-01"},"billing_address":{"first_name":"John","last_name":"Doe","address":"4876 Gallegos Vista Apt. 382\\nLake Christine, VA 92929","country":"BY","city":"Minsk","zip":"220100","state":null,"phone":"+375291234567"}}}',
                '{"transaction":{"uid":"38693368-cbce5e9b79","status":"successful","amount":33333,"currency":"BYN","description":"Test desc","type":"credit","payment_method_type":"credit_card","tracking_id":"test_credit_tracking_id_12345","message":"Successfully processed","test":true,"created_at":"2020-07-23T20:53:21.123Z","updated_at":"2020-07-23T20:53:28.214Z","paid_at":"2020-07-23T20:53:28.200+00:00","language":"en","credit_card":{"holder":"JOHN DOE","stamp":"b3839d334ba40e89168d60cd9f9d1390aee3fe67dd4d5c41adbf3998043eaef8","brand":"visa","last_4":"0000","first_1":"4","bin":"420000","issuer_country":"US","issuer_name":"VISA Demo Bank","product":"F","exp_month":1,"exp_year":2030,"token_provider":null,"token":"e98688fa-b0b8-4497-8744-d6570e30a875"},"id":"38693368-cbce5e9b79","be_protected_verification":{"status":"successful","message":null,"white_black_list":{"card_number":"white"}},"credit":{"auth_code":"654327","bank_code":"05","rrn":"934","ref_id":"777822","message":"Credit was approved","amount":33333,"currency":"BYN","billing_descriptor":"test descriptor","gateway_id":477,"status":"successful"}}}'
            );

        $paymentDto = new PaymentDto($this->paymentData);

        $paymentResponse = $this->payment->submit($paymentDto);
        $transaction = $paymentResponse->getResponse()->transaction;

        $this->data['card']['card_token'] = $transaction->credit_card->token;
        $creditDto = new CreditDto($this->data);

        $response = $this->credit->submit($creditDto);
        $trx = $response->getResponse()->transaction;

        $this->assertTrue($response->isSuccess());
        $this->assertTrue($response->isValid());

        $this->assertNotEmpty($trx->credit);
        $this->assertStringContainsString('Credit was approved', $trx->credit->message);
    }
}
