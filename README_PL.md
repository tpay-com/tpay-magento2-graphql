# Moduł Płatności Tpay obsługujący GraphQL dla Magento 2

### Podstawowe informacje

Oficjalny moduł szybkich płatności online za pośrednictwem Tpay na platformie Magento 2 obsługujący GraphQl.
Moduł jest rozszerzeniem głównego modułu [Magento2 module for Tpay](https://github.com/tpay-com/tpay-magento2-basic).
Aby móc skorzystać z Magento 2
GraphQL należy zainstalować oba moduły: Magento2 basic oraz Magento2 GraphQL.

### Wymagania

- [Tpay module installed (from version 2.0.0)](https://github.com/tpay-com/tpay-magento2-basic)
- Program composer na serwerze

### Instalacja modułu przez Composer

1. Pobierz pliki modułu. W głównym folderze Magento wpisz komendę:

```
composer require tpay-com/magento2-graphql
```

2. Uruchom moduł. W głównym folderze Magento wpisz komendę:

```
php bin/magento module:enable Tpay_Magento2GraphQl
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

### Wsparcie techniczne

W przypadku dodatkowych pytań zapraszamy do kontaktu z Działem Obsługi Klienta Tpay pod tym
linkiem: https://tpay.com/kontakt

### [Changelog](https://github.com/tpay-com/tpay-magento2-graphql/releases)
