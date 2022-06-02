<?php

namespace Excent\BePaidLaravel\Contracts;

use BeGateway\{AuthorizationOperation, CardToken, GetPaymentToken, PaymentOperation, ResponseBase};
use Excent\BePaidLaravel\Dtos\{AuthorizationDto,
    CardTokenDto,
    CreditDto,
    PaymentDto,
    PaymentTokenDto,
    ProductDto,
    RefundDto};

interface IGateway
{
    /**
     * @param AuthorizationDto|CardTokenDto|PaymentDto|PaymentTokenDto|ProductDto|RefundDto|CreditDto $data
     *
     * @return \BeGateway\ResponseBase
     */
    public function submit($data = null): ResponseBase;

    /**
     * @param AuthorizationDto|CardTokenDto|PaymentDto|PaymentTokenDto|ProductDto|RefundDto    $data
     * @param null|AuthorizationOperation|CardToken|GetPaymentToken|PaymentOperation|RefundDto $object
     *
     * @return IGateway
     */
    public function fill($data, $object = null): self;
}
