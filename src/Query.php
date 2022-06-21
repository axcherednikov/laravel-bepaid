<?php

declare(strict_types=1);

namespace Excent\BePaidLaravel;

use Excent\BePaidLaravel\Contracts\FillingDTOContract;
use BeGateway\{QueryByPaymentToken, QueryByTrackingId, QueryByUid};
use Excent\BePaidLaravel\Contracts\IGateway;
use Excent\BePaidLaravel\Dtos\{QueryByPaymentTokenDto, QueryByTrackingIdDto, QueryByUidDto};

class Query extends GatewayAbstract
{
    public QueryByPaymentToken|QueryByTrackingId|QueryByUid $operation;

    public function __construct(
        private QueryByPaymentToken $queryByPaymentToken,
        private QueryByTrackingId $queryByTrackingId,
        private QueryByUid $queryByUid
    ) {
    }

    public function fill(FillingDTOContract $data, $object = null): IGateway
    {
        $this->operation = match (get_class($data)) {
            QueryByPaymentTokenDto::class => $this->queryByPaymentToken,
            QueryByTrackingIdDto::class => $this->queryByTrackingId,
            QueryByUidDto::class => $this->queryByUid,
        };

        return parent::fill($data, $object);
    }
}
