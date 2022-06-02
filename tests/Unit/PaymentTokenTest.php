<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Tests\Unit;

use BeGateway\GatewayTransport;
use BeGateway\PaymentMethod\CreditCard;
use Excent\BePaidLaravel\Dtos\PaymentTokenDto;
use Excent\BePaidLaravel\PaymentToken;
use Excent\BePaidLaravel\Tests\TestCase;

class PaymentTokenTest extends TestCase
{
    private PaymentToken $paymentToken;

    private array $data = [];

    protected function setUp(): void
    {
        parent::setUp();

        \Mockery::mock('alias:' . GatewayTransport::class, [
            'submit' => '{
              "checkout":{
                "token":"2d579c5625da92b088f12d41c0c7548472e7a5f4477c4d579ca8976a53ecf6d2",
                "redirect_url":"https:\/\/checkout.begateway.com\/v2\/checkout?token=2d579c5625da92b088f12d41c0c7548472e7a5f4477c4d579ca8976a53ecf6d2"
              }
            }',
        ])->makePartial();

        $this->paymentToken = $this->app->get('bepaid.paymentToken');

        $this->data = [
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
            'money' => [
                'amount' => 222.22,
            ],
            'additional_data' => [
                'receipt' => ['Some text'],
            ],
            'readonly' => ['first_name', 'last_name'],
            'visible' => ['email'],
            'payment_methods' => [new CreditCard()],
            'description' => 'Dummy text',
            'tracking_id' => 'test_tracking_id_1234',
            'transaction_type' => 'payment',
            'expired_at' => date('c', strtotime('+1 days')),
            'attempts' => 4,
        ];
    }

    public function testLoadedClass()
    {
        $config = $this->app['config']->get('bepaid');

        /** @var \Illuminate\Routing\UrlGenerator $router */
        $router = $this->app['url'];

        $this->assertEquals($config['test_mode'], $this->paymentToken->operation->getTestMode());
        $this->assertEquals($config['currency'], $this->paymentToken->operation->money->getCurrency());
        $this->assertEquals($config['lang'], $this->paymentToken->operation->getLanguage());
        $this->assertEquals($router->route($config['urls']['notifications']['name'], [], true),
            $this->paymentToken->operation->getNotificationUrl());
        $this->assertEquals($router->route($config['urls']['success']['name'], [], true),
            $this->paymentToken->operation->getSuccessUrl());
        $this->assertEquals($router->route($config['urls']['fail']['name'], [], true),
            $this->paymentToken->operation->getFailUrl());
        $this->assertEquals($router->route($config['urls']['decline']['name'], [], true),
            $this->paymentToken->operation->getDeclineUrl());
        $this->assertEquals($router->route($config['urls']['cancel']['name'], [], true),
            $this->paymentToken->operation->getCancelUrl());
        $this->assertNotNull($this->paymentToken->operation->getExpiryDate());
        $this->assertEquals($config['attempts'], $this->paymentToken->operation->getAttempts());
        $this->assertEquals($config['visible'], $this->paymentToken->operation->getVisibleFields());
        $this->assertEquals($config['read_only'], $this->paymentToken->operation->getReadonlyFields());
    }

    public function testFill()
    {
        $paymentTokenDto = new PaymentTokenDto($this->data);

        /** @var PaymentToken $result */
        $result = $this->paymentToken->fill($paymentTokenDto);

        $this->assertEquals($this->data['description'], $result->operation->getDescription());
        $this->assertEquals($this->data['tracking_id'], $result->operation->getTrackingId());
        $this->assertEquals($this->data['transaction_type'], $result->operation->getTransactionType());
        $this->assertEquals($this->data['money']['amount'], $result->operation->money->getAmount());
        $this->assertEquals($this->data['additional_data']['receipt'],
            $result->operation->additional_data->getReceipt());
        $this->assertEquals($this->data['expired_at'], $result->operation->getExpiryDate());
        $this->assertEquals($this->data['attempts'], $result->operation->getAttempts());
        $visible = $result->operation->getVisibleFields();
        $this->assertEquals(sort($this->data['visible']), sort($visible));
        $readonly = $result->operation->getReadonlyFields();
        $this->assertEquals(sort($this->data['readonly']), sort($readonly));
        $this->assertSameSize($this->data['customer'], (array) $result->operation->customer);

        foreach ($result->operation->customer as $key => $value) {
            $this->assertEquals($this->data['customer'][$key], $value);
        }
    }

    public function testSubmitWithData()
    {
        $paymentTokenDto = new PaymentTokenDto($this->data);

        /** @var \BeGateway\ResponseCheckout $response */
        $response = $this->paymentToken->submit($paymentTokenDto);

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isError());
        $this->assertIsString($response->getRedirectUrl());
        $this->assertIsString($response->getToken());
    }

    public function testSubmitWithoutData()
    {
        $paymentTokenDto = new PaymentTokenDto($this->data);

        /** @var \BeGateway\ResponseCheckout $response */
        $response = $this->paymentToken->fill($paymentTokenDto)->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isError());
        $this->assertIsString($response->getRedirectUrl());
        $this->assertIsString($response->getToken());
    }
}
