<?php

namespace Tpay\Magento2GraphQl\Model\Resolver;

use Magento\Checkout\Model\Session;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Phrase;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\TestFramework\TestCase\GraphQl\ResponseContainsErrorsException;
use Tpay\Magento2\Api\TpayConfigInterface;
use Tpay\Magento2\Api\TpayInterface;
use Tpay\Magento2\Model\ApiFacade\OpenApi;
use Tpay\Magento2\Model\ApiFacade\Transaction\TransactionApiFacade;
use Tpay\Magento2\Model\ApiFacade\Transaction\TransactionOriginApi;
use Tpay\Magento2\Service\TpayService;

class BlikPayment implements ResolverInterface
{
    private TransactionApiFacade $transactionApiFacade;
    private TpayInterface $tpay;
    private TpayConfigInterface $tpayConfig;
    private Session $checkoutSession;
    private OrderRepositoryInterface $orderRepository;
    private TpayService $tpayService;

    public function __construct(
        TpayInterface            $tpay,
        TransactionApiFacade     $transactionApiFacade,
        TpayConfigInterface      $tpayConfig,
        Session                  $checkoutSession,
        OrderRepositoryInterface $orderRepository,
        TpayService              $tpayService
    )
    {
        $this->transactionApiFacade = $transactionApiFacade;
        $this->tpay = $tpay;
        $this->tpayConfig = $tpayConfig;
        $this->checkoutSession = $checkoutSession;
        $this->orderRepository = $orderRepository;
        $this->tpayService = $tpayService;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
       $args = $args['input'];
       //tutaj są argumenty do obsługi płatności
    }
}
