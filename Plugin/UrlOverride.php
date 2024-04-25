<?php

namespace Tpay\Magento2GraphQl\Plugin;

use Tpay\Magento2\Provider\ConfigurationProvider;
use Tpay\Magento2\Model\TpayPayment;

class UrlOverride
{
    private ConfigurationProvider $tpayConfig;

    public function __construct(ConfigurationProvider $tpayConfig)
    {
        $this->tpayConfig = $tpayConfig;
    }

    public function afterGetTpayFormData(TpayPayment $subject, $result, $name, $storeId = 0)
    {
        $successUrl = $this->tpayConfig->getConfigData('graphql_url_override/success_url', $storeId);
        $errorUrl = $this->tpayConfig->getConfigData('graphql_url_override/error_url', $storeId);
        $notificationUrl = $this->tpayConfig->getConfigData('graphql_url_override/notification_url', $storeId);

        if(!empty($successUrl)){
            $result['return_url'] = $successUrl;
        }

        if(!empty($errorUrl)){
            $result['return_error_url'] = $errorUrl;
        }

        if(!empty($notificationUrl)){
            $result['result_url'] = $notificationUrl;
        }

        return $result;
    }
}
