<?php

namespace Excent\BePaidLaravel;

use BeGateway\{AdditionalData,
    AuthorizationOperation,
    Customer,
    GetPaymentToken,
    Money,
    PaymentOperation,
    RefundOperation,
    ResponseBase,
    ResponseCheckout};
use Illuminate\Support\Str;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\PaymentTokenDto;

class PaymentToken extends GatewayAbstract
{
    public GetPaymentToken $operation;

    public function __construct(GetPaymentToken $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param  PaymentTokenDto  $data
     *
     * @return ResponseCheckout
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param  PaymentTokenDto                                     $data
     * @param  null|Money|AdditionalData|Customer|GetPaymentToken  $object
     *
     * @return IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        if ($data instanceof PaymentTokenDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid()->toString();
        }

        return parent::fill($data, $object);
    }
}
