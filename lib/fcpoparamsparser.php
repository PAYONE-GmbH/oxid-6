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
 * PHP version 5
 *
 * @copyright 2003 - 2016 Payone GmbH
 * @version   OXID eShop CE
 * @link      http://www.payone.de
 */

class fcpoparamsparser
{
    /**
     * Database object
     *
     * @var fcpohelper
     */
    protected $_oFcpoHelper;

    /**
     * Class constructor, sets all required parameters for requests.
     */
    public function __construct()
    {
        $this->_oFcpoHelper = oxNew('fcpohelper');
    }

    /**
     * @param $sClientToken
     * @param $sParamsJson
     * @return string
     */
    public function _fcpoGetKlarnaWidgetJS($sClientToken, $sParamsJson)
    {
        $aParams = json_decode($sParamsJson, true);
        $aKlarnaData = $this->_fcpoGetKlarnaData();
        $aKlarnaData = $this->_fcpoRemoveKlarnaDataForCountry($aKlarnaData);

        $aKlarnaWidgetSearch = array(
            '%%TOKEN%%',
            '%%PAYMENT_CONTAINER_ID%%',
            '%%PAYMENT_CATEGORY%%',
            '%%KLARNA_DATA%%',
        );

        $aKlarnaWidgetReplace = array(
            $sClientToken,
            $aParams['payment_container_id'],
            $aParams['payment_category'],
            json_encode($aKlarnaData),
        );

        $sKlarnaWidgetJS = file_get_contents($this->_fcpoGetKlarnaWidgetPath());
        $sKlarnaWidgetJS = str_replace($aKlarnaWidgetSearch, $aKlarnaWidgetReplace, $sKlarnaWidgetJS);

        return (string) $sKlarnaWidgetJS;
    }

    /**
     * Return needed data for performing authorization
     *
     * @param void
     * @return array
     */
    protected function _fcpoGetKlarnaData()
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $oUser = $this->_fcpogetUser();
        $oShippingAddress = $this->_fcpoGetShippingAddress();
        $oCur = $oCur = $oConfig->getActShopCurrencyObject();
        $blHasShipping = (!$oShippingAddress) ? false : true;
        $sGender = ($oUser->oxuser__oxsal->value == 'MR') ? 'male' : 'female';

        $aKlarnaData = array(
            'purchase_country' => $oUser->fcpoGetUserCountryIso(),
            'purchase_currency' => $oCur->name,
            'billing_address' => array(
                'given_name' => $oUser->oxuser__oxfname->value,
                'family_name' => $oUser->oxuser__oxlname->value,
                'email' => $oUser->oxuser__oxusername->value,
                'title' => $oUser->oxuser__oxsal->value,
                'street_address' => $oUser->oxuser__oxstreet->value . " " . $oUser->oxuser__oxstreetnr->value,
                'street_address2' => $oUser->oxuser__oxaddinfo->value,
                'postal_code' => $oUser->oxuser__oxzip->value,
                'city' => $oUser->oxuser__oxcity->value,
                'region' => $oUser->getStateTitle(),
                'phone' => $oUser->oxuser__oxfon->value,
                'country' => $oUser->fcpoGetUserCountryIso(),
                'organization_name' => $oUser->oxuser__oxcompany->value,
            ),
        );

        if ($blHasShipping) {
            $aKlarnaData['shipping_address'] = array(
                'given_name' => $oShippingAddress->oxaddress__oxfname->value,
                'family_name' => $oShippingAddress->oxaddress__oxlname->value,
                'email' => $oUser->oxuser__oxusername->value,
                'title' => $oShippingAddress->oxaddress__oxsal->value,
                'street_address' => $oShippingAddress->oxaddress__oxstreet->value . " " . $oShippingAddress->oxaddress__oxstreetnr->value,
                'street_address2' => $oShippingAddress->oxaddress__oxaddinfo->value,
                'postal_code' => $oShippingAddress->oxaddress__oxzip->value,
                'city' => $oShippingAddress->oxaddress__oxcity->value,
                'region' => $oUser->getStateTitle(),
                'phone' => $oShippingAddress->oxaddress__oxfon->value,
                'country' => $oShippingAddress->fcpoGetUserCountryIso(),
                'organization_name' => $oUser->oxaddress__oxcompany->value,
            );
        } else {
            $aKlarnaData['shipping_address'] = $aKlarnaData['billing_address'];
        }

        $aKlarnaData['customer'] = array(
            'date_of_birth' => ($oUser->oxuser__oxbirthdate->value === '0000-00-00') ? '' : $oUser->oxuser__oxbirthdate->value,
            'gender' => $sGender,
            'organization_registration_id' => $oUser->oxuser__oxustid->value,
        );

        if ($oUser->oxuser__oxcompany->value) {
            $aKlarnaData['customer']['organization_entity_type'] = 'OTHER';
        }

        $aOrderData = $this->_fcpoGetKlarnaOrderdata();

        return array_merge(
            $aKlarnaData,
            $aOrderData
        );
    }

    protected function _fcpogetUser()
    {
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $oBasket = $oSession->getBasket();
        $oUser = $oBasket->getUser();
        return $oUser;
    }

    /**
     * Returns an object with the shipping address.
     *
     * @param void
     * @return mixed false|object
     */
    protected function _fcpoGetShippingAddress()
    {
        if (!($sAddressId = $this->_oFcpoHelper->fcpoGetRequestParameter('deladrid'))) {
            $sAddressId = $this->_oFcpoHelper->fcpoGetSessionVariable('deladrid');
        }

        if (!$sAddressId) {
            return false;
        }

        $oShippingAddress = oxNew('oxaddress');
        $oShippingAddress->load($sAddressId);

        return $oShippingAddress;
    }

    /**
     * Returns and brings basket positions into appropriate form
     *
     * @param void
     * @return array
     */
    protected function _fcpoGetKlarnaOrderdata()
    {
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $oBasket = $oSession->getBasket();

        $dAmount = $oBasket->getPrice()->getBruttoPrice();
        $dTaxAmount = $oBasket->getPrice()->getVat();
        $aOrderData = array(
            'order_amount' => $dAmount,
            'order_tax_amount' => $dTaxAmount
        );

        foreach ($oBasket->getContents() as $oBasketItem) {
            $oArticle = $oBasketItem->getArticle();
            $aOrderData['orderlines'][] = array(
                'reference' => $oArticle->oxarticles__oxartnum->value,
                'name' =>  $oBasketItem->getTitle(),
                'quantity' => $oBasketItem->getAmount(),
                'unit_price' => $oBasketItem->getUnitPrice()->getBruttoPrice(),
                'tax_rate' => $oBasketItem->getVatPercent(),
                'total_amount' => $oBasketItem->getPrice()->getBruttoPrice(),
                // 'product_url' => $oBasketItem->getLink(),
                // 'image_url' => $oBasketItem->getIconUrl(),
            );
        }

        return $aOrderData;
    }

    /**
     * Removes params that are not used for this country.
     *
     * @param $aKlarnaData
     */
    protected function _fcpoRemoveKlarnaDataForCountry($aKlarnaData)
    {
        $oUser = $this->_fcpogetUser();
        $sCountryIso2 = $oUser->fcpoGetUserCountryIso();
        switch ($sCountryIso2) {
            case 'AT':
            case 'DE':
                unset(
                    $aKlarnaData['shipping_address'],
                    $aKlarnaData['order_amount'],
                    $aKlarnaData['order_tax_amount'],
                    $aKlarnaData['orderlines']
                );
                break;
            case 'DK':
                unset(
                    $aKlarnaData['billing_address']['title'],
                    $aKlarnaData['billing_address']['street_address_2'],
                    $aKlarnaData['shipping_address']['title'],
                    $aKlarnaData['shipping_address']['street_address_2'],
                    $aKlarnaData['customer']['date_of_birth']
                );
                break;
            case 'FI':
            case 'SE':
                unset(
                    $aKlarnaData['billing_address']['street_address_2'],
                    $aKlarnaData['billing_address']['region'],
                    $aKlarnaData['shipping_address']['street_address_2'],
                    $aKlarnaData['shipping_address']['region'],
                    $aKlarnaData['customer']['date_of_birth']
                );
                break;
            case 'GB':
            case 'US':
                break;
            case 'NL':
                unset(
                    $aKlarnaData['billing_address']['street_address_2'],
                    $aKlarnaData['shipping_address']['street_address_2']
                );
                break;
            case 'NO':
                unset(
                    $aKlarnaData['billing_address']['title'],
                    $aKlarnaData['shipping_address_address']['title'],
                    $aKlarnaData['customer']['date_of_birth']
                );
                break;
        }
        return $aKlarnaData;
    }

    /**
     * Returns the path to a text file with js for the klarna widget.
     *
     * @return string
     */
    protected function _fcpoGetKlarnaWidgetPath()
    {
        $oViewConf = $this->_oFcpoHelper->getFactoryObject('oxviewconfig');
        $sPath = $oViewConf->getModulePath('fcpayone') . '/out/snippets/fcpoKlarnaWidget.txt';
        return $sPath;
    }

}