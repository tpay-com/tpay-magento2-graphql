# Magento2-Tpay-GraphQL

[Tpay](https://tpay.com) payment gateway Magento2 module for GraphQL.

## Manual installation

1. Go to Magento2 root directory.

2. Copy plugin files to `app/code/Tpay/Magento2GraphQl`.

3. Execute following commands to enable module:
    ```console
    php bin/magento module:enable Tpay_Magento2GraphQl
    php bin/magento setup:upgrade
    ```

## Schema
You can find that explains GraphQl queries/mutations defined by module in `etc/schema.graphqls`.
