<?php

namespace Tpay\Magento2GraphQl\Model\Resolver\Mutation;

use Magento\Checkout\Model\Session;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Model\ApiFacade\Transaction\TransactionApiFacade;
use Tpay\Magento2\Service\TpayAliasService;
use Tpay\Magento2\Service\TpayService;

class BlikPayment implements ResolverInterface
{
    private TransactionApiFacade $transactionApiFacade;
    private Session $checkoutSession;
    private TpayService $tpayService;
    private \Magento\Customer\Model\Session $customerSession;
    private TpayAliasService $aliasService;

    public function __construct(
        TransactionApiFacade $transactionApiFacade,
        Session $checkoutSession,
        TpayService $tpayService,
        \Magento\Customer\Model\Session $customerSession,
        TpayAliasService $aliasService
    ) {
        $this->transactionApiFacade = $transactionApiFacade;
        $this->checkoutSession = $checkoutSession;
        $this->tpayService = $tpayService;
        $this->customerSession = $customerSession;
        $this->aliasService = $aliasService;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        $args = $args['input'];
        $orderId = $args['incrementId'];

        if ($orderId) {
            if ($args['blikAlias']) {
                if ($this->customerSession->getCustomerId()) {
                    $alias = $this->aliasService->getCustomerAlias($this->customerSession->getCustomerId());
                } else {
                    throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized. Try again with authorization token'));
                }
            }

            $transaction = $this->transactionApiFacade->blik($args['transactionId'], $args['blikCode'], $alias ?? null);

            if (true === $this->transactionApiFacade->isOpenApiUse()) {
                if (isset($transaction['payments']['errors']) && count($transaction['payments']['errors']) > 0) {
                    return ['redirectUrl' => 'error'];
                }

                return ['redirectUrl' => 'success'];
            }
            $this->checkoutSession->unsQuoteId();

            if (!(isset($transaction['result']) && 1 === $transaction['result'])) {
                $this->tpayService->addCommentToHistory(
                    $orderId,
                    'User has typed wrong blik code and has been redirected to transaction panel in order to finish payment'
                );

                return ['redirectUrl' => 'error'];
            }

            return ['redirectUrl' => 'success'];
        }

        return ['redirectUrl' => 'error'];
    }
}
