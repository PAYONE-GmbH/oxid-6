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

/*
 * load OXID Framework
 */
if (!function_exists('getShopBasePath')) {
    function getShopBasePath()
    {
        return dirname(__FILE__).'/../../../../../';
    }
}

if (file_exists(getShopBasePath() . "/bootstrap.php") ) {
    include_once getShopBasePath() . "/bootstrap.php";
}
else {
    // global variables which are important for older OXID.
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['HTTP_USER_AGENT'] = 'payone_ajax';
    $_SERVER['HTTP_ACCEPT_LANGUAGE'] = '';
    $_SERVER['HTTP_REFERER'] = '';
    $_SERVER['QUERY_STRING'] = '';
    
    include getShopBasePath() . 'modules/functions.php';
    include_once getShopBasePath() . 'core/oxfunctions.php';
    include_once getShopBasePath() . 'views/oxubase.php';
}

// receive params
$sPaymentId = filter_input(INPUT_POST, 'paymentid');
$sAction = filter_input(INPUT_POST, 'action');
$sParamsJson = filter_input(INPUT_POST, 'params');

/**
 * Class for receiving ajax calls and delivering needed data
 *
 * @author andre
 */
class fcpayone_ajax extends oxBase
{
    
    /**
     * Helper object for dealing with different shop versions
     *
     * @var fcpohelper
     */
    protected $_oFcpoHelper = null;
    
    /**
     * init object construction
     * 
     * @return null
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
    }

    /**
     *
     *
     * @param $sPaymentId
     * @param $sAction
     * @param $sParamsJson
     * @return string
     */
    public function fcpoTriggerKlarnaAction($sPaymentId, $sAction, $sParamsJson)
    {
        if ($sAction === 'start_session') {
            return $this->fcpoTriggerKlarnaSessionStart($sPaymentId, $sParamsJson);
        }
    }

    /**
     * Trigger klarna session start
     *
     * @param $sPaymentId
     * @param $sParamsJson
     * @return string
     */
    public function fcpoTriggerKlarnaSessionStart($sPaymentId, $sParamsJson)
    {
        $oRequest = $this->_oFcpoHelper->getFactoryObject('fcporequest');
        $aResponse = $oRequest->sendRequestKlarnaStartSession($sPaymentId);
        $blIsValid = (
            isset($aResponse['status'], $aResponse['add_paydata[client_token]']) &&
            $aResponse['status'] === 'OK'
        );

        if (!$blIsValid) {
            $this->_oFcpoHelper->fcpoSetSessionVariable('payerror', -20);
            $this->_oFcpoHelper->fcpoSetSessionVariable(
                'payerrortext',
                $aResponse['errormessage']
            );
            return header("HTTP/1.0 503 Service not available");
        }

        $this->_fcpoSetKlarnaSessionParams($aResponse);

        return $this->_fcpoGetKlarnaWidgetJS($aResponse['add_paydata[client_token]'], $sParamsJson);
    }

    /**
     * Set needed session params for later handling of Klarna payment
     *
     * @param $aResponse
     * @return void
     */
    protected function _fcpoSetKlarnaSessionParams($aResponse)
    {
        $this->_oFcpoHelper->fcpoDeleteSessionVariable('klarna_authorization_token');
        $this->_oFcpoHelper->fcpoSetSessionVariable(
            'klarna_authorization_token',
            $aResponse['add_paydata[session_id]']
        );
        $this->_oFcpoHelper->fcpoDeleteSessionVariable('fcpoWorkorderId');
        $this->_oFcpoHelper->fcpoSetSessionVariable(
            'fcpoWorkorderId',
            $aResponse['workorderid']
        );
    }

    /**
     * Triggers a call on payoneapi for handling ajax calls for referencedetails
     *
     * @param $sParamsJson
     * @return void
     */
    public function fcpoGetAmazonReferenceId($sParamsJson)
    {
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $aParams = json_decode($sParamsJson, true);
        $sAmazonReferenceId = $aParams['fcpoAmazonReferenceId'];
        $oSession->deleteVariable('fcpoAmazonReferenceId');
        $oSession->setVariable('fcpoAmazonReferenceId', $sAmazonReferenceId);
        $sAmazonLoginAccessToken = $oSession->getVariable('sAmazonLoginAccessToken');

        // do the call cascade
        $this->_fcpoHandleGetOrderReferenceDetails($sAmazonReferenceId, $sAmazonLoginAccessToken);
        $this->_fcpoHandleSetOrderReferenceDetails($sAmazonReferenceId, $sAmazonLoginAccessToken);
    }

    /**
     *
     *
     * @param $sParamsJson
     * @return void
     */
    public function fcpoConfirmAmazonPayOrder($sParamsJson)
    {
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $aParams = json_decode($sParamsJson, true);
        $sAmazonReferenceId = $aParams['fcpoAmazonReferenceId'];
        $sToken = $aParams['fcpoAmazonStoken'];
        $sDeliveryMD5 = $aParams['fcpoAmazonDeliveryMD5'];

        $oSession->deleteVariable('fcpoAmazonReferenceId');
        $oSession->setVariable('fcpoAmazonReferenceId', $sAmazonReferenceId);

        $this->_fcpoHandleConfirmAmazonPayOrder($sAmazonReferenceId, $sToken, $sDeliveryMD5);
    }

    /**
     * @param $sClientToken
     * @param $sParamsJson
     * @return string
     */
    protected function _fcpoGetKlarnaWidgetJS($sClientToken, $sParamsJson)
    {
        $aParams = json_decode($sParamsJson, true);
        $aKlarnaData = $this->_fcpoGetKlarnaData();
        $aKlarnaOrderData = $this->_fcpoGetKlarnaOrderdata();
        $aKlarnaBasket = $aKlarnaOrderData['basket'];
        $aKlarnaOrderlines = $aKlarnaOrderData['orderlines'];

        $aKlarnaWidgetSearch = array(
            '%%TOKEN%%',
            '%%PAYMENT_CONTAINER_ID%%',
            '%%PAYMENT_CATEGORY%%',
            '%%KLARNA_DATA%%',
            '%%KLARNA_BASKET%%',
            '%%KLARNA_ORDERLINES%%',
        );

        $aKlarnaWidgetReplace = array(
            $sClientToken,
            $aParams['payment_container_id'],
            $aParams['payment_category'],
            json_encode($aKlarnaData),
            json_encode($aKlarnaBasket),
            json_encode($aKlarnaOrderlines),
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
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $oBasket = $oSession->getBasket();
        $oUser = $oBasket->getUser();
        $oShippingAddress = $this->_fcpoGetShippingAddress();
        $oCur = $oCur = $oConfig->getActShopCurrencyObject();
        $blHasShipping = (!$oShippingAddress) ? false : true;
        $sGender = ($oUser->oxuser__oxsal->value == 'MR') ? 'male' : 'female';

        $aKlarnaData = array(
            'purchase_country' => $oUser->fcpoGetUserCountryIso(),
            'purchase_currency' => $oCur->name,
            'billing' => array(
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
            $aKlarnaShippingData = array(
                'shipping' => array(
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
                ),
            );
        } else {
            $aKlarnaShippingData = array(
                'shipping' => $aKlarnaData['billing']
            );
        }

        $aKlarnaCustomer = array(
            'customer' => array(
                'date_of_birth' => ($oUser->oxuser__oxbirthdate->value === '0000-00-00') ? '' : $oUser->oxuser__oxbirthdate->value,
                'gender' => $sGender,
                'organization_entity_type' => '',
                'organization_registration_id' => $oUser->oxuser__oxustid->value,
            )
        );

        if ($oUser->oxuser__oxcompany->value) {
            $aKlarnaCustomer['customer']['organization_entity_type'] = 'OTHER';
        }

        $aKlarnaData = array_merge(
            $aKlarnaData,
            $aKlarnaShippingData,
            $aKlarnaCustomer
        );

        return $aKlarnaData;
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
        $aBasketData = array(
            'order_amount' => $dAmount,
            'order_tax_amount' => $dTaxAmount
        );

        $aOrderlines = array();
        foreach ($oBasket->getContents() as $oBasketItem) {
            $oArticle = $oBasketItem->getArticle();
            $aOrderline = array(
                'reference' => $oArticle->oxarticles__oxartnum->value,
                'name' =>  $oBasketItem->getTitle(),
                'quantity' => $oBasketItem->getAmount(),
                'unit_price' => $oBasketItem->getUnitPrice()->getBruttoPrice(),
                'tax_rate' => $oBasketItem->getVatPercent(),
                'total_amount' => $oBasketItem->getPrice()->getBruttoPrice(),
                // 'product_url' => $oBasketItem->getLink(),
                // 'image_url' => $oBasketItem->getIconUrl(),
            );
            $aOrderlines[] = $aOrderline;
        }

        return array(
            'basket' => $aBasketData,
            'orderlines' => $aOrderlines
        );
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
     * Returns the path to a text file with js for the klarna widget.
     *
     * @return string
     */
    protected function _fcpoGetKlarnaWidgetPath()
    {
        $oViewConf = $this->_oFcpoHelper->getFactoryObject('oxviewconfig');
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $oBasket = $oSession->getBasket();
        $oUser = $oBasket->getUser();
        $sCountryIso2 = $oUser->fcpoGetUserCountryIso();

        $sFileName = "fcpoKlarnaWidget_".$sCountryIso2.".txt";

        $sPath =
            $oViewConf->getModulePath('fcpayone') . '/out/snippets/'.$sFileName;

        return $sPath;
    }

    /**
     * Calls confirmorderreference call. Sends a 404 on invalid state
     *
     * @param $sAmazonReferenceId
     * @param $sToken
     */
    protected function _fcpoHandleConfirmAmazonPayOrder($sAmazonReferenceId, $sToken, $sDeliveryMD5)
    {
        $oRequest = $this->_oFcpoHelper->getFactoryObject('fcporequest');

        $aResponse =
            $oRequest->sendRequestGetConfirmAmazonPayOrder($sAmazonReferenceId, $sToken, $sDeliveryMD5);

        $blSend400 = (
            isset($aResponse['status']) &&
            $aResponse['status'] != 'OK'
        );

        if ($blSend400) return header("HTTP/1.0 404 Not Found");

        header("HTTP/1.0 200 Ok");
    }

    /**
     * Triggers call setorderreferencedetails
     *
     * @param $sAmazonReferenceId
     * @param $sAmazonLoginAccessToken
     * @return void
     */
    protected function _fcpoHandleSetOrderReferenceDetails($sAmazonReferenceId, $sAmazonLoginAccessToken)
    {
        $oUtils = $this->_oFcpoHelper->fcpoGetUtils();
        $oRequest = $this->_oFcpoHelper->getFactoryObject('fcporequest');
        $sWorkorderId = $this->_oFcpoHelper->fcpoGetSessionVariable('fcpoAmazonWorkorderId');

        $aResponse = $oRequest->sendRequestSetAmazonOrderReferenceDetails($sAmazonReferenceId, $sAmazonLoginAccessToken, $sWorkorderId);

        if ($aResponse['status'] == 'OK') {
            $oUser = $this->_oFcpoHelper->getFactoryObject('oxuser');
            $oUser->fcpoSetAmazonOrderReferenceDetailsResponse($aResponse);
        } else {
            $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
            $sShopUrl = $oConfig->getShopUrl();
            $oUtils->redirect($sShopUrl . "index.php?cl=basket");
        }
    }

    /**
     * Triggers call getorderreferencedetails
     *
     * @param $sAmazonReferenceId
     * @param $sAmazonLoginAccessToken
     * @return void
     */
    protected function _fcpoHandleGetOrderReferenceDetails($sAmazonReferenceId, $sAmazonLoginAccessToken)
    {
        $oUtils = $this->_oFcpoHelper->fcpoGetUtils();
        $oRequest = $this->_oFcpoHelper->getFactoryObject('fcporequest');

        $aResponse = $oRequest->sendRequestGetAmazonOrderReferenceDetails($sAmazonReferenceId, $sAmazonLoginAccessToken);

        if ($aResponse['status'] == 'OK') {
            $this->_oFcpoHelper->fcpoDeleteSessionVariable('fcpoAmazonWorkorderId');
            $this->_oFcpoHelper->fcpoSetSessionVariable('fcpoAmazonWorkorderId', $aResponse['workorderid']);
            $this->_oFcpoHelper->fcpoDeleteSessionVariable('paymentid');
            $this->_oFcpoHelper->fcpoSetSessionVariable('paymentid', 'fcpoamazonpay');
        } else {
            $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
            $sShopUrl = $oConfig->getShopUrl();
            $oUtils->redirect($sShopUrl . "index.php?cl=basket");
        }
    }


    /**
     * Performs a precheck for payolution installment
     * 
     * @param  type $sPaymentId
     * @return bool
     */
    public function fcpoTriggerPrecheck($sPaymentId, $sParamsJson) 
    {
        $oPaymentController = $this->_oFcpoHelper->getFactoryObject('payment');
        $oPaymentController->setPayolutionAjaxParams(json_decode($sParamsJson, true));
        $mPreCheckResult =  $oPaymentController->fcpoPayolutionPreCheck($sPaymentId);
        $sReturn = ($mPreCheckResult === true) ? 'SUCCESS': $mPreCheckResult;
        
        return $sReturn;
    }

    /**
     * Performs a precheck for payolution installment
     *
     * @param string $sPaymentId
     * @return mixed
     */
    public function fcpoTriggerInstallmentCalculation($sPaymentId)
    {
        $oPaymentController = $this->_oFcpoHelper->getFactoryObject('payment');

        $oPaymentController->fcpoPerformInstallmentCalculation($sPaymentId);
        $mResult = $oPaymentController->fcpoGetInstallments();
        
        $mReturn = (is_array($mResult) && count($mResult) > 0) ? $mResult : false;
        
        return $mReturn;
    }
    
    /**
     * Parse result of calculation to html for returning html code
     * 
     * @param  array $aCalculation
     * @return string
     */
    public function fcpoParseCalculation2Html($aCalculation) 
    {
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        
        $sTranslateInstallmentSelection = utf8_encode($oLang->translateString('FCPO_PAYOLUTION_INSTALLMENT_SELECTION'));
        $sTranslateSelectInstallment = utf8_encode($oLang->translateString('FCPO_PAYOLUTION_SELECT_INSTALLMENT'));
        
        $oConfig = $this->getConfig();
        $sHtml = '
            <div class="content">
                <p id="payolution_installment_calculation_headline" class="payolution_installment_box_headline">2. '.$sTranslateInstallmentSelection.'</p>
                <p id="payolution_installment_calculation_headline" class="payolution_installment_box_subtitle">'.$sTranslateSelectInstallment.'</p>
        ';
        $sHtml .= '<div class="payolution_installment_offers">';
        $sHtml .= '<input id="payolution_no_installments" type="hidden" value="'.count($aCalculation).'">';
        $sHtml .= '<fieldset>';
        foreach ($aCalculation as $sKey=>$aCurrentInstallment) {
            $sHtml .= $this->_fcpoGetInsterestHiddenFields($sKey, $aCurrentInstallment);
            $sHtml .= $this->_fcpoGetInsterestRadio($sKey, $aCurrentInstallment);
            $sHtml .= $this->_fcpoGetInsterestLabel($sKey, $aCurrentInstallment);
            $sHtml .= '<br>';
        }
        $sHtml .= '</fieldset>';
        $sHtml .= '</div></div>';
        $sHtml .= '<div class="payolution_installment_details">';
        foreach ($aCalculation as $sKey=>$aCurrentInstallment) {
            $sHtml .= '<div id="payolution_rates_details_'.$sKey.'" class="payolution_rates_invisible">';
            foreach ($aCurrentInstallment['Months'] as $sMonth=>$aRatesDetails) {
                $sHtml .= $this->_fcpoGetInsterestMonthDetail($sMonth, $aRatesDetails).'<br>';
            }
            $sDownloadUrl = $oConfig->getShopUrl().'/modules/fc/fcpayone/lib/fcpopopup_content.php?login=1&loadurl='.$aCurrentInstallment['StandardCreditInformationUrl'];
            $sHtml .= '</div>';

        }
        $sHtml .= '</div>';
        $sHtml .= '<div class="payolution_draft_download"><a href="'.$sDownloadUrl.'"'.$this->_fcpoGetLightView().'>'.$oLang->translateString('FCPO_PAYOLUTION_INSTALLMENT_DOWNLOAD_DRAFT').'</a></div>';

        return $sHtml;
    }
    
    /**
     * Returns lightview part for download
     * 
     * @param  void
     * @return string
     */
    protected function _fcpoGetLightView() 
    {
        $sContent = 'class="lightview" data-lightview-type="iframe" data-lightview-options="';
        $sContent .= "width: 800, height: 600, viewport: 'scale',background: { color: '#fff', opacity: 1 },skin: 'light'";
        $sContent .= '"';
        
        return $sContent;
    }
    
    
    /**
     * Formats error message to be displayed in a error box
     * 
     * @param  string $sMessage
     * @return string
     */
    public function fcpoReturnErrorMessage($sMessage) 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        if (!$oConfig->isUtf()) {
            $sMessage = utf8_encode($sMessage);
        }
        
        $sReturn  = '<p class="payolution_message_error">';
        $sReturn .= $sMessage;
        $sReturn .= '</p>';
        
        return $sReturn;
    }
    
    
    /**
     * Set hidden fields for beeing able to set needed values
     * 
     * @param  string $sKey
     * @param  array  $aCurrentInstallment
     * @return string
     */
    protected function _fcpoGetInsterestHiddenFields($sKey, $aCurrentInstallment) 
    {
        $sHtml  = '<input type="hidden" id="payolution_installment_value_'.$sKey.'" value="'.str_replace('.', ',', $aCurrentInstallment['Amount']).'">';
        $sHtml .= '<input type="hidden" id="payolution_installment_duration_'.$sKey.'" value="'.$aCurrentInstallment['Duration'].'">';
        $sHtml .= '<input type="hidden" id="payolution_installment_eff_interest_rate_'.$sKey.'" value="'.str_replace('.', ',', $aCurrentInstallment['EffectiveInterestRate']).'">';
        $sHtml .= '<input type="hidden" id="payolution_installment_interest_rate_'.$sKey.'" value="'.str_replace('.', ',', $aCurrentInstallment['InterestRate']).'">';
        $sHtml .= '<input type="hidden" id="payolution_installment_total_amount_'.$sKey.'" value="'.str_replace('.', ',', $aCurrentInstallment['TotalAmount']).'">';

        return $sHtml;
    }
    
    /**
     * Returns a caption for a certain month
     * 
     * @param  string $sMonth
     * @param  array  $aRatesDetails
     * @return string
     */
    protected function _fcpoGetInsterestMonthDetail($sMonth, $aRatesDetails) 
    {
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $sRateCaption = $oLang->translateString('FCPO_PAYOLUTION_INSTALLMENT_RATE');
        $sDueCaption = utf8_encode($oLang->translateString('FCPO_PAYOLUTION_INSTALLMENT_DUE_AT'));
        $sDue = date('d.m.Y', strtotime($aRatesDetails['Due']));
        $sRate = str_replace('.', ',', $aRatesDetails['Amount']);
        
        $sMonthDetailsCaption = $sMonth.'. '.$sRateCaption.': '. $sRate.' '.$aRatesDetails['Currency'].' ('.$sDueCaption.' '.$sDue.')';
        
        return $sMonthDetailsCaption;
    }
    
    /**
     * Returns a html radio button for current installment offer
     * 
     * @param  string $sKey
     * @param  array  $aCurrentInstallment
     * @return string
     */
    protected function _fcpoGetInsterestRadio($sKey, $aCurrentInstallment) 
    {
        $sHtml = '<input type="radio" id="payolution_installment_offer_'.$sKey.'" name="payolution_installment_selection" value="'.$sKey.'">';
        
        return $sHtml;
    }
    
    /**
     * Returns a html label for current installment offer radiobutton
     * 
     * @param  string $sKey
     * @param  array  $aCurrentInstallment
     * @return string
     */
    protected function _fcpoGetInsterestLabel($sKey, $aCurrentInstallment) 
    {
        $sInterestCaption = $this->_fcpoGetInsterestCaption($aCurrentInstallment);
        $sHtml = '<label for="payolution_installment_offer_'.$sKey.'">'.$sInterestCaption.'</label>';

        return $sHtml;
    }

    /**
     * Returns translated caption for current installment offer
     * 
     * @param  array $aCurrentInstallment
     * @return string
     */
    protected function _fcpoGetInsterestCaption($aCurrentInstallment) 
    {
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $sPerMonth = $oLang->translateString('FCPO_PAYOLUTION_INSTALLMENT_PER_MONTH');
        $sRates = $oLang->translateString('FCPO_PAYOLUTION_INSTALLMENT_RATES');
        $sMonthlyAmount = str_replace('.', ',', $aCurrentInstallment['Amount']);
        $sDuration = $aCurrentInstallment['Duration'];
        $sCurrency = $aCurrentInstallment['Currency'];
        
        // put all together to final caption
        $sCaption = $sMonthlyAmount." ".$sCurrency." ".$sPerMonth." - ".$sDuration." ".$sRates;
        
        return $sCaption;
    }
}


if ($sPaymentId) {
    $oPayoneAjax = new fcpayone_ajax();
    if ($sAction == 'precheck') {
        $sResult =  $oPayoneAjax->fcpoTriggerPrecheck($sPaymentId, $sParamsJson);
        if ($sResult == 'SUCCESS') {
            $sAction = 'calculation';
        }
        else {
            echo $oPayoneAjax->fcpoReturnErrorMessage($sResult);
        }
    }
    
    if ($sAction == 'calculation') {
        $mResult = $oPayoneAjax->fcpoTriggerInstallmentCalculation($sPaymentId);
        if (is_array($mResult) && count($mResult) > 0) {
            // we have got a calculation result. Parse it to needed html
            echo $oPayoneAjax->fcpoParseCalculation2Html($mResult);
        }
    }

    if ($sAction == 'get_amazon_reference_details' && $sPaymentId == 'fcpoamazonpay') {
        $oPayoneAjax->fcpoGetAmazonReferenceId($sParamsJson);
    }


    $blConfirmAmazonOrder = (
        $sAction == 'confirm_amazon_pay_order' &&
        $sPaymentId == 'fcpoamazonpay'
    );
    if ($blConfirmAmazonOrder) {
        $oPayoneAjax->fcpoConfirmAmazonPayOrder($sParamsJson);
    }

    $aKlarnaPayments = array(
        'fcpoklarna_invoice',
        'fcpoklarna_installments',
        'fcpoklarna_directdebit',
    );
    if (in_array($sPaymentId, $aKlarnaPayments)) {
        echo $oPayoneAjax->fcpoTriggerKlarnaAction($sPaymentId, $sAction, $sParamsJson);
    }
}