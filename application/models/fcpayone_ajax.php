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
     * @param  type $sPaymentId
     * @return mixed
     */
    public function fcpoTriggerInstallmentCalculation()
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
        $sHtml .= '<input type="radio" id="payolution_installment_offer_'.$sKey.'" name="payolution_installment_selection" value="'.$sKey.'">';
        
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
        $mResult = $oPayoneAjax->fcpoTriggerInstallmentCalculation();
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
}