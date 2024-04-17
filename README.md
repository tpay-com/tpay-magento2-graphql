# Tpay Payments Module supporting GraphQL for Magento 2

### Basic information

Official module for fast online payments via Tpay on Magento 2 switch supporting GraphQl.
The module is an extensible [Magento2 module for Tpay](https://github.com/tpay-com/tpay-magento2-basic). To manage with Magento 2 GraphQL, you
need two modules: Magento2 basic and Magento2 GraphQL.

### Requirements

- [Tpay module installed (from version 2.0.0)](https://github.com/tpay-com/tpay-magento2-basic)
- Composer on the server

### Module installation via Composer

1. Download the module files. In the main Magento folder, run the command:

```
composer require tpay-com/magento2-graphql
```

2. Turn on the module. In the main Magento folder, run the command:

```
php bin/magento module:enable Tpay_Magento2GraphQl
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

### Technical assistance

If you have any questions, please contact Tpay Customer Service at this link: https://tpay.com/kontakt

### [Changelog](https://github.com/tpay-com/tpay-magento2-graphql/releases)
