# PAYONE FinanceGate
![license LGPL](https://img.shields.io/badge/license-LGPL-blue.svg)
[![GitHub issues](https://img.shields.io/github/issues/PAYONE-GmbH/oxid-5.svg)](https://github.com/PAYONE-GmbH/oxid-5/issues)

The PAYONE-FinanceGate-Module offers more than 20 payment methods for your OXID-Shopsystem. Beside commonly used payment methods as 
Paypal, Sofortueberweisung.de or eight Creditcards there is also the possibility to offer secure paying via bill or debit.

## Version
6.0.0_oxid6_demo_5_6601  
March 1 2017

## Requirements
Installed OXID eShop > v4.7.0

## Link
http://www.payone.de  
http://shop.fatchip.de/OXID-PAYONE-FinanceGate-Connector.html

## Installation
1. Extract the module-package.
2. Copy the content of the folder "copy_this" into your shop root-folder (where config.inc.php lies).
3. Go to Extensions->Modules, select the "PAYONE FinanceGate" extension and press the "Activate" Button in the "Overview" tab.
4. Next you need to deposit a transaction url in the PAYONE-Webinterface at Konfiguration -> Zahlungsportale -> YOUR_PORTAL -> Erweitert -> TransactionStatus URL. The URL has to look like this:
`http://->YOUR_SHOP<-/modules/fcPayOne/status.php`
5. Empty "tmp" folder.
6. There is a new menu item in the OXID-Interface named PAYONE. Here you can set your merchant connect data.

## Update

If you are updating from Version 1.52 or lower:

1. Deactivate the "Payone FinanceGate"
2. Delete the following files/folder from your server:
  1. SHOPROOT/application/controllers/admin/inc
  2. SHOPROOT/application/controllers/admin/fcpayone_admin.php
  3. SHOPROOT/application/controllers/admin/fcpayone_apilog.php
  4. SHOPROOT/application/controllers/admin/fcpayone_apilog_list.php
  5. SHOPROOT/application/controllers/admin/fcpayone_apilog_main.php
  6. SHOPROOT/application/controllers/admin/fcpayone_boni.php
  7. SHOPROOT/application/controllers/admin/fcpayone_boni_list.php
  8. SHOPROOT/application/controllers/admin/fcpayone_boni_main.php
  9. SHOPROOT/application/controllers/admin/fcpayone_common.php
  10. SHOPROOT/application/controllers/admin/fcpayone_list.php
  11. SHOPROOT/application/controllers/admin/fcpayone_log.php
  12. SHOPROOT/application/controllers/admin/fcpayone_log_list.php
  13. SHOPROOT/application/controllers/admin/fcpayone_main.php
  14. SHOPROOT/application/controllers/admin/fcpayone_order.php
  15. SHOPROOT/application/controllers/admin/fcpayone_protocol.php
  16. SHOPROOT/application/controllers/admin/fcpayone_status_forwarding.php
  17. SHOPROOT/application/controllers/admin/fcpayone_status_mapping.php
  18. SHOPROOT/application/controllers/admin/fcpayone_support.php
  19. SHOPROOT/application/controllers/admin/fcpayone_support_list.php
  20. SHOPROOT/application/controllers/admin/fcpayone_support_main.php
  21. SHOPROOT/modules/fcPayOne
  22. SHOPROOT/out/admin/img/certified_extension_100px.png
  23. SHOPROOT/out/admin/img/logoclaim.gif
3. Copy the content of "copy this" into your shop root-folder (where config.inc.php lies).
4. Activate the "Payone FinanceGate"

If you updating from version 2.x or higher:

1. Deactivate the "Payone FinanceGate"
2. Copy the content of "copy this" into your shop root-folder (where config.inc.php lies).
3. Activate the "Payone FinanceGate"

## Notice
When you are using the creditcard iframe payment-method there is no transaction-id ( txid ) in the moment where the order is created.
The transaction-id will be filled in seconds later, when the first "TransactionStatus" from Payone comes in.
So when using pixi or another ERP where the transaction-id is needed, make sure to export the order only when the transaction-id is already there.

## Author
FATCHIP GmbH
www.fatchip.de
<a href="mailto:support@fatchip.de">support@fatchip.de</a>

## Prefix
fc