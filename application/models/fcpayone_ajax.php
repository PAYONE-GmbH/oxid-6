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

use OxidEsales\Eshop\Core\Field;

if (!function_exists('getShopBasePath')) {
    function getShopBasePath()
    {
        return dirname(__FILE__).'/../../../../../';
    }
}

if (file_exists(getShopBasePath() . "bootstrap.php") ) {
    include_once getShopBasePath() . "bootstrap.php";
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
        $this->_fcpoUpdateUser($sParamsJson);
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

        $oParamsParser = $this->_oFcpoHelper->getFactoryObject('fcpoparamsparser');
        $sKlarnaWidgetJS =
            $oParamsParser->fcpoGetKlarnaWidgetJS(
                $aResponse['add_paydata[client_token]'],
                $sParamsJson
            );

        return $sKlarnaWidgetJS;
    }

    /**
     *
     *
     * @param $sParamsJson
     * @return string
     */
    public function _fcpoUpdateUser($sParamsJson)
    {
        $aParams = json_decode($sParamsJson, true);
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $oBasket = $oSession->getBasket();
        $oUser = $oBasket->getUser();
        /** @var oxUser $oUser value */
        if ($aParams['birthday'] !== 'undefined') {
            $oUser->oxuser__oxbirthdate = new Field($aParams['birthday']);
        }
        if ($aParams['telephone'] !== 'undefined') {
            $oUser->oxuser__oxfon = new Field($aParams['telephone']);
        }
        if ($aParams['personalid'] !== 'undefined') {
            $oUser->oxuser__fcpopersonalid = new Field($aParams['personalid']);
        }
        $oUser->save();
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
        $this->_oFcpoHelper->fcpoDeleteSessionVariable('klarna_client_token');
        $this->_oFcpoHelper->fcpoSetSessionVariable(
            'klarna_client_token',
            $aResponse['add_paydata[client_token]']
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

    public function fcpoAplRegisterDevice($sParamsJson)
    {
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $aParams = json_decode($sParamsJson, true);

        $allowedDevice = $aParams['allowed'];
        $oSession->setVariable('applePayAllowedDevice', $allowedDevice);
        return json_encode(['status' => 'SUCCESS', 'message' => '']);
    }

    public function fcpoAplCreateSession($sParamsJson)
    {
        $logger = \OxidEsales\Eshop\Core\Registry::getLogger();
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $aParams = json_decode($sParamsJson, true);

        /** @var oxviewconfig $config */
        $oViewConfig = $this->_oFcpoHelper->fcpoGetViewConfig();
        /** @var  \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();

        $certDir =  $oViewConfig->fcpoGetCertDirPath();
        $shopFQDN = $_SERVER['SERVER_NAME'];
        $validationUrl = $aParams['validationUrl'];

        try {
            $merchantId = $oConfig->getConfigParam('sFCPOAplMerchantId');
            $certificateFileName = $oConfig->getConfigParam('sFCPOAplCertificate');
            $keyFileName = $oConfig->getConfigParam('sFCPOAplKey');
            $keyPassword = $oConfig->getConfigParam('sFCPOAplPassword');

            $payload = [
                'merchantIdentifier' => $merchantId,
                'displayName' => 'PAYONE Apple Pay',
                'initiative' => 'web',
                'initiativeContext' => $shopFQDN
            ];

            $httpClient = new \OxidEsales\Eshop\Core\Curl();
            $httpClient->setUrl($validationUrl);
            $httpClient->setMethod('POST');
            $httpClient->setOption('CURLOPT_SSLCERT', $certDir . $certificateFileName);
            $httpClient->setOption('CURLOPT_SSLKEY', $certDir . $keyFileName);
            $httpClient->setOption('CURLOPT_SSLKEYPASSWD', $keyPassword);
            $httpClient->setOption('CURLOPT_POSTFIELDS', json_encode($payload));
            $httpResponse = $httpClient->execute();
            $status = $httpClient->getStatusCode();

            if ($status !== 200) {
                $logger->error($oLang->translateString('FCPO_APPLE_PAY_CREATE_SESSION_ERROR') . ' : ' . var_export($httpResponse, true));

                $response = [
                    'status' => 'ERROR',
                    'message' => $oLang->translateString('FCPO_APPLE_PAY_CREATE_SESSION_ERROR'),
                    'errorDetails' => $httpResponse
                ];

                return json_encode($response);
            }

            $merchantSession = json_decode($httpResponse, true);
            $response = [
                'status' => 'SUCCESS',
                'message' => '',
                'merchantSession' => $merchantSession
            ];

            return json_encode($response);

        } catch (\Exception $e) {
            $logger->error($e->getTraceAsString());

            $response = [
                'status' => 'ERROR',
                'message' => $oLang->translateString('FCPO_APPLE_PAY_CREATE_SESSION_ERROR'),
                'errorDetails' => $e->getMessage()
            ];

            return json_encode($response);
        }
    }

    public function fcpoAplPayment($sParamsJson)
    {
        $aCreditCardMapping = array(
            'visa' => 'V',
            'mastercard' => 'M',
            'amex' => 'M',
            'discover' => 'D'
        );

        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $aParams = json_decode($sParamsJson, true);

        $paymentData = $aParams['token']['paymentData'];
        $methodData = $aParams['token']['paymentMethod'];
        $creditCardType = '';
        if (isset($aCreditCardMapping[strtolower($methodData['network'])])) {
            $creditCardType = $aCreditCardMapping[strtolower($methodData['network'])];
        }

        $tokenData = [
            'paydata' => [
                'paymentdata_token_data' => isset($paymentData['data']) ? $paymentData['data'] : '',
                'paymentdata_token_ephemeral_publickey' => isset($paymentData['header']['ephemeralPublicKey']) ? $paymentData['header']['ephemeralPublicKey'] : '',
                'paymentdata_token_publickey_hash' => isset($paymentData['header']['publicKeyHash']) ? $paymentData['header']['publicKeyHash'] : '',
                'paymentdata_token_transaction_id' => isset($paymentData['header']['transactionId']) ? $paymentData['header']['transactionId'] : '',
                'paymentdata_token_signature' => isset($paymentData['signature']) ? $paymentData['signature'] : '',
                'paymentdata_token_version' => isset($paymentData['version']) ? $paymentData['version'] : ''
            ],
            'creditCardType' => $creditCardType
        ];

        $oSession->setVariable('applePayTokenData', $tokenData);

        $response = [
            'status' => 'SUCCESS',
            'message' => ''
        ];

        return json_encode($response);
    }

    public function fcpoAplOrderInfo()
    {
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $oBasket = $oSession->getBasket();
        $sPaymentId = $oBasket->getPaymentId();
        $sAmount = $oBasket->getPrice()->getPrice();

        $oCurr = $oConfig->getActShopCurrencyObject();
        $sCurrency = $oCurr->name;

        /** @var \OxidEsales\Eshop\Application\Model\Country $oCountry */
        $oCountry = $this->_oFcpoHelper->getFactoryObject('oxcountry');
        $oCountry->load($oBasket->getBasketUser()->getActiveCountry());
        $sCountry = $oCountry->oxcountry__oxisoalpha2->value;

        $aCreditCardMapping = [
            'V' => "visa",
            'M' => "masterCard",
            'A' => "amex",
            'D' => "discover"
        ];

        $aSupportedNetwork = [];
        $aConfigAplCreditCard = $oConfig->getConfigParam('aFCPOAplCreditCards');
        if (!empty($aConfigAplCreditCard)) {
            foreach ($oConfig->getConfigParam('aFCPOAplCreditCards') as $sCardCode) {
                if (isset($aCreditCardMapping[$sCardCode])) {
                    $aSupportedNetwork[] = $aCreditCardMapping[$sCardCode];
                }
            }
        }

        $response = [
            'status' => 'SUCCESS',
            'message' => '',
            'info' => [
                'isApl' => ($sPaymentId == 'fcpo_apple_pay') ? true : false,
                'amount' => $sAmount,
                'currency' => $sCurrency,
                'country' => $sCountry,
                'supportedNetworks' => $aSupportedNetwork,
                'errorMessage' => ''
            ]
        ];

        if (count($aSupportedNetwork) < 1) {
            $response['info']['errorMessage'] = $oLang->translateString('FCPO_APPLE_PAY_CREATE_SESSION_ERROR_CARDS');
        }

        return json_encode($response);
    }

    public function fcpoRatepayCalculation($sParamsJson)
    {
        $aParams = json_decode($sParamsJson, true);
        $sOxid = $aParams['sPaymentMethodOxid'];

        $oRatePay = oxNew('fcporatepay');
        $aRatepayData = $oRatePay->fcpoGetProfileData($sOxid);

        if ($aParams['sMode'] == 'runtime') {
            $sCalculationMode = 'calculation-by-time';
            $aRatepayData['duration'] = $aParams['iMonth'];
        } else {
            $sCalculationMode = 'calculation-by-rate';
            $aRatepayData['installment'] = $aParams['iInstallment'];
        }

        $oRequest = $this->_oFcpoHelper->getFactoryObject('fcporequest');
        $aResponse = $oRequest->sendRequestRatepayCalculation($sCalculationMode, $aRatepayData);

        if (is_array($aResponse) && array_key_exists('workorderid', $aResponse) !== false) {
            $this->_oFcpoHelper->fcpoSetSessionVariable('ratepay_workorderid', $aResponse['workorderid']);
        }

        $aInstallmentDetails = [
            'annualPercentageRate' => $aResponse['add_paydata[annual-percentage-rate]'],
            'interestAmount' => $aResponse['add_paydata[interest-amount]'],
            'amount' => $aResponse['add_paydata[amount]'],
            'numberOfRate' => $aResponse['add_paydata[number-of-rates]'],
            'numberOfRatesFull' => $aResponse['add_paydata[number-of-rates]'],
            'rate' => $aResponse['add_paydata[rate]'],
            'paymentFirstday' => $aResponse['add_paydata[payment-firstday]'],
            'interestRate' => $aResponse['add_paydata[interest-rate]'],
            'monthlyDebitInterest' => $aResponse['add_paydata[monthly-debit-interest]'],
            'lastRate' => $aResponse['add_paydata[last-rate]'],
            'serviceCharge' => $aResponse['add_paydata[service-charge]'],
            'totalAmount' => $aResponse['add_paydata[total-amount]'],
        ];

        if ($aInstallmentDetails['lastRate'] < $aInstallmentDetails['rate']) {
            $aInstallmentDetails['numberOfRate'] = $aInstallmentDetails['numberOfRate']-1;
        }

        $code = $this->_generateTranslatedResultCode($aRatepayData, $aInstallmentDetails);

        $sHtml = $this->_parseRatepayRateDetails($aRatepayData['OXPAYMENTID'], $aInstallmentDetails, $code);

        return $sHtml;
    }

    protected function _generateTranslatedResultCode($aRatepayData, $aInstallmentDetails)
    {
        if (isset($aRatepayData['installment']) && $aRatepayData['installment'] < $aInstallmentDetails['rate']) {
            return 'RATE_INCREASED';
        }
        if (isset($aRatepayData['installment']) && $aRatepayData['installment'] > $aInstallmentDetails['rate']) {
            return 'RATE_REDUCED';
        }

        return 603;
    }

    protected function _parseRatepayRateDetails($sPaymentMethod, $aInstallmentDetails, $iCode)
    {
        $oLang = $this->_oFcpoHelper->fcpoGetLang();

        $sHtml = '<div class="rp-table-striped">';
        $sHtml .= '    <div>';
        $sHtml .= '        <div class="text-center text-uppercase" colspan="2">' . $oLang->translateString('FCPO_RATEPAY_CALCULATION_DETAILS_TITLE') . '</div>';
        $sHtml .= '    </div>';

        $sHtml .= '    <div>';
        $sHtml .= '        <div class="warning small text-center" colspan="2">' . $oLang->translateString('FCPO_RATEPAY_CALCULATION_DETAILS_CODE_TRANSLATION_' . $iCode) . '<br/>' . $oLang->translateString('FCPO_RATEPAY_CALCULATION_DETAILS_EXAMPLE') . '</div>';
        $sHtml .= '    </div>';

        $sHtml .= '    <div class="rp-menue">';
        $sHtml .= '        <div colspan="2" class="small text-right">';
        $sHtml .= '             <a class="rp-link" id="' . $sPaymentMethod . '_rp-show-installment-plan-details" onclick="fcpoRpChangeDetails(\'' . $sPaymentMethod . '\')">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_DETAILS_SHOW');
        $sHtml .= '                <img src="modules/fc/fcpayone/out/img/icon-enlarge.png" class="rp-details-icon" />';
        $sHtml .= '            </a>';
        $sHtml .= '             <a class="rp-link" id="' . $sPaymentMethod . '_rp-hide-installment-plan-details" onclick="fcpoRpChangeDetails(\'' . $sPaymentMethod . '\')">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_DETAILS_HIDE');
        $sHtml .= '                <img src="modules/fc/fcpayone/out/img/icon-shrink.png" class="rp-details-icon" />';
        $sHtml .= '            </a>';
        $sHtml .= '        </div>';
        $sHtml .= '    </div>';

        $sHtml .= '    <div id="' . $sPaymentMethod . '_rp-installment-plan-details">';
        $sHtml .= '        <div class="rp-installment-plan-details">';
        $sHtml .= '             <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver(\'' . $sPaymentMethod . '_amount\')" onmouseout="fcpoMouseOut(\'' . $sPaymentMethod . '_amount\')">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_DETAILS_PRICE_LABEL') . '&nbsp;';
        $sHtml .= '                 <p id="' . $sPaymentMethod . '_amount" class="rp-installment-plan-description small">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_DETAILS_PRICE_DESC');
        $sHtml .= '                </p>';
        $sHtml .= '            </div>';
        $sHtml .= '            <div class="text-right">';
        $sHtml .= $aInstallmentDetails['amount'];
        $sHtml .= '            </div>';
        $sHtml .= '        </div>';

        $sHtml .= '        <div class="rp-installment-plan-details">';
        $sHtml .= '            <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver(\'' . $sPaymentMethod . '_serviceCharge\')" onmouseout="fcpoMouseOut(\'' . $sPaymentMethod . '_serviceCharge\')">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_DETAILS_SERVICE_CHARGE_LABEL') . '&nbsp;';
        $sHtml .= '                <p id="' . $sPaymentMethod . '_serviceCharge" class="rp-installment-plan-description small">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_DETAILS_SERVICE_CHARGE_DESC');
        $sHtml .= '                </p>';
        $sHtml .= '            </div>';
        $sHtml .= '            <div class="text-right">';
        $sHtml .= $aInstallmentDetails['serviceCharge'];
        $sHtml .= '            </div>';
        $sHtml .= '        </div>';

        $sHtml .= '        <div class="rp-installment-plan-details">';
        $sHtml .= '            <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver(\'' . $sPaymentMethod . '_annualPercentageRate\')" onmouseout="fcpoMouseOut(\'' . $sPaymentMethod . '_annualPercentageRate\')">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_EFFECTIVE_RATE_LABEL') . '&nbsp;';
        $sHtml .= '                <p id="' . $sPaymentMethod . '_annualPercentageRate" class="rp-installment-plan-description small">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_EFFECTIVE_RATE_DESC');
        $sHtml .= '                </p>';
        $sHtml .= '            </div>';
        $sHtml .= '            <div class="text-right">';
        $sHtml .= $aInstallmentDetails['annualPercentageRate'];
        $sHtml .= '            </div>';
        $sHtml .= '        </div>';

        $sHtml .= '        <div class="rp-installment-plan-details">';
        $sHtml .= '            <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver(\'' . $sPaymentMethod . '_interestRate\')" onmouseout="fcpoMouseOut(\'' . $sPaymentMethod . '_interestRate\')">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_DEBIT_RATE_LABEL') . '&nbsp;';
        $sHtml .= '                <p id="' . $sPaymentMethod . '_interestRate" class="rp-installment-plan-description small">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_DEBIT_RATE_DESC');
        $sHtml .= '                </p>';
        $sHtml .= '            </div>';
        $sHtml .= '            <div class="text-right">';
        $sHtml .= $aInstallmentDetails['interestRate'];
        $sHtml .= '            </div>';
        $sHtml .= '        </div>';

        $sHtml .= '        <div class="rp-installment-plan-details">';
        $sHtml .= '            <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver(\'' . $sPaymentMethod . '_interestAmount\')" onmouseout="fcpoMouseOut(\'' . $sPaymentMethod . '_interestAmount\')">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_INTEREST_AMOUNT_LABEL') . '&nbsp;';
        $sHtml .= '                <p id="' . $sPaymentMethod . '_interestAmount" class="rp-installment-plan-description small">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_INTEREST_AMOUNT_DESC');
        $sHtml .= '                </p>';
        $sHtml .= '            </div>';
        $sHtml .= '            <div class="text-right">';
        $sHtml .= $aInstallmentDetails['interestAmount'];
        $sHtml .= '            </div>';
        $sHtml .= '        </div>';

        $sHtml .= '        <div class="rp-installment-plan-details">';
        $sHtml .= '            <div colspan="2"></div>';
        $sHtml .= '        </div>';

        $sHtml .= '        <div class="rp-installment-plan-details">';
        $sHtml .= '            <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver(\'' . $sPaymentMethod . '_rate\')" onmouseout="fcpoMouseOut(\'' . $sPaymentMethod . '_rate\')">';
        $sHtml .= $aInstallmentDetails['numberOfRate'] . ' ' . $oLang->translateString('FCPO_RATEPAY_CALCULATION_DURATION_MONTH_LABEL') . '&nbsp;';
        $sHtml .= '                <p id="' . $sPaymentMethod . '_rate" class="rp-installment-plan-description small">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_DURATION_MONTH_DESC');
        $sHtml .= '                </p>';
        $sHtml .= '            </div>';
        $sHtml .= '            <div class="text-right">';
        $sHtml .= $aInstallmentDetails['rate'];
        $sHtml .= '            </div>';
        $sHtml .= '        </div>';

        $sHtml .= '        <div class="rp-installment-plan-details">';
        $sHtml .= '            <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver(\'' . $sPaymentMethod . '_lastRate\')" onmouseout="fcpoMouseOut(\'' . $sPaymentMethod . '_lastRate\')">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_LAST_RATE_LABEL') . '&nbsp;';
        $sHtml .= '                <p id="' . $sPaymentMethod . '_lastRate" class="rp-installment-plan-description small">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_LAST_RATE_DESC');
        $sHtml .= '                </p>';
        $sHtml .= '            </div>';
        $sHtml .= '            <div class="text-right">';
        $sHtml .= $aInstallmentDetails['lastRate'];
        $sHtml .= '            </div>';
        $sHtml .= '        </div>';
        $sHtml .= '    </div>';


        $sHtml .= '    <div id="' . $sPaymentMethod . '_rp-installment-plan-no-details">';
        $sHtml .= '        <div class="rp-installment-plan-no-details">';
        $sHtml .= '            <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver(\'' . $sPaymentMethod . '_rate2\')" onmouseout="fcpoMouseOut(\'' . $sPaymentMethod . '_rate2\')">';
        $sHtml .= $aInstallmentDetails['numberOfRatesFull'] . ' ' . $oLang->translateString('FCPO_RATEPAY_CALCULATION_DURATION_MONTH_LABEL') . '&nbsp;';
        $sHtml .= '                <p id="' . $sPaymentMethod . '_rate2" class="rp-installment-plan-description small">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_DURATION_MONTH_DESC');
        $sHtml .= '                </p>';
        $sHtml .= '            </div>';
        $sHtml .= '            <div class="text-right">';
        $sHtml .= $aInstallmentDetails['rate'];
        $sHtml .= '            </div>';
        $sHtml .= '        </div>';
        $sHtml .= '    </div>';
        $sHtml .= '    <div class="rp-installment-plan-details">';
        $sHtml .= '        <div class="rp-installment-plan-title" onmouseover="fcpoMouseOver(\'' . $sPaymentMethod . '_totalAmount\')" onmouseout="fcpoMouseOut(\'' . $sPaymentMethod . '_totalAmount\')">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_TOTAL_AMOUNT_LABEL') . '&nbsp;';
        $sHtml .= '            <p id="' . $sPaymentMethod . '_totalAmount" class="rp-installment-plan-description small">';
        $sHtml .= $oLang->translateString('FCPO_RATEPAY_CALCULATION_TOTAL_AMOUNT_DESC');
        $sHtml .= '            </p>';
        $sHtml .= '        </div>';
        $sHtml .= '        <div class="text-right">';
        $sHtml .= $aInstallmentDetails['totalAmount'];
        $sHtml .= '        </div>';
        $sHtml .= '    </div>';
        $sHtml .= '</div>';
        $sHtml .= '<div>';
        $sHtml .= '<input type="hidden" name="dynvalue[fcporp_installment_amount]" value="' . $aInstallmentDetails['rate'] . '">';
        $sHtml .= '<input type="hidden" name="dynvalue[fcporp_installment_number]" value="' . $aInstallmentDetails['numberOfRatesFull'] . '">';
        $sHtml .= '<input type="hidden" name="dynvalue[fcporp_installment_last_amount]" value="' . $aInstallmentDetails['lastRate'] . '">';
        $sHtml .= '<input type="hidden" name="dynvalue[fcporp_installment_interest_rate]" value="' . $aInstallmentDetails['interestRate'] . '">';
        $sHtml .= '<input type="hidden" name="dynvalue[fcporp_installment_total_amount]" value="' . $aInstallmentDetails['totalAmount'] . '">';

        $sHtml .= '<input type="hidden" name="dynvalue[fcporp_installment_service_charge]" value="' . $aInstallmentDetails['serviceCharge'] . '">';
        $sHtml .= '<input type="hidden" name="dynvalue[fcporp_installment_annual_percentage_rate]" value="' . $aInstallmentDetails['annualPercentageRate'] . '">';
        $sHtml .= '<input type="hidden" name="dynvalue[fcporp_installment_interest_amount]" value="' . $aInstallmentDetails['interestAmount'] . '">';
        $sHtml .= '<input type="hidden" name="dynvalue[fcporp_installment_basket_amount]" value="' . $aInstallmentDetails['amount'] . '">';
        $sHtml .= '<input type="hidden" name="dynvalue[fcporp_installment_number_of_rate]" value="' . $aInstallmentDetails['numberOfRate'] . '">';
        $sHtml .= '</div>';

        return $sHtml;
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

    if ($sAction == 'fcpoapl_register_device' && $sPaymentId == 'fcpo_apple_pay') {
        echo $oPayoneAjax->fcpoAplRegisterDevice($sParamsJson);
    }
    if ($sAction == 'fcpoapl_create_session' && $sPaymentId == 'fcpo_apple_pay') {
        echo $oPayoneAjax->fcpoAplCreateSession($sParamsJson);
    }
    if ($sAction == 'fcpoapl_payment' && $sPaymentId == 'fcpo_apple_pay') {
        echo $oPayoneAjax->fcpoAplPayment($sParamsJson);
    }
    if ($sAction == 'fcpoapl_get_order_info' && $sPaymentId == 'fcpo_apple_pay') {
        echo $oPayoneAjax->fcpoAplOrderInfo();
    }

    if ($sAction == 'fcporp_calculation' && $sPaymentId == 'fcporp_installment') {
        echo $oPayoneAjax->fcpoRatepayCalculation($sParamsJson);
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