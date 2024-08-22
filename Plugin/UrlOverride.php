<?php

namespace Tpay\Magento2GraphQl\Plugin;

use Tpay\Magento2\Model\TpayPayment;
use Tpay\Magento2\Provider\ConfigurationProvider;

class UrlOverride
{
    private ConfigurationProvider $tpayConfig;

    public function __construct(ConfigurationProvider $tpayConfig)
    {
        $this->tpayConfig = $tpayConfig;
    }

    public function afterGetTpayFormData(TpayPayment $subject, $result)
    {
        $successUrl = $this->tpayConfig->getConfigData('graphql_url_override/success_url', $subject->getStore());
        $errorUrl = $this->tpayConfig->getConfigData('graphql_url_override/error_url', $subject->getStore());
        $notificationUrl = $this->tpayConfig->getConfigData('graphql_url_override/notification_url', $subject->getStore());

        if (!empty($successUrl)) {
            $result['return_url'] = $successUrl;
        }

        if (!empty($errorUrl)) {
            $result['return_error_url'] = $errorUrl;
        }

        if (!empty($notificationUrl)) {
            $result['result_url'] = $notificationUrl;
        }

        return $result;
    }
}
