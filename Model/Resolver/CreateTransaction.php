<?php

namespace Tpay\Magento2GraphQl\Model\Resolver;

use Exception;
use Magento\Checkout\Model\Session;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Phrase;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Tpay\Magento2\Api\TpayConfigInterface;
use Tpay\Magento2\Api\TpayInterface;
use Tpay\Magento2\Model\ApiFacade\Transaction\TransactionApiFacade;
use Tpay\Magento2\Service\TpayService;

class CreateTransaction implements ResolverInterface
{
    private TransactionApiFacade $transactionApiFacade;
    private TpayInterface $tpay;
    private TpayConfigInterface $tpayConfig;
    private Session $checkoutSession;
    private OrderRepositoryInterface $orderRepository;
    private TpayService $tpayService;
    private StoreManagerInterface $storeManager;

    public function __construct(
        TpayInterface $tpay,
        TransactionApiFacade $transactionApiFacade,
        TpayConfigInterface $tpayConfig,
        Session $checkoutSession,
        OrderRepositoryInterface $orderRepository,
        TpayService $tpayService,
        StoreManagerInterface $storeManager
    ) {
        $this->transactionApiFacade = $transactionApiFacade;
        $this->tpay = $tpay;
        $this->tpayConfig = $tpayConfig;
        $this->checkoutSession = $checkoutSession;
        $this->orderRepository = $orderRepository;
        $this->tpayService = $tpayService;
        $this->storeManager = $storeManager;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        if (!isset($value['order_number'])) {
            return;
        }

        $orderId = $this->checkoutSession->getLastRealOrderId();
        if (!$orderId) {
            return;
        }
        $transaction = null;
        try {
            $order = $this->orderRepository->get($orderId);
            /** @var \Magento\Sales\Model\Order\Payment $payment */
            $payment = $order->getPayment();
            $paymentData = $payment->getData();

            if ('PLN' !== $this->storeManager->getStore()->getCurrentCurrencyCode()) {
                return ['transaction' => null, 'redirectUrl' => null];
            }
            $transaction = $this->prepareTransaction($order->getIncrementId(), $paymentData);
            $transactionUrl = $transaction['url'];

            if (isset($transaction['transactionId'])) {
                $paymentData['additional_information']['transaction_id'] = $transaction['transactionId'];
            }
            $this->tpayService->addCommentToHistory($orderId, 'Transaction title '.$transaction['title']);

            if (true === $this->tpayConfig->redirectToChannel()) {
                $transactionUrl = str_replace('gtitle', 'title', $transactionUrl);
            }

            $this->tpayService->addCommentToHistory($orderId, 'Transaction link '.$transactionUrl);
            $paymentData['additional_information']['transaction_url'] = $transactionUrl;
            $payment->setData($paymentData);
            $this->tpayService->saveOrderPayment($payment);
        } catch (Exception $e) {
            throw new GraphQlInputException(new Phrase($e->getMessage()), $e);
        }

        return ['transaction' => $transaction['transactionId'], 'redirectUrl' => $transaction['transactionPaymentUrl']];
    }

    private function prepareTransaction($orderId, array $additionalPaymentInformation): array
    {
        $data = $this->tpay->getTpayFormData($orderId);

        $data['group'] = (int) ($additionalPaymentInformation['group'] ?? null);
        $data['channel'] = (int) ($additionalPaymentInformation['channel'] ?? null);

        if ($this->tpayConfig->redirectToChannel()) {
            $data['direct'] = 1;
        }

        $data = $this->transactionApiFacade->originApiFieldCorrect($data);
        $data = $this->transactionApiFacade->translateGroupToChannel($data, $this->tpayConfig->redirectToChannel());

        if (isset($data['channel']) && $data['channel']) {
            return $this->transactionApiFacade->createWithInstantRedirection($data);
        }

        return $this->transactionApiFacade->createTransaction($data);
    }
}
