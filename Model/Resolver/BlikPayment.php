<?php

namespace Tpay\Magento2GraphQl\Model\Resolver;

use Magento\Checkout\Model\Session;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Model\ApiFacade\Transaction\TransactionApiFacade;
use Tpay\Magento2\Service\TpayService;

class BlikPayment implements ResolverInterface
{
    private TransactionApiFacade $transactionApiFacade;
    private Session $checkoutSession;
    private TpayService $tpayService;

    public function __construct(TransactionApiFacade $transactionApiFacade, Session $checkoutSession, TpayService $tpayService)
    {
        $this->transactionApiFacade = $transactionApiFacade;
        $this->checkoutSession = $checkoutSession;
        $this->tpayService = $tpayService;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $args = $args['input'];
        $orderId = $args['incrementId'];
        if ($orderId) {
            $transaction = $this->transactionApiFacade->blik($args['transactionId'], $args['blikCode']);

            if (true === $this->transactionApiFacade->isOpenApiUse()) {
                if (isset($transaction['payments']['errors']) && count($transaction['payments']['errors']) > 0) {
                    return ['redirectUrl' => 'error', 'result' => 'error'];
                }

                return ['redirectUrl' => 'success', 'result' => 'success'];
            }
            $this->checkoutSession->unsQuoteId();

            if (!(isset($transaction['result']) && 1 === $transaction['result'])) {
                $this->tpayService->addCommentToHistory($orderId, 'User has typed wrong blik code and has been redirected to transaction panel in order to finish payment');

                return ['redirectUrl' => 'error', 'result' => 'error'];
            }

            return ['redirectUrl' => 'success', 'result' => 'success'];
        }

        return ['redirectUrl' => 'error', 'result' => 'error'];
    }
}
