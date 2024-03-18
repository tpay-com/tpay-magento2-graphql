<?php

namespace Tpay\Magento2GraphQl\Model;

use Magento\QuoteGraphQl\Model\Cart\Payment\AdditionalDataProviderInterface;
use Tpay\Magento2\Api\TpayInterface;

class TermsOfServiceDataProvider implements AdditionalDataProviderInterface
{
    /**
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlInputException
     */
    public function getData(array $data): array
    {
        $code = $data['code'];
        $method = $data[$code]['method'] ?? null;
        if (!$method) {
            throw new GraphQlInputException(__('No payment method provided!'));
        }

        $data[$code][TpayInterface::TERMS_ACCEPT] ??= false;

        return $data[$code];
    }
}
