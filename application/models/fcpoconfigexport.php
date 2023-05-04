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

class fcpoconfigexport extends oxBase
{

    /**
     * Helper object for dealing with different shop versions
     *
     * @var fcpohelper
     */
    protected $_oFcpoHelper = null;

    /**
     * Centralized Database instance
     *
     * @var object
     */
    protected $_oFcpoDb = null;

    /**
     * Holds config values for all available shop ids
     *
     * @var array
     */
    protected $_aShopConfigs = array();

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
     * Newline
     *
     * @var string
     */
    protected $_sN = "\n";

    /**
     * Tab
     *
     * @var string
     */
    protected $_sT = "    ";

    /**
     * Definitions of multilang files
     *
     * @var array
     */
    protected $_aMultiLangFields = array(
        'sFCPOApprovalText',
        'sFCPODenialText',
    );


    /**
     * config fields which needs skipping multilines
     *
     * @var array
     */
    protected $_aSkipMultiline = array('aFCPODebitCountries', 'aFCPOAplCreditCards');

    /**
     * Init needed data
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
        $this->_oFcpoDb = oxDb::getDb();
    }

    /**
     * Returns payone configuration
     *
     * @param  string $sShopId
     * @param  int    $iLang
     * @return array
     */
    public function fcpoGetConfig($sShopId, $iLang = 0)
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $oDb = $this->_oFcpoHelper->fcpoGetDb(true);
        $sQuery = "select oxvarname, oxvartype, DECODE( oxvarvalue, " . $oDb->quote($oConfig->getConfigParam('sConfigKey')) . ") as oxvarvalue from oxconfig where oxshopid = '$sShopId' AND (oxvartype = 'str' OR oxvartype = 'bool' OR oxvartype = 'arr')";
        $aResult = $oDb->getAll($sQuery);

        if (count($aResult)) {
            $oStr = getStr();
            foreach ($aResult as $aRow) {
                $sVarName = $aRow['oxvarname'];
                $sVarType = $aRow['oxvartype'];
                $sVarVal = $aRow['oxvarvalue'];

                if ($sVarType == "bool") {
                    $this->_aConfBools[$sVarName] = ($sVarVal == "true" || $sVarVal == "1"); 
                }
                if ($sVarType == "str") {
                    $sVarName = $this->fcpoGetMultilangConfStrVarName($sVarName, $iLang);

                    $this->_aConfStrs[$sVarName] = $sVarVal;
                    if ($this->_aConfStrs[$sVarName]) {
                        $this->_aConfStrs[$sVarName] = $oStr->htmlentities($this->_aConfStrs[$sVarName]);
                    }
                }

                if ($sVarType == "arr") {
                    if (in_array($sVarName, $this->_aSkipMultiline)) {
                        $this->_aConfArrs[$sVarName] = unserialize($sVarVal);
                    } else {
                        $this->_aConfArrs[$sVarName] = $oStr->htmlentities($this->_arrayToMultiline(unserialize($sVarVal)));
                    }
                }
            }
        }

        $aConfigs = array();
        $aConfigs['strs'] = $this->_aConfStrs;
        $aConfigs['bools'] = $this->_aConfBools;
        $aConfigs['arrs'] = $this->_aConfArrs;
        return $aConfigs;
    }

    /**
     * Generates and delivers an xml export of configuration
     *
     * @param  void
     * @return null
     */
    public function fcpoExportConfig() 
    {
        $sXml = $this->fcpoGetConfigXml();
        if ($sXml !== false) {
            $this->_oFcpoHelper->fcpoHeader("Content-Type: text/xml; charset=\"utf8\"");
            $this->_oFcpoHelper->fcpoHeader("Content-Disposition: attachment; filename=\"payone_config_export" . date('Y-m-d H-i-s') . "_" . md5($sXml) . ".xml\"");
            echo $this->_oFcpoHelper->fcpoProcessResultString($sXml);
            $this->_oFcpoHelper->fcpoExit();
            return null;
        }
    }

    /**
     * Returns xml configuration of all shops
     *
     * @param  void
     * @return string
     */
    public function fcpoGetConfigXml() 
    {
        $aShopIds = $this->fcpoGetShopIds();
        $this->_fcpoSetShopConfigVars($aShopIds);


        $sXml = '<?xml version="1.0" encoding="UTF-8"?>' . $this->_sN;
        $sXml .= '<config>' . $this->_sN;

        foreach ($this->_aShopConfigs as $aShopConfVars) {
            $sXml .= $this->_sT . '<shop>' . $this->_sN;
            $sXml .= $this->_fcpoGetShopXmlGeneric($aShopConfVars);
            $sXml .= $this->_fcpoGetShopXmlSystem($aShopConfVars);
            $sXml .= $this->_fcpoGetShopXmlGlobal($aShopConfVars);
            $sXml .= $this->_fcpoGetShopXmlClearingTypes($aShopConfVars);
            $sXml .= $this->_fcpoGetShopXmlMisc();
            $sXml .= $this->_fcpoGetShopXmlChecksums();
            $sXml .= $this->_sT . '</shop>' . $this->_sN;
        }

        $sXml .= '</config>';

        return $sXml;
    }

    /**
     * Returns a list of shop ids
     *
     * @param  void
     * @return array
     */
    public function fcpoGetShopIds() 
    {
        return $this->_oFcpoDb->getCol("SELECT `oxid` FROM `oxshops`");
    }


    /**
     * Returns multilang varname if multilangfield
     *
     * @param  string $sVarName
     * @param  int    $iLang
     * @return string
     */
    public function fcpoGetMultilangConfStrVarName($sVarName, $iLang) 
    {
        if (!$iLang) {
            $iLang = 0;
        }
        $sLang = (string) $iLang;

        foreach ($this->_aMultiLangFields as $sMultiLangVar) {
            $sMultilangVarConcat = $sMultiLangVar . '_' . $sLang;
            if ($sVarName == $sMultilangVarConcat) {
                $sVarName = $sMultiLangVar;
            }
        }

        return $sVarName;
    }

    /**
     * Returns collected checksum errors if there are any
     *
     * @param  void
     * @return mixed
     */
    protected function _getChecksumErrors() 
    {
        $blOutput = false;
        $blCheckSumAvailable = $this->_oFcpoHelper->fcpoCheckClassExists('fcCheckChecksum');
        if ($blCheckSumAvailable) {
            $sResult = $this->_fcpoGetCheckSumResult();
            if ($sResult == 'correct') {
                return false;
            } else {
                $aErrors = json_decode(stripslashes($sResult));
                if (is_array($aErrors)) {
                    return $aErrors;
                }
            }
        }
    }

    /**
     * Sets needed shop values for later fetching from attribute
     *
     * @param  array $aShopIds
     * @return void
     */
    protected function _fcpoSetShopConfigVars($aShopIds) 
    {
        $oConf = $this->getConfig();

        foreach ($aShopIds as $sShopId) {
            $oShop = oxNew('oxshop');
            $blLoaded = $oShop->load($sShopId);
            if ($blLoaded) {
                $this->_aShopConfigs[$sShopId]['sFCPOMerchantID'] = $oConf->getShopConfVar('sFCPOMerchantID', $sShopId);
                $this->_aShopConfigs[$sShopId]['sFCPOSubAccountID'] = $oConf->getShopConfVar('sFCPOSubAccountID', $sShopId);
                $this->_aShopConfigs[$sShopId]['sFCPOPortalID'] = $oConf->getShopConfVar('sFCPOPortalID', $sShopId);
                $this->_aShopConfigs[$sShopId]['sFCPORefPrefix'] = $oConf->getShopConfVar('sFCPORefPrefix', $sShopId);
                $this->_aShopConfigs[$sShopId]['sFCPOSubAccountID'] = $oConf->getShopConfVar('sFCPOSubAccountID', $sShopId);
                $this->_aShopConfigs[$sShopId]['sShopName'] = $oShop->oxshops__oxname->value;
                $this->_aShopConfigs[$sShopId]['sShopVersion'] = $oShop->oxshops__oxversion->value;
                $this->_aShopConfigs[$sShopId]['sShopEdition'] = $oShop->oxshops__oxedition->value;
            }
        }
    }

    /**
     * Returns the generic part of shop specific xml
     *
     * @param  array $aShopConfVars
     * @return string
     */
    protected function _fcpoGetShopXmlGeneric($aShopConfVars) 
    {
        $sXml = $this->_sT . $this->_sT . "<code>{$sShopId}</code>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . "<name><![CDATA[{$aShopConfVars['sShopName']}]]></name>" . $this->_sN;

        return $sXml;
    }

    /**
     * Returns system block of shop specific xml
     *
     * @param  array $aShopConfVars
     * @return string
     */
    protected function _fcpoGetShopXmlSystem($aShopConfVars) 
    {
        $sXml = $this->_sT . $this->_sT . "<system>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "<name>OXID</name>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "<version>{$aShopConfVars['sShopVersion']}</version>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "<edition>{$aShopConfVars['sShopEdition']}</edition>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "<modules>" . $this->_sN;
        $aModules = $this->_getModuleInfo();
        if ($aModules && count($aModules) > 0) {
            foreach ($aModules as $sModule => $sInfo) {
                $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<{$sModule}>{$sInfo}</{$sModule}>" . $this->_sN;
            }
        }
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "</modules>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . "</system>" . $this->_sN;

        return $sXml;
    }

    /**
     * Returns shop specific global block
     *
     * @param  array $aShopConfVars
     * @return string
     */
    protected function _fcpoGetShopXmlGlobal($aShopConfVars) 
    {
        $sXml = $this->_sT . $this->_sT . "<global>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "<mid>" . $aShopConfVars['sFCPOMerchantID'] . "</mid>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "<aid>" . $aShopConfVars['sFCPOSubAccountID'] . "</aid>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "<portalid>" . $aShopConfVars['sFCPOPortalID'] . "</portalid>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "<refnr_prefix>" . $aShopConfVars['sFCPORefPrefix'] . "</refnr_prefix>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "<status_mapping>" . $this->_sN;
        $aPaymentMapping = $this->_getMappings();

        foreach ($aPaymentMapping as $sAbbr => $aMappings) {
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<{$sAbbr}>" . $this->_sN;
            foreach ($aMappings as $index => $subtype) {
                $subtype = $index;
                $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<{$subtype}>" . $this->_sN;
                foreach ($aMappings[$subtype] as $aMap) {
                    $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . $this->_sT . $this->_sT . '<map from="' . $aMap['from'] . '" to="' . $aMap['to'] . '" name="'. $aMap['name'] . '"/>' . $this->_sN;
                }
                $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . $this->_sT . "</{$subtype}>" . $this->_sN;
            }
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "</{$sAbbr}>" . $this->_sN;
        }
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "</status_mapping>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . "</global>" . $this->_sN;

        return $sXml;
    }

    /**
     * Returns shop specific clearingtypes
     *
     * @param  array $aShopConfVars
     * @return string
     */
    protected function _fcpoGetShopXmlClearingTypes($aShopConfVars) 
    {
        $sXml = $this->_sT . $this->_sT . "<clearingtypes>" . $this->_sN;
        $aPayments = $this->_getPaymentTypes();
        foreach ($aPayments as $oPayment) {
            $sXml .= $this->_sT . $this->_sT . $this->_sT . "<" . $this->_getPaymentAbbreviation($oPayment->getId()) . ">" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<title><![CDATA[{$oPayment->oxpayments__oxdesc->value}]]></title>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<id>{$oPayment->getId()}</id>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<mid>{$aShopConfVars['sFCPOMerchantID']}</mid>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<aid>{$aShopConfVars['sFCPOSubAccountID']}</aid>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<portalid>{$aShopConfVars['sFCPOPortalID']}</portalid>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<min_order_total>{$oPayment->oxpayments__oxfromamount->value}</min_order_total>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<max_order_total>{$oPayment->oxpayments__oxtoamount->value}</max_order_total>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<active>{$oPayment->oxpayments__oxactive->value}</active>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<countries>{$this->_getPaymentCountries($oPayment)}</countries>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<authorization>{$oPayment->oxpayments__fcpoauthmode->value}</authorization>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<mode>{$oPayment->fcpoGetOperationMode()}</mode>" . $this->_sN;
            $sXml .= $this->_sT . $this->_sT . $this->_sT . "</" . $this->_getPaymentAbbreviation($oPayment->getId()) . ">" . $this->_sN;
        }
        $sXml .= $this->_sT . $this->_sT . "</clearingtypes>" . $this->_sN;

        return $sXml;
    }

    /**
     * Returns miscelanous
     *
     * @param  void
     * @return string
     */
    protected function _fcpoGetShopXmlMisc() 
    {
        $sXml = $this->_sT . $this->_sT . "<misc>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "<transactionstatus_forwarding>" . $this->_sN;
        $aForwardings = $this->_getForwardings();
        foreach ($aForwardings as $aForward) {
            $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . '<config status="' . $aForward['status'] . '" url="' . htmlentities($aForward['url']) . '" timeout="' . $aForward['timeout'] . '"/>' . $this->_sN;
        }
        $sXml .= $this->_sT . $this->_sT . $this->_sT . "</transactionstatus_forwarding>" . $this->_sN;
        $sXml .= $this->_sT . $this->_sT . "</misc>" . $this->_sN;

        return $sXml;
    }

    /**
     * Returns shop specific checksum part of xml
     *
     * @param  void
     * @return string
     */
    protected function _fcpoGetShopXmlChecksums() 
    {
        $sXml = $this->_sT . $this->_sT . "<checksums>" . $this->_sN;
        $mUrlOpen = $this->_oFcpoHelper->fcpoIniGet('allow_url_fopen');
        $blCurlAvailable = $this->_oFcpoHelper->fcpoFunctionExists('curl_init');

        if ($mUrlOpen == 0) {
            $sXml .= $this->_sT . $this->_sT . $this->_sT . "<status>Cant verify checksums, allow_url_fopen is not activated on customer-server</status>" . $this->_sN;
        } elseif (!$blCurlAvailable) {
            $sXml .= $this->_sT . $this->_sT . $this->_sT . "<status>Cant verify checksums, curl is not activated on customer-server</status>" . $this->_sN;
        } else {
            $aErrors = $this->_getChecksumErrors();
            if ($aErrors === false) {
                $sXml .= $this->_sT . $this->_sT . $this->_sT . "<status>Correct</status>" . $this->_sN;
            } elseif (is_array($aErrors) && count($aErrors) > 0) {
                $sXml .= $this->_sT . $this->_sT . $this->_sT . "<status>Error</status>" . $this->_sN;
                $sXml .= $this->_sT . $this->_sT . $this->_sT . "<errors>" . $this->_sN;
                foreach ($aErrors as $sError) {
                    $sXml .= $this->_sT . $this->_sT . $this->_sT . $this->_sT . "<error>" . base64_encode($sError) . "</error>" . $this->_sN;
                }
                $sXml .= $this->_sT . $this->_sT . $this->_sT . "</errors>" . $this->_sN;
            }
        }
        $sXml .= $this->_sT . $this->_sT . "</checksums>" . $this->_sN;

        return $sXml;
    }

    /**
     * Returns array of payments
     *
     * @param  void
     * @return array
     */
    protected function _getPaymentTypes() 
    {
        $aPayments = array();

        $sQuery = "SELECT oxid FROM oxpayments WHERE fcpoispayone = 1";
        $this->_oFcpoDb->setFetchMode(\OxidEsales\EshopCommunity\Core\Database\Adapter\DatabaseInterface::FETCH_MODE_NUM);
        $aRows = $this->_oFcpoDb->getAll($sQuery);
        foreach ($aRows as $aRow) {
            $oPayment = oxNew('oxpayment');
            $sOxid = $aRow[0];
            if ($oPayment->load($sOxid)) {
                $aPayments[] = $oPayment;
            }
        }
        return $aPayments;
    }

    /**
     * Returns matching abbreviation for given payment id
     *
     * @param  string $sPaymentId
     * @return string
     */
    protected function _getPaymentAbbreviation($sPaymentId) 
    {
        $sAbbr = '';

        $aAbbreviations = array(
            'fcpocreditcard' => 'cc',
            'fcpocashondel' => 'cod',
            'fcpodebitnote' => 'elv',
            'fcpopayadvance' => 'vor',
            'fcpoinvoice' => 'rec',
            'fcpopaypal' => 'wlt',
            'fcpopaypal_express' => 'wlt',
            'fcpoklarna' => 'fnc',
            'fcpoklarna_invoice' => 'fnc',
            'fcpoklarna_directdebit' => 'fnc',
            'fcpoklarna_installments' => 'fnc',
            'fcpobarzahlen' => 'csh',
            'fcpopaydirekt' => 'wlt',
            'fcpopo_bill' => 'fnc',
            'fcpopo_debitnote' => 'fnc',
            'fcpopo_installment' => 'fnc',
            'fcporp_bill' => 'fnc',
            'fcpocreditcard_iframe' => 'cc',
            'fcpobillsafe' => 'fnc',
            'fcpoamazonpay' => 'wlt',
            'fcpo_secinvoice' => 'rec',
            'fcpopl_secinvoice' => 'fnc',
            'fcpopl_secinstallment' => 'fnc',
            'fcpopl_secdebitnote' => 'fnc',
            'fcpopaydirekt_express' => 'wlt',
            'fcpo_sofort' => 'sb',
            'fcpo_giropay' => 'sb',
            'fcpo_eps' => 'sb',
            'fcpo_pf_finance' => 'sb',
            'fcpo_pf_card' => 'sb',
            'fcpo_ideal' => 'sb',
            'fcpo_p24' => 'sb',
            'fcpo_bancontact' => 'sb',
            'fcporp_debitnote' => 'fnc',
            'fcpo_alipay' => 'wlt',
            'fcpo_trustly' => 'sb',
            'fcpo_wechatpay' => 'wlt',
            'fcpo_apple_pay' => 'wlt',
            'fcporp_installment' => 'fnc',
        );

        if (isset($aAbbreviations[$sPaymentId])) {
            $sAbbr = $aAbbreviations[$sPaymentId];
        }

        return $sAbbr;
    }

    /**
     * Returns matching subtypes for given payment id
     *
     * @param string $sPaymentId
     * @return string
     */
    protected function _getPaymentSubtype($sPaymentId) {
        $sAbbr = '';

        $aAbbreviations = array(
            'fcpocreditcard' => 'V,M,A,D,J,O,U,B',
            'fcpocashondel' => 'CSH', // has no subtype use clearingtype instead
            'fcpodebitnote' => 'ELV', // has no subtype use clearingtype instead
            'fcpopayadvance' => 'VOR', // has no subtype use clearingtype instead
            'fcpoinvoice' => 'REC', // has no subtype use clearingtype instead
            'fcpopaypal' => 'PPE',
            'fcpopaypal_express' => 'PPE',
            'fcpoklarna_invoice' => 'KIV',
            'fcpoklarna' => 'KLV',
            'fcpoklarna_directdebit' => 'KDD',
            'fcpoklarna_installments' => 'KIS',
            'fcpobarzahlen' => 'BZN',
            'fcpopaydirekt' => 'PDT',
            'fcpopaydirekt_express' => 'PDT',
            'fcpopo_bill' => 'PYV',
            'fcpopo_debitnote' => 'PYD',
            'fcpopo_installment' => 'PYS',
            'fcporp_bill' => 'RPV',
            'fcporp_debitnote' => 'RPD',
            'fcporp_installment' => 'RPS',
            'fcpocreditcard_iframe' => 'V,M,A,D,J,O,U,B',
            'fcpoamazonpay' => 'AMZ',
            'fcpo_secinvoice' => 'POV',
            'fcpopl_secinvoice' => 'PIV',
            'fcpopl_secinstallment' => 'PIN',
            'fcpopl_secdebitnote' => 'PDD',
            'fcpo_sofort' => 'PNT',
            'fcpo_giropay' => 'GPY',
            'fcpo_eps' => 'EPS',
            'fcpo_pf_finance' => 'PFF',
            'fcpo_pf_card' => 'PFC',
            'fcpo_ideal' => 'IDL',
            'fcpo_p24' => 'P24',
            'fcpo_bancontact' => 'BCT',
            'fcpo_alipay' => 'ALP',
            'fcpo_trustly' => 'TRL',
            'fcpo_wechatpay' => 'WCP',
            'fcpo_apple_pay' => 'APL',
        );

        if (isset($aAbbreviations[$sPaymentId])) {
            $sAbbr = strtolower($aAbbreviations[$sPaymentId]);
        }

        return $sAbbr;
    }

    /**
     * Returns payment countries
     *
     * @param  object $oPayment
     * @return string
     */
    protected function _getPaymentCountries($oPayment) 
    {
        $aCountries = $oPayment->getCountries();
        $sCountries = '';
        foreach ($aCountries as $sCountryId) {
            $oCountry = oxNew('oxcountry');
            if ($oCountry->load($sCountryId)) {
                $sCountries .= $oCountry->oxcountry__oxisoalpha2->value . ',';
            }
        }
        $sCountries = rtrim($sCountries, ',');
        return $sCountries;
    }

    /**
     * Returns the configured list of forwardings
     *
     * @param  void
     * @return array
     */
    protected function _getForwardings() 
    {
        $aForwardings = array();
        $oForwarding = $this->_oFcpoHelper->getFactoryObject('fcpoforwarding');
        $aForwardingsList = $oForwarding->fcpoGetExistingForwardings();

        foreach ($aForwardingsList as $oCurrentForwarding) {
            $aForwardings[] = array(
                'status' => $oCurrentForwarding->sPayoneStatusId,
                'url' => $oCurrentForwarding->sForwardingUrl,
                'timeout' => $oCurrentForwarding->iForwardingTimeout,
            );
        }

        return $aForwardings;
    }

    /**
     * Returns the configured mappings
     *
     * @param void
     * @return array
     */
    protected function _getMappings() {
        $aMappings = array();

        $oMapping = oxNew('fcpomapping');
        $aExistingMappings = $oMapping->fcpoGetExistingMappings();

        foreach ($aExistingMappings as $oCurrentMapping) {
            $sAbbr = $this->_getPaymentAbbreviation($oCurrentMapping->sPaymentType);
            $sSubType = $this->_getPaymentSubType($oCurrentMapping->sPaymentType);
            $aSubTypes = explode(',',$sSubType);
            if (array_key_exists($sAbbr, $aMappings) === false) {
                $aMappings[$sAbbr] = array();
            }
            foreach ($aSubTypes as $sType) {
                $aMappings[$sAbbr][$sType][] = array(
                    'from' => $oCurrentMapping->sPayoneStatusId,
                    'to' => $oCurrentMapping->sShopStatusId,
                    'name' => $oCurrentMapping->sPaymentType,
                );
            }
        }

        return $aMappings;
    }

    /**
     * Returns a list of available modules and their versions
     *
     * @param  void
     * @return array
     */
    protected function _getModuleInfo() 
    {
        $iVersion = $this->_oFcpoHelper->fcpoGetIntShopVersion();
        if ($iVersion < 4600) {
            $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
            $aModules = $oConfig->getConfigParam('aModules');
            foreach ($aModules as $sKey => $sValue) {
                $aModules[$sKey] = '<![CDATA[' . $sValue . ']]>';
            }
        } else {
            $sModulesDir = $this->getConfig()->getModulesDir();
            /** @var oxmodulelist $oModuleList */
            $oModuleList = $this->_oFcpoHelper->getFactoryObject("oxModuleList");
            $aOxidModules = $oModuleList->getModulesFromDir($sModulesDir);
            $aModules = array();
            foreach ($aOxidModules as $oModule) {
                $aModules[$oModule->getId()] = $oModule->getInfo('version');
            }
        }
        return $aModules;
    }

    /**
     * Converts simple array to multiline text. Returns this text.
     *
     * @param array $aInput Array with text
     *
     * @return string
     */
    protected function _arrayToMultiline($aInput) 
    {
        $sVal = '';
        if (is_array($aInput)) {
            $sVal = implode("\n", $aInput);
        }

        return $sVal;
    }

}
