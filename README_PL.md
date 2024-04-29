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
   
#### Konfiguracja własnych adresów URL 
W module istnieje możliwość dodania własnych adresów URL, na które ma zostać przekierowany kupujący po ich opłaceniu. W przypadku braku ustawień adresów zostaną one zastąpione domyślnymi adresami URL z platformy sprzedażowej.

W konfiguracji modułu pojawi się dodatkowa zakładka do skonfigurowania adresów URL. 

![konfiguracja_adresow_w_graphql](https://github.com/tpay-com/tpay-magento2-graphql/assets/90452844/e11885d2-750c-44c8-b5be-e480965e1e34)

- Success URL — przekierowanie na adres URL po poprawnie dokonanej płatności. 

- Error URL — przekierowanie na adres URL, w przypadku gdy kupujący zaniecha transakcje lub wystąpi błąd podczas próby opłacenia jej. 

- Notification URL - adres URL, na który jest wysyłane powiadomienie z systemu Tpay dotyczące statusu transakcji po jej opłaceniu.

### Wsparcie techniczne

W przypadku dodatkowych pytań zapraszamy do kontaktu z Działem Obsługi Klienta Tpay pod tym
linkiem: https://tpay.com/kontakt

### [Changelog](https://github.com/tpay-com/tpay-magento2-graphql/blob/master/CHANGELOG.MD)

### [Schema GraphQL](https://github.com/tpay-com/tpay-magento2-graphql/blob/master/etc/schema.graphqls)
