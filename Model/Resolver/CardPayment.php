<?php

namespace Tpay\Magento2GraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Model\ApiFacade\CardTransaction\CardApiFacade;
use Tpay\Magento2\Service\TpayService;

class CardPayment implements ResolverInterface
{
    private CardApiFacade $cardApiFacade;
    private TpayService $tpayService;

    public function __construct(CardApiFacade $cardApiFacade, TpayService $tpayService)
    {
        $this->cardApiFacade = $cardApiFacade;
        $this->tpayService = $tpayService;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $args = $args['input'];
        $orderId = $args['incrementId'];
        $this->updateAdditionalData($args);

        if ($args['transactionId']) {
            $payment = $this->tpayService->getPayment($orderId);
            $paymentData = $payment->getData();

            return ['redirectUrl' => $this->cardApiFacade->payTransaction($orderId, $paymentData['additional_information'], $args['transactionId'])];
        }

        if ($orderId) {
            return ['redirectUrl' => $this->cardApiFacade->makeCardTransaction($orderId)];
        }

        return ['redirectUrl' => 'error'];
    }

    private function updateAdditionalData(array $args): void
    {
        $transactionId = $args['transactionId'];

        $payment = $this->tpayService->getPayment($args['incrementId']);
        $paymentData = $payment->getData();
        $paymentData['additional_information']['card_data'] = $args['cardData'];
        $paymentData['additional_information']['card_save'] = $args['storeCard'];

        if ($transactionId) {
            $paymentData['additional_information']['transaction_id'] = $transactionId;
        }

        $payment->setData($paymentData);
        $this->tpayService->saveOrderPayment($payment);
    }
}
