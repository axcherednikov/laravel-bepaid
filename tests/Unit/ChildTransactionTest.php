<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Tests\Unit;

use Excent\BePaidLaravel\{
    Authorization,
    ChildTransaction,
    Dtos\AuthorizationDto,
    Dtos\CaptureDto,
    Dtos\VoidDto
};
use BeGateway\{CaptureOperation, GatewayTransport, VoidOperation};
use Excent\BePaidLaravel\Tests\TestCase;

class ChildTransactionTest extends TestCase
{
    private ChildTransaction $childTransaction;

    private Authorization $authorization;

    private array $data = [
        'money' => [
            'amount' => 222.22,
        ],
        'parent_uid' => 'test_parent_uid_12345',
    ];

    private array $authorizationData = [
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

    public function testFillCapture()
    {
        $captureDto = new CaptureDto($this->data);

        $result = $this->childTransaction->fill($captureDto);

        $this->assertNotNull($result->operation);
        $this->assertInstanceOf(CaptureOperation::class, $result->operation);
    }

    public function testFillVoid()
    {
        $voidDto = new VoidDto($this->data);

        $result = $this->childTransaction->fill($voidDto);

        $this->assertNotNull($result->operation);
        $this->assertInstanceOf(VoidOperation::class, $result->operation);
    }

    public function testSubmitCapture()
    {
        \Mockery::mock('alias:' . GatewayTransport::class)
            ->makePartial()
            ->shouldReceive('submit')
            ->twice()
            ->andReturn(
                '{"transaction":{"uid":"38697024-5dec707304","status":"successful","amount":33333,"currency":"BYN","description":"Test desc","type":"authorization","payment_method_type":"credit_card","tracking_id":"test_tracking_id_1234","message":"Successfully processed","test":true,"created_at":"2020-07-23T21:31:30.981Z","updated_at":"2020-07-23T21:31:34.183Z","paid_at":"2020-07-23T21:31:34.164+00:00","expired_at":null,"closed_at":null,"settled_at":null,"language":"ru","redirect_url":"https:\/\/demo-gateway.begateway.com\/process\/38697024-5dec707304","credit_card":{"holder":"JOHN DOE","stamp":"b3839d334ba40e89168d60cd9f9d1390aee3fe67dd4d5c41adbf3998043eaef8","brand":"visa","last_4":"0000","first_1":"4","bin":"420000","issuer_country":"US","issuer_name":"VISA Demo Bank","product":"F","exp_month":1,"exp_year":2030,"token_provider":null,"token":"b29537a4-61ed-4c8a-ab23-9b7e6db3e385"},"id":"38697024-5dec707304","additional_data":{"receipt_text":["Some text"],"contract":[],"meta":[]},"be_protected_verification":{"status":"successful","message":null,"white_black_list":{"email":"absent","ip":"absent","card_number":"white"}},"authorization":{"auth_code":"654321","bank_code":"05","rrn":"999","ref_id":"777888","message":"Authorization was approved","amount":33333,"currency":"BYN","billing_descriptor":"test descriptor","gateway_id":477,"status":"successful"},"avs_cvc_verification":{"avs_verification":{"result_code":"1"},"cvc_verification":{"result_code":"1"}},"customer":{"ip":"127.0.0.1","email":"test@example.com","device_id":null,"birth_date":"1970-01-01"},"billing_address":{"first_name":"John","last_name":"Doe","address":"4876 Gallegos Vista Apt. 382\\nLake Christine, VA 92929","country":"BY","city":"Minsk","zip":"220100","state":null,"phone":"+375291234567"}}}',
                '{"transaction":{"uid":"38697166-d2f198d0fd","status":"successful","amount":22222,"currency":"BYN","type":"capture","message":"Successfully processed","test":true,"created_at":"2020-07-23T21:33:09.262Z","updated_at":"2020-07-23T21:33:12.345Z","paid_at":"2020-07-23T21:33:12.333+00:00","closed_at":null,"settled_at":null,"parent_uid":"38697024-5dec707304","id":"38697166-d2f198d0fd","be_protected_verification":{"status":"successful","message":null,"white_black_list":{"email":"absent","ip":"absent","card_number":"white"}},"capture":{"message":"Capture was approved","ref_id":"8889912","rrn":null,"auth_code":null,"gateway_id":477,"status":"successful"}}}'
            );

        $authorizationDto = new AuthorizationDto($this->authorizationData);

        $authorizationResponse = $this->authorization->submit($authorizationDto);

        $this->data['parent_uid'] = $authorizationResponse->getUid();
        $captureDto = new CaptureDto($this->data);

        $response = $this->childTransaction->submit($captureDto);

        $this->assertTrue($response->isSuccess());
        $this->assertTrue($response->isValid());

        $transaction = $response->getResponse()->transaction;

        $this->assertNotEmpty($transaction->capture);
        $this->assertStringContainsString('Capture was approved', $transaction->capture->message);
    }

    public function testSubmitVoid()
    {
        \Mockery::mock('alias:' . GatewayTransport::class)
            ->makePartial()
            ->shouldReceive('submit')
            ->twice()
            ->andReturn(
                '{"transaction":{"uid":"38698066-22838a613a","status":"successful","amount":33333,"currency":"BYN","description":"Test desc","type":"authorization","payment_method_type":"credit_card","tracking_id":"test_tracking_id_1234","message":"Successfully processed","test":true,"created_at":"2020-07-23T21:42:36.985Z","updated_at":"2020-07-23T21:42:44.123Z","paid_at":"2020-07-23T21:42:44.105+00:00","expired_at":null,"closed_at":null,"settled_at":null,"language":"ru","redirect_url":"https:\/\/demo-gateway.begateway.com\/process\/38698066-22838a613a","credit_card":{"holder":"JOHN DOE","stamp":"b3839d334ba40e89168d60cd9f9d1390aee3fe67dd4d5c41adbf3998043eaef8","brand":"visa","last_4":"0000","first_1":"4","bin":"420000","issuer_country":"US","issuer_name":"VISA Demo Bank","product":"F","exp_month":1,"exp_year":2030,"token_provider":null,"token":"c6e0c2ad-c82a-4877-8868-bf07a45c1572"},"id":"38698066-22838a613a","additional_data":{"receipt_text":["Some text"],"contract":[],"meta":[]},"be_protected_verification":{"status":"successful","message":null,"white_black_list":{"email":"absent","ip":"absent","card_number":"white"}},"authorization":{"auth_code":"654321","bank_code":"05","rrn":"999","ref_id":"777888","message":"Authorization was approved","amount":33333,"currency":"BYN","billing_descriptor":"test descriptor","gateway_id":477,"status":"successful"},"avs_cvc_verification":{"avs_verification":{"result_code":"1"},"cvc_verification":{"result_code":"1"}},"customer":{"ip":"127.0.0.1","email":"test@example.com","device_id":null,"birth_date":"1970-01-01"},"billing_address":{"first_name":"John","last_name":"Doe","address":"4876 Gallegos Vista Apt. 382\\nLake Christine, VA 92929","country":"BY","city":"Minsk","zip":"220100","state":null,"phone":"+375291234567"}}}',
                '{"transaction":{"uid":"38698179-3e0c051257","status":"successful","amount":22222,"currency":"BYN","type":"void","message":"Successfully processed","test":true,"created_at":"2020-07-23T21:43:48.735Z","updated_at":"2020-07-23T21:43:51.841Z","paid_at":"2020-07-23T21:43:51.829+00:00","parent_uid":"38698066-22838a613a","id":"38698179-3e0c051257","be_protected_verification":{"status":"successful","message":null,"rules":{"1_368_beGateway Test Shop":{},"1_Demo Test":{"1":{"Authorization transaction count more or equal than 4 per IP address in 2 hours":"passed"}},"Demo PSP":{"abc":{"BIN is in Blocked_BINs":"passed"}}}},"void":{"message":"Void was approved","ref_id":"8889913","rrn":null,"auth_code":null,"gateway_id":477,"status":"successful"}}}'
            );

        $authorizationDto = new AuthorizationDto($this->authorizationData);

        $authorizationResponse = $this->authorization->submit($authorizationDto);

        $this->data['parent_uid'] = $authorizationResponse->getUid();
        $captureDto = new VoidDto($this->data);

        $response = $this->childTransaction->submit($captureDto);

        $this->assertTrue($response->isSuccess());
        $this->assertTrue($response->isValid());

        $transaction = $response->getResponse()->transaction;

        $this->assertNotEmpty($transaction->void);
        $this->assertStringContainsString('Void was approved', $transaction->void->message);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->childTransaction = $this->app->get('bepaid.childTransaction');
        $this->authorization = $this->app->get('bepaid.authorization');
    }
}
