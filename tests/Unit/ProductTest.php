<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Tests\Unit;

use BeGateway\GatewayTransport;
use Excent\BePaidLaravel\Dtos\ProductDto;
use Excent\BePaidLaravel\Product;
use Excent\BePaidLaravel\Tests\TestCase;

class ProductTest extends TestCase
{
    private Product $product;

    private array $data = [
        'name' => 'test',
        'description' => 'test description',
        'quantity' => 5,
        'infinite_state' => false,
        'immortal_state' => false,
        'transaction_type' => 'payment',
        'money' => [
            'amount' => 111.12,
        ],
        'additional_data' => [
            'receipt' => 'Some dummy text',
        ],
        'expire_at' => '2020-07-15T17:17:58+0000',
        'visible' => [
            'phone',
            'first_name',
            'last_name',
            'email',
        ],
    ];

    public function testLoadedClass()
    {
        $config = $this->app['config']->get('bepaid');

        /** @var \Illuminate\Routing\UrlGenerator $router */
        $router = $this->app['url'];

        $this->assertEquals($config['test_mode'], $this->product->operation->getTestMode());
        $this->assertEquals($config['currency'], $this->product->operation->money->getCurrency());
        $this->assertEquals($config['lang'], $this->product->operation->getLanguage());
        $this->assertEquals($router->route($config['urls']['notifications']['name'], [], true), $this->product->operation->getNotificationUrl());
        $this->assertEquals($router->route($config['urls']['success']['name'], [], true), $this->product->operation->getSuccessUrl());
        $this->assertEquals($router->route($config['urls']['fail']['name'], [], true), $this->product->operation->getFailUrl());
        $this->assertEquals($router->route($config['urls']['return']['name'], [], true), $this->product->operation->getReturnUrl());
        $this->assertNotNull($this->product->operation->getExpiryDate());
    }

    public function testFill()
    {
        $productDto = new ProductDto($this->data);

        /** @var Product $result */
        $result = $this->product->fill($productDto);

        $this->assertEquals($this->data['name'], $result->operation->getName());
        $this->assertEquals($this->data['description'], $result->operation->getDescription());
        $this->assertEquals($this->data['quantity'], $result->operation->getQuantity());
        $this->assertEquals($this->data['infinite_state'], $result->operation->getInfiniteState());
        $this->assertEquals($this->data['immortal_state'], $result->operation->getImmortalState());
        $this->assertEquals($this->data['transaction_type'], $result->operation->getTransactionType());
        $this->assertEquals($this->data['money']['amount'], $result->operation->money->getAmount());
        $this->assertEquals($this->data['additional_data']['receipt'], $result->operation->additional_data->getReceipt());
        $visible = $result->operation->getVisibleFields();
        $this->assertEquals(sort($this->data['visible']), sort($visible));
    }

    public function testSubmitWithData()
    {
        $productDto = new ProductDto($this->data);

        /** @var \BeGateway\ResponseApiProduct $response */
        $response = $this->product->submit($productDto);

        /** @var object $result */
        $result = $response->getResponse();

        $this->assertEquals($this->data['name'], $this->product->operation->getName());
        $this->assertEquals($this->data['description'], $this->product->operation->getDescription());
        $this->assertEquals($this->data['quantity'], $this->product->operation->getQuantity());
        $this->assertEquals($this->data['infinite_state'], $this->product->operation->getInfiniteState());
        $this->assertEquals($this->data['immortal_state'], $this->product->operation->getImmortalState());
        $this->assertEquals($this->data['transaction_type'], $this->product->operation->getTransactionType());
        $this->assertEquals($this->data['money']['amount'], $this->product->operation->money->getAmount());
        $this->assertEquals($this->data['additional_data']['receipt'], $this->product->operation->additional_data->getReceipt());
        $visible = $this->product->operation->getVisibleFields();
        $this->assertEquals(sort($this->data['visible']), sort($visible));

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertNotNull($response->getId());

        $this->assertEquals($result->name, $this->product->operation->getName());
        $this->assertEquals($result->description, $this->product->operation->getDescription());
        $this->assertEquals($result->quantity, $this->product->operation->getQuantity());
        $this->assertEquals($result->infinite, $this->product->operation->getInfiniteState());
        $this->assertEquals($result->language, $this->product->operation->getLanguage());
        $this->assertEquals($result->transaction_type, $this->product->operation->getTransactionType());
        $this->assertEquals($result->test, $this->product->operation->getTestMode());
        $this->assertEquals($result->currency, $this->product->operation->money->getCurrency());
        $this->assertEquals($result->amount, $this->product->operation->money->getCents());
    }

    public function testSubmitWithoutData()
    {
        $productDto = new ProductDto($this->data);

        $this->product->fill($productDto);

        /** @var \BeGateway\ResponseApiProduct $response */
        $response = $this->product->submit();

        /** @var object $result */
        $result = $response->getResponse();

        $this->assertEquals($this->data['name'], $this->product->operation->getName());
        $this->assertEquals($this->data['description'], $this->product->operation->getDescription());
        $this->assertEquals($this->data['quantity'], $this->product->operation->getQuantity());
        $this->assertEquals($this->data['infinite_state'], $this->product->operation->getInfiniteState());
        $this->assertEquals($this->data['immortal_state'], $this->product->operation->getImmortalState());
        $this->assertEquals($this->data['transaction_type'], $this->product->operation->getTransactionType());
        $this->assertEquals($this->data['money']['amount'], $this->product->operation->money->getAmount());
        $this->assertEquals($this->data['additional_data']['receipt'], $this->product->operation->additional_data->getReceipt());

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertNotNull($response->getId());

        $this->assertEquals($result->name, $this->product->operation->getName());
        $this->assertEquals($result->description, $this->product->operation->getDescription());
        $this->assertEquals($result->quantity, $this->product->operation->getQuantity());
        $this->assertEquals($result->infinite, $this->product->operation->getInfiniteState());
        $this->assertEquals($result->language, $this->product->operation->getLanguage());
        $this->assertEquals($result->transaction_type, $this->product->operation->getTransactionType());
        $this->assertEquals($result->test, $this->product->operation->getTestMode());
        $this->assertEquals($result->currency, $this->product->operation->money->getCurrency());
        $this->assertEquals($result->amount, $this->product->operation->money->getCents());
    }

    protected function setUp(): void
    {
        parent::setUp();

        \Mockery::mock('alias:' . GatewayTransport::class, [
            'submit' => '{
              "id":"prd_770aa9c072595c80",
              "name":"test",
              "description":"test description",
              "currency":"BYN",
              "amount":11112,
              "quantity":5,
              "infinite":false,
              "language":"ru",
              "transaction_type":"payment",
              "created_at":"2020-07-14T09:18:15.032Z",
              "updated_at":"2020-07-14T09:18:15.032Z",
              "test":true,
              "additional_data":{
                "receipt_text":"Some dummy text",
                "contract":null,
                "meta":null
              }
            }',
        ])->makePartial();

        $this->product = $this->app->get('bepaid.product');
    }
}
