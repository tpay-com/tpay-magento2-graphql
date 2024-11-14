<?php

namespace Tpay\Magento2GraphQl\Model\Resolver\Query;

use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Service\TpayAliasServiceInterface;

class BlikAlias implements ResolverInterface
{
    protected TpayAliasServiceInterface $aliasService;
    protected GetCustomer $getCustomer;

    public function __construct(TpayAliasServiceInterface $aliasService, GetCustomer $getCustomer)
    {
        $this->aliasService = $aliasService;
        $this->getCustomer = $getCustomer;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized. Try again with authorization token'));
        }

        $customer = $this->getCustomer->execute($context);
        $result = $this->aliasService->getCustomerAlias($customer->getId());

        return ['alias' => $result ?: null];
    }
}
