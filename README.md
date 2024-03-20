# Magento2-Tpay-GraphQL

[Tpay](https://tpay.com) payment gateway Magento2 module for GraphQL.

## Installation process

1. Run `composer require tpay-com/magento2-graphql`

2. Execute following commands to enable module:
    ```console
    php bin/magento module:enable Tpay_Magento2GraphQl
    php bin/magento setup:upgrade
    php bin/magento setup:di:compile
    ```

## Schema
You can find that explains GraphQl queries/mutations defined by module in `etc/schema.graphqls`.
