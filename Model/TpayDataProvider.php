<?php

namespace Tpay\Magento2GraphQl\Model;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\QuoteGraphQl\Model\Cart\Payment\AdditionalDataProviderInterface;
use Tpay\Magento2\Api\TpayInterface;

class TpayDataProvider implements AdditionalDataProviderInterface
{
    /** @throws GraphQlInputException */
    public function getData(array $data): array
    {
        $code = $data['code'];
        if (TpayInterface::CODE === $code) {
            $data['tpay'][TpayInterface::TERMS_ACCEPT] ??= false;
        }

        return $data['tpay'];
    }
}
