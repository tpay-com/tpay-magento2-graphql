<?php

namespace Tpay\Magento2GraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Model\ApiFacade\CardTransaction\CardApiFacade;
use Tpay\Magento2\Service\TpayService;

class StoredCardPayment implements ResolverInterface
{
    private CardApiFacade $cardApiFacade;
    private StoredTokens $storedTokens;
    private TpayService $tpayService;

    public function __construct(CardApiFacade $cardApiFacade, StoredTokens $storedTokens, TpayService $tpayService)
    {
        $this->cardApiFacade = $cardApiFacade;
        $this->storedTokens = $storedTokens;
        $this->tpayService = $tpayService;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        $args = $args['input'];
        $orderId = $args['incrementId'];

        $token = $this->findRequiredToken($args['tokenId'], $this->storedTokens->resolve($field, $context, $info, $value, $args));
        $token = $this->handleTokenData($token);

        if ($orderId && !empty($token)) {
            $this->updateAdditionalData($args, $token);

            if ($args['transactionId']) {
                $payment = $this->tpayService->getPayment($orderId);
                $paymentData = $payment->getData();

                return ['redirectUrl' => $this->cardApiFacade->payTransaction($orderId, $paymentData['additional_information'], $args['transactionId'], $token)];
            }

            return ['redirectUrl' => $this->cardApiFacade->makeCardTransaction($orderId, $token)];
        }

        return ['redirectUrl' => 'error'];
    }

    private function handleTokenData(array $token): ?array
    {
        if (!empty($token)) {
            $token = array_values($token)[0];
            $token['cli_auth'] = $token['token'];

            return $token;
        }

        return null;
    }

    private function findRequiredToken(string $tokenId, array $tokens): array
    {
        return array_filter($tokens, function ($token) use ($tokenId) {
            return $tokenId == $token['tokenId'];
        });
    }

    private function updateAdditionalData(array $args, array $token): void
    {
        $transactionId = $args['transactionId'];

        $payment = $this->tpayService->getPayment($args['incrementId']);
        $paymentData = $payment->getData();
        $paymentData['additional_information']['card_id'] = $token['tokenId'];

        if ($transactionId) {
            $paymentData['additional_information']['transaction_id'] = $transactionId;
        }

        $payment->setData($paymentData);
        $this->tpayService->saveOrderPayment($payment);
    }
}
