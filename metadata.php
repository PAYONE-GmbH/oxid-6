<?php

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

$sMetadataVersion = '1.1';

$aModule = array(
    'id'            => 'fcpayone',
    'title'         => 'PAYONE Payment f&uuml;r OXID eShop',
    'description'   => 'Sie suchen nach der optimalen Payment-L&ouml;sung f&uuml;r Ihren Online-Shop?<br><br>
                        PAYONE bietet Unternehmensl&ouml;sungen zur automatisierten und ganzheitlichen Abwicklung aller Zahlungsprozesse im E-Commerce. 
                        Der Payment Service Provider ist ein Unternehmen der Sparkassen-Finanzgruppe und von der Bundesanstalt f&uuml;r Finanzdienstleistungsaufsicht als Zahlungsinstitut zugelassen. 
                        Das Leistungsspektrum umfasst die Akzeptanz und Abwicklung nationaler und internationaler Zahlungsarten sowie alle Zahlungsdienstleistungen. 
                        Standardisierte Schnittstellen und Extensions erlauben eine einfache Integration in bestehende E-Commerce und IT-Systeme bei h&ouml;chsten Sicherheitsstandards.<br><br>
                        Hier finden Sie weitere Informationen zum PAYONE Payment-Modul f&uuml;r OXID eShop: 
                        <a href="https://www.payone.de/plattform-integration/extensions/oxid/" style="color:darkblue;text-decoration: underline;" title="PAYONE Homepage" target="_blank">
                            https://www.payone.de
                        </a>',
    'thumbnail'     => 'picture.gif',
    'version'       => '1.2.1',
    'author'        => 'FATCHIP GmbH',
    'email'         => 'kontakt@fatchip.de',
    'url'           => 'https://wiki.fatchip.de/public/faqpayone',
    'extend'        => array(
        // controllers
        'basket'                            => 'fc/fcpayone/extend/application/controllers/fcPayOneBasketView',
        'user'                              => 'fc/fcpayone/extend/application/controllers/fcPayOneUserView',
        'order'                             => 'fc/fcpayone/extend/application/controllers/fcPayOneOrderView',
        'payment'                           => 'fc/fcpayone/extend/application/controllers/fcPayOnePaymentView',
        'thankyou'                          => 'fc/fcpayone/extend/application/controllers/fcPayOneThankyouView',
        // models
        'oxbasket'                          => 'fc/fcpayone/extend/application/models/fcPayOneBasket',
        'oxbasketitem'                      => 'fc/fcpayone/extend/application/models/fcPayOneBasketitem',
        'oxorder'                           => 'fc/fcpayone/extend/application/models/fcPayOneOrder',
        'oxorderarticle'                    => 'fc/fcpayone/extend/application/models/fcPayOneOrderarticle',
        'oxpayment'                         => 'fc/fcpayone/extend/application/models/fcPayOnePayment',
        'oxpaymentgateway'                  => 'fc/fcpayone/extend/application/models/fcPayOnePaymentgateway',
        'oxuser'                            => 'fc/fcpayone/extend/application/models/fcPayOneUser',
        // core
        'oxviewconfig'                      => 'fc/fcpayone/extend/core/fcPayOneViewConf',
    ),
    'files'         => array(
        // controllers -> admin
        'fcpayone_adminview'                => 'fc/fcpayone/application/controllers/admin/fcpayone_adminview.php',
        'fcpayone_admindetails'             => 'fc/fcpayone/application/controllers/admin/fcpayone_admindetails.php',
        'fcpayone_adminlist'                => 'fc/fcpayone/application/controllers/admin/fcpayone_adminlist.php',
        'fcPayOne_Main_Ajax'                => 'fc/fcpayone/application/controllers/admin/fcPayOne_Main_Ajax.php',
        'fcpayone_admin'                    => 'fc/fcpayone/application/controllers/admin/fcpayone_admin.php',
        'fcpayone_apilog'                   => 'fc/fcpayone/application/controllers/admin/fcpayone_apilog.php',
        'fcpayone_apilog_list'              => 'fc/fcpayone/application/controllers/admin/fcpayone_apilog_list.php',
        'fcpayone_apilog_main'              => 'fc/fcpayone/application/controllers/admin/fcpayone_apilog_main.php',
        'fcpayone_boni'                     => 'fc/fcpayone/application/controllers/admin/fcpayone_boni.php',
        'fcpayone_boni_list'                => 'fc/fcpayone/application/controllers/admin/fcpayone_boni_list.php',
        'fcpayone_boni_main'                => 'fc/fcpayone/application/controllers/admin/fcpayone_boni_main.php',
        'fcpayone_list'                     => 'fc/fcpayone/application/controllers/admin/fcpayone_list.php',
        'fcpayone_log'                      => 'fc/fcpayone/application/controllers/admin/fcpayone_log.php',
        'fcpayone_log_list'                 => 'fc/fcpayone/application/controllers/admin/fcpayone_log_list.php',
        'fcpayone_main'                     => 'fc/fcpayone/application/controllers/admin/fcpayone_main.php',
        'fcpayone_order'                    => 'fc/fcpayone/application/controllers/admin/fcpayone_order.php',
        'fcpayone_protocol'                 => 'fc/fcpayone/application/controllers/admin/fcpayone_protocol.php',
        'fcpayone_status_forwarding'        => 'fc/fcpayone/application/controllers/admin/fcpayone_status_forwarding.php',
        'fcpayone_status_mapping'           => 'fc/fcpayone/application/controllers/admin/fcpayone_status_mapping.php',
        'fcpayone_error_mapping'            => 'fc/fcpayone/application/controllers/admin/fcpayone_error_mapping.php',
        // controllers
        'fcpayoneiframe'                    => 'fc/fcpayone/application/controllers/fcpayoneiframe.php',
        // models
	'fcpouserflag'                      => 'fc/fcpayone/application/models/fcpouserflag.php',
        'fcporequestlog'                    => 'fc/fcpayone/application/models/fcporequestlog.php',
        'fcpotransactionstatus'             => 'fc/fcpayone/application/models/fcpotransactionstatus.php',
        'fcpomapping'                       => 'fc/fcpayone/application/models/fcpomapping.php',
        'fcpoerrormapping'                  => 'fc/fcpayone/application/models/fcpoerrormapping.php',
        'fcpoforwarding'                    => 'fc/fcpayone/application/models/fcpoforwarding.php',
        'fcpoconfigexport'                  => 'fc/fcpayone/application/models/fcpoconfigexport.php',
        'fcpoklarna'                        => 'fc/fcpayone/application/models/fcpoklarna.php',
        'fcpopaypal'                        => 'fc/fcpayone/application/models/fcpopaypal.php',
        'fcpayone_ajax'                     => 'fc/fcpayone/application/models/fcpayone_ajax.php',
        'fcporatepay'                       => 'fc/fcpayone/application/models/fcporatepay.php',
        // libs
        'fcpohelper'                        => 'fc/fcpayone/lib/fcpohelper.php',
        'fcporequest'                       => 'fc/fcpayone/lib/fcporequest.php',
        // core
        'fcpayone_events'                   => 'fc/fcpayone/core/fcpayone_events.php',
    ),
    'templates' => array(
        // frontend
        'fcpayoneiframe.tpl'                => 'fc/fcpayone/application/views/frontend/tpl/fcpayoneiframe.tpl',
        // admin
        'fcpayone_popup_main.tpl'           => 'fc/fcpayone/application/views/admin/tpl/popups/fcpayone_popup_main.tpl',
        'fcpayone.tpl'                      => 'fc/fcpayone/application/views/admin/tpl/fcpayone.tpl',
        'fcpayone_apilog.tpl'               => 'fc/fcpayone/application/views/admin/tpl/fcpayone_apilog.tpl',
        'fcpayone_apilog_list.tpl'          => 'fc/fcpayone/application/views/admin/tpl/fcpayone_apilog_list.tpl',
        'fcpayone_apilog_main.tpl'          => 'fc/fcpayone/application/views/admin/tpl/fcpayone_apilog_main.tpl',
        'fcpayone_boni.tpl'                 => 'fc/fcpayone/application/views/admin/tpl/fcpayone_boni.tpl',
        'fcpayone_boni_list.tpl'            => 'fc/fcpayone/application/views/admin/tpl/fcpayone_boni_list.tpl',
        'fcpayone_boni_main.tpl'            => 'fc/fcpayone/application/views/admin/tpl/fcpayone_boni_main.tpl',
        'fcpayone_cc_preview.tpl'           => 'fc/fcpayone/application/views/admin/tpl/fcpayone_cc_preview.tpl',
        'fcpayone_list.tpl'                 => 'fc/fcpayone/application/views/admin/tpl/fcpayone_list.tpl',
        'fcpayone_log.tpl'                  => 'fc/fcpayone/application/views/admin/tpl/fcpayone_log.tpl',
        'fcpayone_log_list.tpl'             => 'fc/fcpayone/application/views/admin/tpl/fcpayone_log_list.tpl',
        'fcpayone_main.tpl'                 => 'fc/fcpayone/application/views/admin/tpl/fcpayone_main.tpl',
        'fcpayone_order.tpl'                => 'fc/fcpayone/application/views/admin/tpl/fcpayone_order.tpl',
        'fcpayone_protocol.tpl'             => 'fc/fcpayone/application/views/admin/tpl/fcpayone_protocol.tpl',
        'fcpayone_status_forwarding.tpl'    => 'fc/fcpayone/application/views/admin/tpl/fcpayone_status_forwarding.tpl',
        'fcpayone_status_mapping.tpl'       => 'fc/fcpayone/application/views/admin/tpl/fcpayone_status_mapping.tpl',
        'fcpayone_error_mapping.tpl'        => 'fc/fcpayone/application/views/admin/tpl/fcpayone_error_mapping.tpl',
    ),
    'events'        => array(
        'onActivate'                        => 'fcpayone_events::onActivate',
        'onDeactivate'                      => 'fcpayone_events::onDeactivate',
    ),
    'blocks'        => array(
        array(
            'template' => 'layout/base.tpl',
            'block' => 'base_js',
            'file' => 'fcpo_base_js_extend'
        ),
        array(
            'template' => 'layout/base.tpl',
            'block' => 'base_style',
            'file' => 'fcpo_base_css_extend'
        ),
        array(
            'template' => 'page/checkout/basket.tpl',
            'block' => 'checkout_basket_main',
            'file' => 'fcpo_basket_override'
        ),
        array(
            'template' => 'widget/minibasket/minibasket.tpl',
            'block' => 'widget_minibasket_total',
            'file' => 'fcpo_minibasket_total_override',
        ),
        array(
            'template' => 'page/checkout/order.tpl',
            'block' => 'checkout_order_address',
            'file' => 'fcpo_order_override'
        ),
        array(
            'template' => 'page/checkout/user.tpl',
            'block' => 'checkout_user_main',
            'file' => 'fcpo_user_override'
        ),
        array(
            'template' => '_formparams.tpl',
            'block' => 'admin_formparams',
            'file' => 'fcpo_admin_formparams',
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'change_payment',
            'file' => 'fcpo_payment_override',
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'select_payment',
            'file' => 'fcpo_payment_select_override',
        ),
        array(
            'template' => 'page/checkout/order.tpl',
            'block' => 'order_basket',
            'file' => 'fcpo_order_basket_override',
        ),
        array(
            'template' => 'page/checkout/order.tpl',
            'block' => 'checkout_order_errors',
            'file' => 'fcpo_order_checkout_order_errors'
        ),
        array(
            'template' => 'page/checkout/thankyou.tpl',
            'block' => 'checkout_thankyou_proceed',
            'file' => 'fcpo_thankyou_checkout_thankyou',
        ),
        array(
            'template' => 'email/html/order_cust.tpl',
            'block' => 'email_html_order_cust_paymentinfo',
            'file' => 'fcpo_email_html_order_cust_paymentinfo',
        ),
        array(
            'template' => 'email/plain/order_cust.tpl',
            'block' => 'email_plain_order_cust_paymentinfo',
            'file' => 'fcpo_email_plain_order_cust_paymentinfo',
        ),
        array(
            'template' => 'order_list.tpl',
            'block' => 'admin_order_list_colgroup',
            'file' => 'fcpo_admin_order_list_colgroup',
        ),
        array(
            'template' => 'order_list.tpl',
            'block' => 'admin_order_list_filter',
            'file' => 'fcpo_admin_order_list_filter',
        ),
        array(
            'template' => 'order_list.tpl',
            'block' => 'admin_order_list_sorting',
            'file' => 'fcpo_admin_order_list_sorting',
        ),
        array(
            'template' => 'order_list.tpl',
            'block' => 'admin_order_list_item',
            'file' => 'fcpo_admin_order_list_item',
        ),
        array(
            'template' => 'payment_list.tpl',
            'block' => 'admin_payment_list_filter',
            'file' => 'fcpo_admin_payment_list_filter',
        ),
        array(
            'template' => 'payment_main.tpl',
            'block' => 'admin_payment_main_form',
            'file' => 'fcpo_admin_payment_main_form',
        ),
        array(
            'template' => 'page/checkout/basket.tpl',
            'block' => 'basket_btn_next_top',
            'file' => 'fcpo_basket_btn_next',
        ),
        array(
            'template' => 'page/checkout/basket.tpl',
            'block' => 'basket_btn_next_bottom',
            'file' => 'fcpo_basket_btn_next_bottom',
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'checkout_payment_errors',
            'file' => 'fcpo_payment_errors',
        ),
        array(
            'template' => 'page/checkout/basket.tpl',
            'block' => 'checkout_basket_main',
            'file' => 'fcpo_basket_errors',
        ),
    ),
);

if(class_exists('\OxidEsales\Facts\Facts')) {
    $oFacts = new \OxidEsales\Facts\Facts();
    $sShopEdition = $oFacts->getEdition();
    if($sShopEdition == \OxidEsales\Facts\Edition\EditionSelector::ENTERPRISE) {
        $aModule['blocks'][] = array(
                'template' => 'roles_bemain.tpl',
                'block' => 'admin_roles_bemain_form',
                'file' => 'fcpo_admin_roles_bemain_form',
        );
        $aModule['extend']['roles_bemain'] = 'fc/fcpayone/extend/application/controllers/admin/fcPayOneRolesBeMain';
    }
}
