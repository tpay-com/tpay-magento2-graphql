<?php

namespace Tpay\Magento2GraphQl\Model\Resolver\Mutation;

use Magento\Checkout\Model\Session;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Model\ApiFacade\Transaction\TransactionApiFacade;
use Tpay\Magento2\Service\TpayAliasService;
use Tpay\Magento2\Service\TpayService;

class BlikAliasPayment implements ResolverInterface
{
    private TransactionApiFacade $transactionApiFacade;
    private Session $checkoutSession;
    private \Magento\Customer\Model\Session $customerSession;
    private TpayAliasService $aliasService;

    public function __construct(TransactionApiFacade $transactionApiFacade, Session $checkoutSession, TpayAliasService $aliasService, \Magento\Customer\Model\Session $customerSession)
    {
        $this->transactionApiFacade = $transactionApiFacade;
        $this->checkoutSession = $checkoutSession;
        $this->aliasService = $aliasService;
        $this->customerSession = $customerSession;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        $args = $args['input'];
        $orderId = $args['incrementId'];

        if ($orderId) {
            $alias = $this->aliasService->getCustomerAlias($this->customerSession->getCustomerId());
            $transaction = $this->transactionApiFacade->blikAlias($args['transactionId'], $alias);

            if (true === $this->transactionApiFacade->isOpenApiUse()) {
                if (isset($transaction['payments']['errors']) && count($transaction['payments']['errors']) > 0) {
                    return ['redirectUrl' => 'error'];
                }

                return ['redirectUrl' => 'success'];
            }

            $this->checkoutSession->unsQuoteId();

            return ['redirectUrl' => 'success'];
        }

        return ['redirectUrl' => 'error'];
    }
}
