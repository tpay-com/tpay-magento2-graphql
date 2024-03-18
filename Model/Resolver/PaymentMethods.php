<?php

namespace Tpay\Magento2GraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Model\ApiFacade\Transaction\TransactionApiFacade;

class PaymentMethods implements ResolverInterface
{
    private TransactionApiFacade $transactionApiFacade;

    public function __construct(TransactionApiFacade $transactionApiFacade)
    {
        $this->transactionApiFacade = $transactionApiFacade;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        return $this->transactionApiFacade->channels();
    }
}
