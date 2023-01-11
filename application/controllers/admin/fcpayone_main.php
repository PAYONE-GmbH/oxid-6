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
 
class fcpayone_main extends fcpayone_admindetails
{

    /**
     * Helper object for dealing with different shop versions
     *
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * Current class template name
     *
     * @var string
     */
    protected $_sThisTemplate = 'fcpayone_main.tpl';

    /**
     * List of boolean config values
     *
     * @var array
     */
    protected $_aConfBools = array();

    /**
     * List of string config values
     *
     * @var array
     */
    protected $_aConfStrs = array();

    /**
     * List of array config values
     *
     * @var array
     */
    protected $_aConfArrs = array();

    /**
     * List of countries
     *
     * @var array
     */
    protected $_aCountryList = array();

    /**
     * List of credit cards
     *
     * @var array
     */
    protected $_aAplCreditCardsList = array();

    /**
     * List of config errors encountered
     * @var array
     */
    protected $_aConfErrors = null;

    /**
     * Set of default config strings
     *
     * @var array 
     */
    protected $_aFcpoDefaultStringConf = array(
        'sFCPOCCType' => 'ajax',
        'sFCPOCCNumberType' => 'tel',
        'sFCPOCCNumberCount' => '30',
        'sFCPOCCNumberMax' => '16',
        'sFCPOCCNumberIframe' => 'standard',
        'sFCPOCCNumberWidth' => '202px',
        'sFCPOCCNumberHeight' => '20px',
        'sFCPOCCNumberStyle' => 'standard',
        'sFCPOCCNumberCSS' => '',
        'sFCPOCCCVCType' => 'tel',
        'sFCPOCCCVCCount' => '30',
        'sFCPOCCCVCMax' => '4',
        'sFCPOCCCVCIframe' => 'standard',
        'sFCPOCCCVCWidth' => '202px',
        'sFCPOCCCVCHeight' => '20px',
        'sFCPOCCCVCStyle' => 'standard',
        'sFCPOCCCVCCSS' => '',
        'sFCPOCCMonthType' => 'select',
        'sFCPOCCMonthCount' => '3',
        'sFCPOCCMonthMax' => '2',
        'sFCPOCCMonthIframe' => 'custom',
        'sFCPOCCMonthWidth' => '50px',
        'sFCPOCCMonthHeight' => '20px',
        'sFCPOCCMonthStyle' => 'standard',
        'sFCPOCCMonthCSS' => '',
        'sFCPOCCYearType' => 'select',
        'sFCPOCCYearCount' => '5',
        'sFCPOCCYearMax' => '4',
        'sFCPOCCYearIframe' => 'custom',
        'sFCPOCCYearWidth' => '80px',
        'sFCPOCCYearHeight' => '20px',
        'sFCPOCCYearStyle' => 'standard',
        'sFCPOCCYearCSS' => '',
        'sFCPOCCIframeWidth' => '202px',
        'sFCPOCCIframeHeight' => '20px',
        'sFCPOCCStandardInput' => 'border: 1px solid #8c8989; border-radius: 2px;',
        'sFCPOCCStandardOutput' => '',
    );

    /**
     * Configuration for JS CC Preview generation
     *
     * @var array
     */
    protected $_aFcJsCCPreviewFieldConfigs = array(
        'cardpan' => array(
            'selector' => 'cardpan',
            'type' => 'sFCPOCCNumberType',
            'size' => 'sFCPOCCNumberCount',
            'maxlength' => 'sFCPOCCNumberMax',
            'customstyle' => 'sFCPOCCNumberStyle',
            'style' => 'sFCPOCCNumberCSS',
            'customiframe' => 'sFCPOCCNumberIframe',
            'widht' => 'sFCPOCCNumberWidth',
            'height' => 'sFCPOCCNumberHeight',
        ),
        'cardcvc2' => array(
            'selector' => 'cardcvc2',
            'type' => 'sFCPOCCCVCType',
            'size' => 'sFCPOCCCVCCount',
            'maxlength' => 'sFCPOCCCVCMax',
            'customstyle' => 'sFCPOCCCVCStyle',
            'style' => 'sFCPOCCCVCCSS',
            'customiframe' => 'sFCPOCCCVCIframe',
            'widht' => 'sFCPOCCCVCWidth',
            'height' => 'sFCPOCCCVCHeight',
        ),
        'cardexpiremonth' => array(
            'selector' => 'cardexpiremonth',
            'type' => 'sFCPOCCMonthType',
            'size' => 'sFCPOCCMonthCount',
            'maxlength' => 'sFCPOCCMonthMax',
            'customstyle' => 'sFCPOCCMonthStyle',
            'style' => 'sFCPOCCMonthCSS',
            'customiframe' => 'sFCPOCCMonthIframe',
            'widht' => 'sFCPOCCMonthWidth',
            'height' => 'sFCPOCCMonthHeight',
        ),
        'cardexpireyear' => array(
            'selector' => 'cardexpireyear',
            'type' => 'sFCPOCCYearType',
            'size' => 'sFCPOCCYearCount',
            'maxlength' => 'sFCPOCCYearMax',
            'customstyle' => 'sFCPOCCYearStyle',
            'style' => 'sFCPOCCYearCSS',
            'customiframe' => 'sFCPOCCYearIframe',
            'widht' => 'sFCPOCCYearWidth',
            'height' => 'sFCPOCCYearHeight',
        ),
    );

    /**
     * Configuration for JS CC Preview generation
     *
     * @var array
     */
    protected $_aFcJsCCPreviewDefaultStyle = array(
        'input' => 'sFCPOCCStandardInput',
        'select' => 'sFCPOCCStandardOutput',
        'width' => 'sFCPOCCIframeWidth',
        'height' => 'sFCPOCCIframeHeight',
    );

    /**
     * Collects messages of different types
     *
     * @var array
     */
    protected $_aAdminMessages = array();

    /**
     * init object construction
     * 
     * @return null
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
        
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sOxid = $oConfig->getShopId();
        $this->_fcpoLoadConfigs($sOxid);
        $this->_fcpoLoadCountryList();
        $this->_fcpoLoadAplCreditCardsList();
    }

    /**
     * Loads PAYONE configuration and passes it to Smarty engine, returns
     * name of template file "fcpayone_main.tpl".
     *
     * @return string
     */
    public function render() 
    {
        $sReturn = parent::render();
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();

        $this->_aViewData['sHelpURL'] = $this->_oFcpoHelper->fcpoGetHelpUrl();

        if ($this->_oFcpoHelper->fcpoGetRequestParameter("aoc")) {
            $sOxid = $this->_oFcpoHelper->fcpoGetRequestParameter("oxid");
            $this->_aViewData["oxid"] = $sOxid;
            $sType = $this->_oFcpoHelper->fcpoGetRequestParameter("type");
            $this->_aViewData["type"] = $sType;

            if (version_compare($oConfig->getVersion(), '4.6.0', '>=')) {
                $oPayOneAjax = oxNew('fcpayone_main_ajax');
                $aColumns = $oPayOneAjax->getColumns();
            } else {
                $aColumns = array();
                include_once 'inc/' . strtolower(__CLASS__) . '.inc.php';
            }
            $this->_aViewData['oxajax'] = $aColumns;

            return "fcpayone_popup_main.tpl";
        }
        return $sReturn;
    }
    
    /**
     * Template getter that returns an array of available ISO-Codes of currencies
     * 
     * @param  void
     * @return void
     */
    public function fcpoGetCurrencyIso() 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $aCurrencyArray = $oConfig->getCurrencyArray();
        $aReturn = array();
        foreach ($aCurrencyArray as $oCur) {
            $aReturn[] = $oCur->name;
        }
        
        return $aReturn;
    }

    /**
     * Template getter for returning template version
     * 
     * @param  void
     * @return string
     */
    public function fcpoGetModuleVersion() 
    {
        return $this->_oFcpoHelper->fcpoGetModuleVersion();
    }

    /**
     * Template getter for boolean config values
     * 
     * @param  void
     * @return array
     */
    public function fcpoGetConfBools() 
    {
        return $this->_aConfBools;
    }

    /**
     * Template getter for string config values
     * 
     * @param  void
     * @return array
     */
    public function fcpoGetConfStrs() 
    {
        return $this->_aConfStrs;
    }

    /**
     * Template getter for array config values
     * 
     * @param  void
     * @return array
     */
    public function fcpoGetConfArrs() 
    {
        return $this->_aConfArrs;
    }

    /**
     * Template getter for countrylist
     * 
     * @param  void
     * @return array
     */
    public function fcpoGetCountryList() 
    {
        return $this->_aCountryList;
    }

    /**
     * Template getter for apple pay credit card list
     *
     * @param  void
     * @return array
     */
    public function fcpoGetAplCreditCards()
    {
        return $this->_aAplCreditCardsList;
    }

    /**
     * Saves changed configuration parameters.
     *
     * @return mixed
     */
    public function save() 
    {
        $blValid = $this->_fcpoValidateData();

        if (!$blValid) {
            return;
        }

        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $aConfBools = $this->_oFcpoHelper->fcpoGetRequestParameter("confbools");
        $aConfStrs = $this->_oFcpoHelper->fcpoGetRequestParameter("confstrs");
        $aConfArrs = $this->_oFcpoHelper->fcpoGetRequestParameter("confarrs");

        if (is_array($aConfBools)) {
            foreach ($aConfBools as $sVarName => $sVarVal) {
                $oConfig->saveShopConfVar("bool", $sVarName, $sVarVal);
            }
        }

        if (is_array($aConfStrs)) {
            foreach ($aConfStrs as $sVarName => $sVarVal) {
                $oConfig->saveShopConfVar("str", $sVarName, $sVarVal);
            }
        }

        if (is_array($aConfArrs)) {
            foreach ($aConfArrs as $sVarName => $aVarVal) {
                // home country multiple selectlist feature
                if (!is_array($aVarVal)) {
                    $aVarVal = $this->_multilineToArray($aVarVal);
                }
                $oConfig->saveShopConfVar("arr", $sVarName, $aVarVal);
            }
        }

        // add storeids, campaigns and logos if set
        $this->_fcpoCheckAndAddLogos();

        // add ratepay profiles if set
        $this->_fcpoCheckAndAddRatePayProfile();
        $this->_fcpoInsertProfiles();

        // request and add amazonpay configuration if triggered
        $this->_fcpoCheckRequestAmazonPayConfiguration();

        $this->_handlePayPalExpressLogos();

        $this->handleApplePayCredentials(
            $aConfStrs['sFCPOAplCertificate'],
            $aConfStrs['sFCPOAplKey']
        );
        
        //reload config after saving
        $sOxid = $oConfig->getShopId();
        $this->_fcpoLoadConfigs($sOxid);
    }

    /**
     * Returns collected errors
     *
     * @param void
     * @return mixed array|false
     */
    public function fcpoGetConfigErrors()
    {
        if (!is_array($this->_aConfErrors)) {
            return false;
        }

        return $this->_aConfErrors;
    }

    /**
     * Validation of entered configuration values
     *
     * @param void
     * @return bool
     */
    protected function _fcpoValidateData()
    {
        $blValidAccountData = $this->_fcpoValidateAccountData();

        $blValid = (
            $blValidAccountData
        );

        return $blValid;
    }

    /**
     * Checks accountdata section on errors
     *
     * @param void
     * @return bool
     */
    protected function _fcpoValidateAccountData()
    {
        return true;
    }

    /**
     * Adding a detected configuration error
     *
     * @param $sTranslationString
     * @return void
     */
    protected function _fcpoAddConfigError($sTranslationString)
    {
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $sMessage = $oLang->translateString($sTranslationString);

        if (!is_array($this->_aConfErrors)) {
            $this->_aConfErrors = array();
        }
        $this->_aConfErrors[] = $sMessage;
    }

    /**
     * Loads list of countries
     * 
     * @param  void
     * @return void
     */
    protected function _fcpoLoadCountryList() 
    {
        // #251A passing country list
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $oCountryList = $this->_oFcpoHelper->getFactoryObject("oxCountryList");
        $oCountryList->loadActiveCountries($oLang->getTplLanguage());

        $blValidCountryData = (
                isset($this->_aConfArrs["aFCPODebitCountries"]) &&
                count($this->_aConfArrs["aFCPODebitCountries"]) &&
                count($oCountryList)
                );

        if ($blValidCountryData) {
            foreach ($oCountryList as $sCountryId => $oCountry) {
                if (in_array($oCountry->oxcountry__oxid->value, $this->_aConfArrs["aFCPODebitCountries"])) {
                    $oCountryList[$sCountryId]->selected = "1"; 
                }
            }
        }

        $this->_aCountryList = $oCountryList;
    }

    /**
     * Loads list of supported creditcards for Apple Pay
     *
     */
    protected function _fcpoLoadAplCreditCardsList()
    {
        $this->_aAplCreditCardsList = [
            'V' => json_decode(json_encode([
                'name' => 'Visa',
                'selected' => $this->_aConfArrs["aFCPOAplCreditCards"] && in_array('V', $this->_aConfArrs["aFCPOAplCreditCards"]) ? 1 : 0
            ])),
            'M' => json_decode(json_encode([
                'name' => 'Mastercard',
                'selected' => $this->_aConfArrs["aFCPOAplCreditCards"] && in_array('M', $this->_aConfArrs["aFCPOAplCreditCards"]) ? 1 : 0
            ])),
        ];
    }

    /**
     * Loads configurations of payone and make them accessable
     * 
     * @param  void
     * @return void
     */
    protected function _fcpoLoadConfigs($sShopId) 
    {
        $aConfigs = $this->_oFcpoConfigExport->fcpoGetConfig($sShopId);
        $this->_aConfStrs = $aConfigs['strs'];
        $this->_aConfStrs = $this->_initConfigStrings();
        $this->_aConfBools = $aConfigs['bools'];
        $this->_aConfArrs = $aConfigs['arrs'];
    }

    /**
     * Insert Ratepay profile
     * 
     * @param  void
     *  @return void
     */
    protected function _fcpoInsertProfiles() 
    {
        $aRatePayProfiles = $this->_oFcpoHelper->fcpoGetRequestParameter('aRatepayProfiles');
        if (is_array($aRatePayProfiles)) {
            foreach ($aRatePayProfiles as $sOxid=>$aRatePayData) {
                $this->_oFcpoRatePay->fcpoInsertProfile($sOxid, $aRatePayData);
            }
        }
    }

    /**
     * Check and add a new Ratepay Profile
     * 
     * @param  void
     * @return void
     */
    protected function _fcpoCheckAndAddRatePayProfile() 
    {
        if ($this->_oFcpoHelper->fcpoGetRequestParameter('addRatePayProfile')) {
            $this->_oFcpoRatePay->fcpoAddRatePayProfile();
            $this->_aAdminMessages["blRatePayProfileAdded"] = true;
        }
    }

    /**
     * Checks if button for fetching configuration settings for amazon from payone api has been triggered
     * Initiates requesting api if true
     *
     * @param void
     * @return void
     */
    protected function _fcpoCheckRequestAmazonPayConfiguration() {
        if ($this->_oFcpoHelper->fcpoGetRequestParameter('getAmazonPayConfiguration')) {
            $oLang = $this->_oFcpoHelper->fcpoGetLang();
            $blSuccess = $this->_fcpoRequestAndAddAmazonConfig();
            $sMessage = 'FCPO_AMAZONPAY_ERROR_GETTING_CONFIG';
            if ($blSuccess) {
                $this->_aAdminMessages["blAmazonPayConfigFetched"] = true;
                $sMessage = 'FCPO_AMAZONPAY_SUCCESS_GETTING_CONFIG';
            }
            $sTranslatedMessage = $oLang->translateString($sMessage);
            $oUtilsView = $this->_oFcpoHelper->fcpoGetUtilsView();
            $oUtilsView->addErrorToDisplay($sTranslatedMessage, false, true);
        }
    }

    /**
     * Triggers requesting payone api for amazon configuration and returns
     * if succeeded
     *
     * @param void
     * @return bool
     */
    protected function _fcpoRequestAndAddAmazonConfig() {
        $oFcpoRequest = $this->_oFcpoHelper->getFactoryObject('fcporequest');
        $aResponse = $oFcpoRequest->sendRequestGetAmazonPayConfiguration();
        $blSuccess = $this->_fcpoSaveAmazonConfigFromResponse($aResponse);

        return $blSuccess;
    }

    /**
     * Analyzes response tries to save config and returns if everything succeeded
     *
     * @param $aResponse
     * @return bool
     */
    protected function _fcpoSaveAmazonConfigFromResponse($aResponse) {
        $sStatus = $aResponse['status'];
        $blReturn = false;
        if ($sStatus == 'OK') {
            $sSellerId = $aResponse['add_paydata[seller_id]'];
            $sClientId = $aResponse['add_paydata[client_id]'];
            $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
            $oConfig->saveShopConfVar('str', 'sFCPOAmazonPaySellerId', $sSellerId);
            $oConfig->saveShopConfVar('str', 'sFCPOAmazonPayClientId', $sClientId);
            $blReturn = true;
        }

        return $blReturn;
    }

    /**
     * Check if logo shall be added. Adds it and set flag true in case
     * 
     * @param  void
     * @return void
     */
    protected function _fcpoCheckAndAddLogos() 
    {
        if ($this->_oFcpoHelper->fcpoGetRequestParameter('addPayPalLogo')) {
            $this->_oFcpoPayPal->fcpoAddPaypalExpressLogo();
            $this->_aAdminMessages["blLogoAdded"] = true;
        }
    }

    /**
     * Handling of paypal express logos
     * 
     * @param  void
     * @return void
     */
    protected function _handlePayPalExpressLogos() 
    {
        $aLogos = $this->_oFcpoHelper->fcpoGetRequestParameter('logos');

        if (is_array($aLogos) && count($aLogos) > 0) {
            $this->_oFcpoPayPal->fcpoUpdatePayPalLogos($aLogos);
            $aMessages = $this->_oFcpoPayPal->fcpoGetMessages();
            $this->_aAdminMessages = array_merge($this->_aAdminMessages, $aMessages);
        }
    }

    /**
     * Template getter for requesting if logo has recently been added
     * 
     * @param  void
     * @return bool
     */
    public function fcpoIsLogoAdded() 
    {
        $blLogoAdded = ( isset($this->_aAdminMessages["blLogoAdded"]) && $this->_aAdminMessages["blLogoAdded"] === true ) ? true : false;

        return $blLogoAdded;
    }

    /**
     * Returns configured ratepay profiles
     * 
     * @param  void
     * @return array
     */
    public function fcpoGetRatePayProfiles() 
    {
        $aReturn = $this->_oFcpoRatePay->fcpoGetRatePayProfiles();

        return $aReturn;
    }

    /**
     * Return admin template seperator sign by shop-version
     *
     * @return string
     */
    public function fcGetAdminSeperator() 
    {
        $iVersion = $this->_oFcpoHelper->fcpoGetIntShopVersion();
        if ($iVersion < 4300) {
            return '?';
        } else {
            return '&';
        }
    }

    /**
     * Method returns the checksum result
     * 
     * @param  void
     * @return string
     */
    protected function _fcpoGetCheckSumResult() 
    {
        $sIncludePath = getShopBasePath() . 'modules/fcPayOne/fcCheckChecksum.php';
        $oScript = $this->_oFcpoHelper->fcpoGetInstance('fcCheckChecksum', $sIncludePath);

        return $oScript->checkChecksumXml();
    }

    /**
     * Generates and delivers an xml export of configuration
     * 
     * @param  void
     * @return void
     */
    public function export() 
    {
        $oConfigExport = $this->_oFcpoHelper->getFactoryObject('fcpoconfigexport');
        $oConfigExport->fcpoExportConfig();
    }

    /**
     * Returns an array of languages of the shop
     * 
     * @param  void
     * @return array
     */
    public function fcGetLanguages() 
    {
        $aReturn = array();
        $oFcLang = $this->_oFcpoHelper->fcpoGetLang();

        foreach ($oFcLang->getLanguageArray() as $oLang) {
            if ($oLang->active == 1) {
                $aReturn[$oLang->oxid] = $oLang->name;
            }
        }
        return $aReturn;
    }

    /**
     * Returns an array of currencies of the shop
     * 
     * @param  void
     * @return array
     */
    public function fcGetCurrencies() 
    {
        $aReturn = array();
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();

        foreach ($oConfig->getCurrencyArray() as $iKey => $oCurr) {
            $aReturn[$oCurr->name] = $oCurr->name;
        }
        return $aReturn;
    }

    /**
     * Returns list of uploaded paypalexpresslogos
     * 
     * @param  void
     * @return array
     */
    public function fcpoGetPayPalLogos() 
    {
        $oPaypal = $this->_oFcpoHelper->getFactoryObject('fcpopaypal');
        $aLogos = $oPaypal->fcpoGetPayPalLogos();

        return $aLogos;
    }

    /**
     * Returns fields belonging to creditcard
     * 
     * @param  void
     * @return array
     */
    public function getCCFields() 
    {
        return array(
            'Number',
            'CVC',
            'Month',
            'Year',
        );
    }

    /**
     * Return array of cc types
     * 
     * @param  string $sField
     * @return array
     */
    public function getCCTypes($sField) 
    {
        $aTypes = array();
        if ($sField == 'Month' || $sField == 'Year') {
            $aTypes['select'] = $this->_oFcpoHelper->fcpoGetLang()->translateString('FCPO_CC_SELECT');
        }
        $aTypes['tel'] = $this->_oFcpoHelper->fcpoGetLang()->translateString('FCPO_CC_TYPE_NUMERIC');
        $aTypes['password'] = $this->_oFcpoHelper->fcpoGetLang()->translateString('FCPO_CC_TYPE_PASSWORD');
        $aTypes['text'] = $this->_oFcpoHelper->fcpoGetLang()->translateString('FCPO_CC_TYPE_TEXT');

        return $aTypes;
    }

    /**
     * Get available cc styles
     * 
     * @param  void
     * @return array
     */
    public function getCCStyles() 
    {
        return array(
            'standard' => $this->_oFcpoHelper->fcpoGetLang()->translateString('FCPO_CC_IFRAME_STANDARD'),
            'custom' => $this->_oFcpoHelper->fcpoGetLang()->translateString('FCPO_CC_IFRAME_CUSTOM'),
        );
    }

    /**
     * Method returns config value of a given config name or false if not existing
     * 
     * @param  string $sParam
     * @return mixed
     */
    public function getConfigParam($sParam) 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sConfigValue = $oConfig->getConfigParam($sParam);

        return $sConfigValue;
    }

    /**
     * Template getter returns the preview javascript code
     * 
     * @param  void
     * @return string
     */
    public function fcpoGetJsCardPreviewCode() 
    {
        $sJsCode = "";
        $sJsCode .= $this->_fcpoGetJsPreviewCodeHeader();
        $sJsCode .= $this->_fcpoGetJsPreviewCodeFields();
        $sJsCode .= "\t" . "},";
        $sJsCode .= $this->_fcpoGetJsPreviewCodeDefaultStyle();
        $sJsCode .= $this->_fcpoGetJsPreviewCodeErrorBlock();
        $sJsCode .= '};';
        $sJsCode .= 'var iframes = new Payone.ClientApi.HostedIFrames(config, request);';

        return $sJsCode;
    }

    /**
     * Returns a list of deliverysets for template select
     *
     * @param void
     * @return array
     */
    public function fcpoGetDeliverySets()
    {
        $oDeliveryAdminList =
            $this->_oFcpoHelper->getFactoryObject('DeliverySet_List');
        $oList = $oDeliveryAdminList->getItemList();
        $aDeliveryList = $oList->getArray();
        return $aDeliveryList;
    }

    /**
     * Getter which delivers the error block part
     * 
     * @param  void
     * @return string
     */
    protected function _fcpoGetJsPreviewCodeErrorBlock() 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sJsCode = "";
        $blFCPOCCErrorsActive = $oConfig->getConfigParam('blFCPOCCErrorsActive');
        $sFCPOCCErrorsLang = $oConfig->getConfigParam('sFCPOCCErrorsLang');
        $sLangConcat = ($sFCPOCCErrorsLang == 'de') ? 'de' : 'en';

        if ($blFCPOCCErrorsActive) {
            $sJsCode .= "\t\t" . 'error: "errorOutput",' . "\n";
            $sJsCode .= "\t\t\t" . 'language: language: Payone.ClientApi.Language.' . $sLangConcat . "\n";
        }

        return $sJsCode;
    }

    /**
     * Returns default style javascript block
     * 
     * @param  void
     * @return string
     */
    protected function _fcpoGetJsPreviewCodeDefaultStyle() 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sJsCode = "\t" . 'defaultStyle: {' . "\n";
        $sJsCode .= "\t\t" . 'input: "' . $oConfig->getConfigParam($this->_aFcJsCCPreviewDefaultStyle['input']) . '",' . "\n";
        $sJsCode .= "\t\t" . 'select: "' . $oConfig->getConfigParam($this->_aFcJsCCPreviewDefaultStyle['select']) . '",' . "\n";
        $sJsCode .= "\t\t" . 'iframe: {' . "\n";
        $sJsCode .= "\t\t\t" . 'width: "' . $oConfig->getConfigParam($this->_aFcJsCCPreviewDefaultStyle['width']) . '",' . "\n";
        $sJsCode .= "\t\t\t" . 'height: "' . $oConfig->getConfigParam($this->_aFcJsCCPreviewDefaultStyle['height']) . '",' . "\n";
        $sJsCode .= "\t\t" . '}' . "\n";
        $sJsCode .= "\t" . '},' . "\n";

        return $sJsCode;
    }

    /**
     * Returns the configured fields
     * 
     * @param  void
     * @return string
     */
    protected function _fcpoGetJsPreviewCodeFields() 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sJsCode = "";

        foreach ($this->_aFcJsCCPreviewFieldConfigs as $sFieldGroupIdent => $aCCFieldConfig) {
            $blCustomStyle = $oConfig->getConfigParam($aCCFieldConfig['customstyle']);
            $blCustomIframe = $oConfig->getConfigParam($aCCFieldConfig['customiframe']);
            $sJsCode .= "\t\t" . $sFieldGroupIdent . ": {" . "\n";
            foreach ($aCCFieldConfig as $sVar => $sConfVal) {
                $sValue = $this->_fcGetJsPreviewCodeValue($sVar, $sConfVal, $blCustomStyle, $blCustomIframe);
                if ($sValue) {
                    $sJsCode .= "\t\t\t" . $sVar . ': "' . $sValue . '",' . "\n";
                }
            }
            $sJsCode .= "\t\t" . "}," . "\n";
        }

        return $sJsCode;
    }

    /**
     * Method returns the matching value no matter if its a config value or direct
     * 
     * @param  string $sVar
     * @param  string $sConfVal
     * @param  bool   $blCustomStyle
     * @param  bool   $blCustomIframe
     * @return string
     */
    protected function _fcGetJsPreviewCodeValue($sVar, $sConfVal, $blCustomStyle, $blCustomIframe) 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sReturn = "";

        $blCustomStyleVar = ($sVar == 'style');
        $blCustomIframeVar = ($sVar == 'width' || $sVar == 'height');
        $blNoCustomVar = (!$blCustomStyleVar && !$blCustomIframeVar);

        if ($sVar == 'selector') {
            $sReturn = $sConfVal;
        } else if ($blCustomStyleVar && $blCustomStyle) {
            $sReturn = $oConfig->getConfigParam($sConfVal);
        } else if ($blCustomIframeVar && $blCustomIframe) {
            $sReturn = $oConfig->getConfigParam($sConfVal);
        } else if ($blNoCustomVar) {
            $sReturn = $oConfig->getConfigParam($sConfVal);
        }

        return $sReturn;
    }

    /**
     * Returns the header part of injected javascript
     * 
     * @param  void
     * @return string
     */
    protected function _fcpoGetJsPreviewCodeHeader() 
    {
        $sJsCode = "";
        $sJsCode .= "var request, config;" . "\n";
        $sJsCode .= "config = {" . "\n";
        $sJsCode .= "\t" . "fields: {" . "\n";

        return $sJsCode;
    }

    /**
     * Set default values
     * 
     * @param  array  $aArray
     * @param  string $sKey
     * @param  mixed  $mValue
     * @return array
     */
    protected function _fcpoSetDefault($aArray, $sKey, $mValue) 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        if (!isset($aArray[$sKey])) {
            $oConfig->saveShopConfVar("str", $sKey, $mValue);
        }

        return $oConfig->getShopConfVar($sKey);
    }

    /**
     * Initialize config strings
     * 
     * @param  void
     * @return array
     */
    protected function _initConfigStrings() 
    {
        $aConfStrs = $this->_aConfStrs;
        foreach ($this->_aFcpoDefaultStringConf as $sKey => $sValue) {
            $aConfStrs[$sKey] = $this->_fcpoSetDefault($aConfStrs, $sKey, $sValue);
        }
        
        return $aConfStrs;
    }

    /**
     * Converts Multiline text to simple array. Returns this array.
     *
     * @param string $sMultiline Multiline text
     *
     * @return array
     */
    protected function _multilineToArray($sMultiline) 
    {
        $aArr = explode("\n", $sMultiline);

        if (!is_array($aArr)) {
            return; 
        }

        foreach ($aArr as $key => $val) {
            $aArr[$key] = trim($val);
            if ($aArr[$key] == "") {
                unset($aArr[$key]); 
            }
        }

        return $aArr;
    }

    /**
     * Handles the save of credential files/text for Apple Pay configuration
     *
     * @param string $sCertFilename
     * @param string $sKeyFileName
     */
    public function handleApplePayCredentials($sCertFilename, $sKeyFileName)
    {
        $aFiles = $this->_oFcpoHelper->fcpoGetFiles();
        $sCertDir = getShopBasePath() . 'modules/fc/fcpayone/cert/';

        foreach ($aFiles as $sInputName => $aFile) {
            if (!in_array($sInputName, ['fcpoAplCertificateFile', 'fcpoAplKeyFile'])) {
                continue;
            }

            if ($sInputName == 'fcpoAplCertificateFile') {
                $this->saveApplePayFile($aFile, $sCertFilename, $sCertDir);
            }

            if ($sInputName == 'fcpoAplKeyFile') {
                $this->saveApplePayFile($aFile, $sKeyFileName, $sCertDir);
            }
        }

        if (!isset($aFiles['fcpoAplKeyFile']) || empty($aFiles['fcpoAplKeyFile']['name'])) {
            $sKeyText = $this->_oFcpoHelper->fcpoGetRequestParameter('fcpoAplKeyText');
            if (!empty($sKeyText)) {
                $this->saveApplePayTextKey($sKeyText, $sKeyFileName, $sCertDir);
            }
        }
    }

    /**
     * Saves the file, adjusting the filename if necessary
     *
     * @param array $aFileData
     * @param string $sPostedFilename
     * @param string $sCertDir
     */
    protected function saveApplePayFile($aFileData, $sPostedFilename, $sCertDir)
    {
        if (!empty($aFileData['name'] && $aFileData['size'] > 0)) {
            $sFilename = $aFileData['name'];
            if(!empty($sPostedFilename)) {
                $sFilename = $sPostedFilename;
            }

            $this->saveFile(
                $sFilename,
                $aFileData['tmp_name'],
                $sCertDir
            );
        }
    }

    /**
     * Saves the Apple Pay key text in a proper file, and store its name in configuration
     *
     * @param string $sKeyContent
     * @param string $sPostedFilename
     * @param string $sCertDir
     */
    protected function saveApplePayTextKey($sKeyContent, $sPostedFilename, $sCertDir)
    {
        if (!empty($sKeyContent)) {
            $filename = 'merchant_id.key';
            if(!empty($sPostedFilename)) {
                $filename = $sPostedFilename;
            }

            $blResult = $this->writeFile($filename, $sKeyContent, $sCertDir);

            if ($blResult) {
                $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
                $oConfig->saveShopConfVar("str", 'sFCPOAplKey', $filename);
            }
        }
    }

    /**
     * Moves a file to a destination from the temporary storage after upload
     *
     * @param string $filename
     * @param string $tempFilePath
     * @param string $destinationPath
     * @return bool
     * @throws Exception
     */
    private function saveFile($filename, $tempFilePath, $destinationPath)
    {
        try {
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0700);
            }

            move_uploaded_file($tempFilePath, $destinationPath . $filename);
            chmod($destinationPath . $filename, 0644);

            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Writes a content to a named destination file
     *
     * @param string $filename
     * @param string $content
     * @param string $destinationPath
     * @return bool
     * @throws Exception
     */
    private function writeFile($filename, $content, $destinationPath)
    {
        try {
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0700);
            }

            if (!is_file($destinationPath . $filename)) {
                touch($destinationPath . $filename);
                chmod($destinationPath . $filename, 0644);
            }
            file_put_contents($destinationPath . $filename, $content);

            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }

}
