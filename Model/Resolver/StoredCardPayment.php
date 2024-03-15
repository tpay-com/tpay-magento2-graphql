<?php

namespace Tpay\Magento2GraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Model\ApiFacade\CardTransaction\CardApiFacade;

class StoredCardPayment implements ResolverInterface
{
    private CardApiFacade $cardApiFacade;

    public function __construct(CardApiFacade $cardApiFacade)
    {
        $this->cardApiFacade = $cardApiFacade;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $args = $args['input'];
        $orderId = $args['incrementId'];
        if ($orderId) {
            return ['redirectUrl' => $this->cardApiFacade->makeCardTransaction($orderId)];
        }

        return ['redirectUrl' => 'error'];
    }
}
