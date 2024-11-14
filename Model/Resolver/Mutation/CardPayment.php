<?php

namespace Tpay\Magento2GraphQl\Model\Resolver\Mutation;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Api\TpayInterface;
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

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        $args = $args['input'];
        $orderId = $args['incrementId'];

        if (true === $args['storeCard'] && false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized. Try again with authorization token'));
        }

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
        $paymentData['additional_information'][TpayInterface::CARDDATA] = $args['cardData'];
        $paymentData['additional_information'][TpayInterface::CARD_SAVE] = $args['storeCard'];

        if ($transactionId) {
            $paymentData['additional_information']['transaction_id'] = $transactionId;
        }

        if ($args['storeCard']) {
            $paymentData['additional_information'][TpayInterface::SHORT_CODE] = $args['shortCode'];
            $paymentData['additional_information'][TpayInterface::CARD_VENDOR] = $args['cardVendor'];
        }

        $payment->setData($paymentData);
        $this->tpayService->saveOrderPayment($payment);
    }
}
