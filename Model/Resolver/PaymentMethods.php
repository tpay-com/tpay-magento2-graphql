<?php

namespace Tpay\Magento2GraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Tpay\Magento2\Model\ApiFacade\OpenApi;

class PaymentMethods implements ResolverInterface
{
    private OpenApi $openApi;

    public function __construct(OpenApi $openApi)
    {
        $this->openApi = $openApi;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null)
    {
        return $this->openApi->channels();
    }
}
