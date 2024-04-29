# Tpay Payments Module supporting GraphQL for Magento 2

### [Polish version of README](https://github.com/tpay-com/tpay-magento2-graphql/blob/master/README_PL.md)

### Basic information

Official module for fast online payments via Tpay on Magento 2 switch supporting GraphQl.
The module is an extensible [Magento2 module for Tpay](https://github.com/tpay-com/tpay-magento2-basic). To manage with
Magento 2 GraphQL, you
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

#### Configuration URL addresses  
In the module, you can add your own URL addresses to which the payer is to be redirected after paying. If there are no address settings, they will be replaced with the default URLs from the sales platform.

In the module configuration, you can configure URL addresses.

![konfiguracja_adresow_w_graphql](https://github.com/tpay-com/tpay-magento2-graphql/assets/90452844/04782a03-1fc3-4c3e-b926-224924edd727)

- Success URL - redirection to the URL after successful payment.

- Error URL - Redirect to the URL if the payer abandons the transaction or encounters an error while trying to pay.

- Notification URL - URL address to which a notification is sent from the Tpay system regarding the status of the transaction after payment.

### Technical assistance

If you have any questions, please contact Tpay Customer Service at this link: https://tpay.com/kontakt

### [Changelog](https://github.com/tpay-com/tpay-magento2-graphql/releases)

### [Schema GraphQL](https://github.com/tpay-com/tpay-magento2-graphql/blob/master/etc/schema.graphqls)# Tpay Payments Module supporting GraphQL for Magento 2
