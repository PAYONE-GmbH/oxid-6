/** 
 * PAYONE OXID Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PAYONE OXID Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PAYONE OXID Connector.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.payone.de
 * @copyright (C) Payone GmbH
 * @version   OXID eShop CE
 */

==Title==
PAYONE FinanceGate

==Author==
FATCHIP GmbH
www.fatchip.de

==Prefix==
fc

==Version==
6.0.0_oxid6_demo_5_6601
March 1 2017

==Link==
http://www.payone.de
http://shop.fatchip.de/OXID-PAYONE-FinanceGate-Connector.html

==Mail==
<a href="mailto:support@fatchip.de">support@fatchip.de</a>

==Requirements==
/

==Description==
The PAYONE-FinanceGate-Module offers more than 20 payment methods for your OXID-Shopsystem. Beside commonly used payment methods as 
Paypal, Sofortueberweisung.de or eight Creditcards there is also the possibility to offer secure paying via bill or debit.

==Extend==
void

==Installation==
Notice: 
1. Extract the module-package.
2. Copy the content of the folder "copy_this" into your shop root-folder (where config.inc.php lies).
3. Go to Extensions->Modules, select the "PAYONE FinanceGate" extension and press the "Activate" Button in the "Overview" tab.
4. Next you need to deposit a transaction url in the PAYONE-Webinterface at Konfiguration -> Zahlungsportale -> YOUR_PORTAL -> Erweitert -> TransactionStatus URL  .
The URL has to look like this:
http://->YOUR_SHOP<-/modules/fcPayOne/status.php
8. Empty "tmp" folder.
9. There is a new menu item in the OXID-Interface named PAYONE. Here you can set your merchant connect data.

==Update==

If you are updating from Version 1.52 or lower:

1. Deactivate the "Payone FinanceGate"
2. Delete the following files/folder from your server:
- SHOPROOT/application/controllers/admin/inc
- SHOPROOT/application/controllers/admin/fcpayone_admin.php
- SHOPROOT/application/controllers/admin/fcpayone_apilog.php
- SHOPROOT/application/controllers/admin/fcpayone_apilog_list.php
- SHOPROOT/application/controllers/admin/fcpayone_apilog_main.php
- SHOPROOT/application/controllers/admin/fcpayone_boni.php
- SHOPROOT/application/controllers/admin/fcpayone_boni_list.php
- SHOPROOT/application/controllers/admin/fcpayone_boni_main.php
- SHOPROOT/application/controllers/admin/fcpayone_common.php
- SHOPROOT/application/controllers/admin/fcpayone_list.php
- SHOPROOT/application/controllers/admin/fcpayone_log.php
- SHOPROOT/application/controllers/admin/fcpayone_log_list.php
- SHOPROOT/application/controllers/admin/fcpayone_main.php
- SHOPROOT/application/controllers/admin/fcpayone_order.php
- SHOPROOT/application/controllers/admin/fcpayone_protocol.php
- SHOPROOT/application/controllers/admin/fcpayone_status_forwarding.php
- SHOPROOT/application/controllers/admin/fcpayone_status_mapping.php
- SHOPROOT/application/controllers/admin/fcpayone_support.php
- SHOPROOT/application/controllers/admin/fcpayone_support_list.php
- SHOPROOT/application/controllers/admin/fcpayone_support_main.php

- SHOPROOT/modules/fcPayOne

- SHOPROOT/out/admin/img/certified_extension_100px.png
- SHOPROOT/out/admin/img/logoclaim.gif

3. Copy the content of "copy this" into your shop root-folder (where config.inc.php lies).
4. Activate the "Payone FinanceGate"


If you updating from version 2.x or higher:

1. Deactivate the "Payone FinanceGate"
2. Copy the content of "copy this" into your shop root-folder (where config.inc.php lies).
3. Activate the "Payone FinanceGate"


==Notice==
When you are using the creditcard iframe payment-method there is no transaction-id ( txid ) in the moment where the order is created.
The transaction-id will be filled in seconds later, when the first "TransactionStatus" from Payone comes in.
So when using pixi or another ERP where the transaction-id is needed, make sure to export the order only when the transaction-id is already there.