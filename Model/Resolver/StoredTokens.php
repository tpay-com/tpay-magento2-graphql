<?php

namespace Tpay\Magento2GraphQl\Model\Resolver;

use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Model\ApiFacade\CardTransaction\CardApiFacade;
use Tpay\Magento2\Service\TpayTokensService;

class StoredTokens implements ResolverInterface
{
    private TpayTokensService $tokensService;
    private GetCustomer $getCustomer;
    private CardApiFacade $cardApiFacade;

    public function __construct(TpayTokensService $tokensService, GetCustomer $getCustomer, CardApiFacade $transactionApiFacade)
    {
        $this->tokensService = $tokensService;
        $this->getCustomer = $getCustomer;
        $this->cardApiFacade = $transactionApiFacade;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized. Try again with authorization token'));
        }
        $customer = $this->getCustomer->execute($context);

        return $this->tokensService->getCustomerTokens($customer->getId(),$this->cardApiFacade->isOpenApiUse());
    }
}
