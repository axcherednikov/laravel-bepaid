<?php

namespace Excent\BePaidLaravel;

use BeGateway\{
    AuthorizationOperation,
    CardToken,
    GetPaymentToken,
    PaymentOperation,
    RefundOperation,
    ResponseBase
};
use Illuminate\Support\Str;
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\{
    AuthorizationDto,
    CaptureDto,
    CardTokenDto,
    CreditDto,
    PaymentDto,
    PaymentTokenDto,
    ProductDto,
    QueryByPaymentTokenDto,
    QueryByTrackingIdDto,
    QueryByUidDto,
    RefundDto,
    VoidDto
};
use Excent\BePaidLaravel\Exceptions\BadRequestException;
use Excent\BePaidLaravel\Exceptions\TransactionException;

abstract class GatewayAbstract implements IGateway
{
    /**
     * @param AuthorizationDto|CardTokenDto|PaymentDto|PaymentTokenDto|ProductDto|RefundDto|QueryByPaymentTokenDto|QueryByTrackingIdDto|QueryByUidDto|CreditDto|VoidDto|CaptureDto $data
     *
     * @return \BeGateway\ResponseBase
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        if ($data) $this->fill($data);

        $responseBase = $this->operation->submit();
        $response = $responseBase->getResponse();

        if ($responseBase->isError()) {
            if (strpos($response->message, 'transaction can\'t be refunded')) {
                throw new TransactionException($response->message, $response->errors);
            } elseif (
                strpos($response->message, 'can\'t be blank') ||
                strpos($response->message, 'is invalid') ||
                strpos($response->message, 'is not a number') ||
                strpos($response->message, 'must be greater than 0')
            ) {
                throw new BadRequestException($response->message, $response->errors);
            } else {
                throw new \Exception($response->message);
            }
        }

        return $responseBase;
    }

    /**
     * @param AuthorizationDto|CardTokenDto|PaymentDto|PaymentTokenDto|ProductDto|RefundDto|QueryByPaymentTokenDto|QueryByTrackingIdDto|QueryByUidDto|CreditDto|VoidDto|CaptureDto $data
     * @param null                                                                                                                                                                 $object
     *
     * @return \Excent\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        $obj = $object ?? $this->operation;

        foreach ($data as $property => $value) {
            if ($value !== null) {
                if (is_object($value)) {
                    $snakeCaseProp = Str::snake($property);
                    $this->fill($value, $obj->{$snakeCaseProp});
                } else {
                    $formattedProperty = strtolower(str_replace('_', '', $property));
                    $method = "set{$formattedProperty}";
                    if (method_exists($obj, $method)) {
                        $obj->{$method}($value);
                    }
                }
            }
        }

        return $this;
    }
}
