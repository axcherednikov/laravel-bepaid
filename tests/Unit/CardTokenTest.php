<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel\Tests\Unit;

use BeGateway\GatewayTransport;
use Excent\BePaidLaravel\Dtos\CardTokenDto;
use Excent\BePaidLaravel\Tests\TestCase;

class CardTokenTest extends TestCase
{
    /** @var \Excent\BePaidLaravel\CardToken */
    private $token;

    private $data = [
        'card' => [
            'card_number' => '4200000000000000',
            'card_holder' => 'JOHN DOE',
            'card_exp_month' => 1,
            'card_exp_year' => 2030,
            'card_cvc' => '123',
        ],
    ];

    public function testFill()
    {
        $cardTokenDto = new CardTokenDto($this->data);

        $result = $this->token->fill($cardTokenDto);

        $this->assertEquals($this->data['card']['card_number'], $result->operation->card->getCardNumber());
        $this->assertEquals($this->data['card']['card_holder'], $result->operation->card->getCardHolder());
        $this->assertEquals($this->data['card']['card_exp_year'], $result->operation->card->getCardExpYear());
        $this->assertEquals($this->data['card']['card_cvc'], $result->operation->card->getCardCvc());
        $month = sprintf('%02d', $this->data['card']['card_exp_month']);
        $this->assertEquals($month, $result->operation->card->getCardExpMonth());
    }

    public function testSubmit()
    {
        $cardTokenDto = new CardTokenDto($this->data);

        $result = $this->token->submit($cardTokenDto);
        $response = $result->getResponse();

        /** @var \BeGateway\Card $card */
        $card = $result->card;

        $this->assertTrue($result->isValid());
        $this->assertTrue($result->isSuccess());
        $this->assertFalse($result->isError());

        $this->assertEquals($this->data['card']['card_holder'], $response->holder);
        $this->assertEquals($this->data['card']['card_exp_month'], $response->exp_month);
        $this->assertEquals($this->data['card']['card_exp_year'], $response->exp_year);
        $this->assertNotNull($response->stamp);
        $this->assertNotNull($response->brand);
        $this->assertNotNull($response->token);

        $cardNumber = $this->data['card']['card_number'];
        $firstOne = $cardNumber[0];
        $lastFour = substr($cardNumber, -4);

        $this->assertEquals($this->data['card']['card_holder'], $card->getCardHolder());
        $this->assertEquals($this->data['card']['card_exp_month'], $card->getCardExpMonth());
        $this->assertEquals($this->data['card']['card_exp_year'], $card->getCardExpYear());
        $this->assertEquals($firstOne, $card->getFirst_1());
        $this->assertEquals($lastFour, $card->getLast_4());
        $this->assertNotNull($card->getBrand());
        $this->assertNotNull($card->getCardToken());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = $this->app->get('bepaid.cardToken');

        \Mockery::mock('alias:' . GatewayTransport::class, [
            'submit' => '{
              "holder":"JOHN DOE",
              "stamp":"b3839d334ba40e89168d60cd9f9d1390aee3fe67dd4d5c41adbf3998043eaef8",
              "brand":"visa",
              "last_4":"0000",
              "first_1":"4",
              "token":"54bf7b1c-ba2f-4d18-ba4d-156a0dae5c68",
              "product":"F",
              "bin":"420000",
              "issuer_country":"US",
              "issuer_name":"VISA Demo Bank",
              "exp_month":1,
              "exp_year":2030
            }',
        ])->makePartial();
    }
}
