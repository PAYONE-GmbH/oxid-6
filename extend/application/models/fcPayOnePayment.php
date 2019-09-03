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
class fcPayOnePayment extends fcPayOnePayment_parent
{

    /**
     * Helper object for dealing with different shop versions
     *
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * Database object instance
     *
     * @var object
     */
    protected $_oFcpoDb = null;

    /*
     * Array of all payment method IDs belonging to PAYONE
     *
     * @var array
     */
    protected static $_aPaymentTypes = array(
        'fcpoinvoice',
        'fcpopayadvance',
        'fcpodebitnote',
        'fcpocashondel',
        'fcpocreditcard',
        'fcpoonlineueberweisung',
        'fcpopaypal',
        'fcpopaypal_express',
        'fcpoklarna',
        'fcpobarzahlen',
        'fcpopaydirekt',
        'fcpopo_bill',
        'fcpopo_debitnote',
        'fcpopo_installment',
        'fcporp_bill',
        'fcpoamazonpay',
        'fcpo_secinvoice',
        'fcpopaydirekt_express',
    );
    
    protected static $_aRedirectPayments = array(
        'fcpoonlineueberweisung',
        'fcpopaypal',
        'fcpopaypal_express',
        'fcpoklarna',
        'fcpopaydirekt',
    );
    
    protected static $_aIframePaymentTypes = array(
    );
    protected static $_aFrontendApiPaymentTypes = array(
    );
    
    protected $_aPaymentsNoAuthorize = array(
        'fcpobarzahlen',
        'fcpopo_bill',
        'fcpopo_debitnote',
        'fcporp_bill',
    );

    /**
     * List of payments that are not foreseen to be shown as regular payment
     * selection
     *
     * @var array
     */
    protected $_aExpressPayments = array(
        'fcpomasterpass',
        'fcpoamazonpay',
        'fcpopaydirekt_express'
    );


    /**
     * init object construction
     * 
     * @return null
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
        $this->_oFcpoDb = oxDb::getDb();
    }

    public static function fcIsPayOnePaymentType($sPaymentId) 
    {
        $blReturn = (array_search($sPaymentId, self::$_aPaymentTypes) !== false) ? true : false;
        return $blReturn;
    }
    
    public static function fcIsPayOneRedirectType($sPaymentId) 
    {
        $blReturn = (in_array($sPaymentId, self::$_aRedirectPayments) !== false) ? true : false;
        $oHelper = oxNew('fcpohelper');

        $blDynFlaggedAsRedirectPayment =
            (bool)$oHelper->fcpoGetSessionVariable('blDynFlaggedAsRedirectPayment');
        $blUseDynamicFlag = (
            !$blReturn &&
            $blDynFlaggedAsRedirectPayment === true
        );

        if ($blUseDynamicFlag) {
            // overwrite static value
            $blReturn = $blDynFlaggedAsRedirectPayment;
        }

        return $blReturn;
    }

    /**
     * Checks if this payment is foreseen to be shown as standard
     * payment selection
     *
     * @param string $sPaymentId
     * @return bool
     */
    public function fcpoShowAsRegularPaymentSelection($sPaymentId=false)
    {
        $sPaymentId = (!$sPaymentId) ? $this->getId() : $sPaymentId;
        $blPaymentAllowedInSelection =
            !in_array($sPaymentId, $this->_aExpressPayments);

        return $blPaymentAllowedInSelection;
    }

    public static function fcIsPayOneIframePaymentType($sPaymentId) 
    {
        $blReturn = (array_search($sPaymentId, self::$_aIframePaymentTypes) !== false) ? true : false;
        return $blReturn;
    }

    public static function fcIsPayOneFrontendApiPaymentType($sPaymentId) 
    {
        $blReturn = (array_search($sPaymentId, self::$_aFrontendApiPaymentTypes) !== false) ? true : false;
        return $blReturn;
    }

    /**
     * Determines the operation mode ( live or test ) used in this order based on the payment (sub) method
     *
     * @param string $sType payment subtype ( Visa, MC, etc.). Default is ''
     * 
     * @return bool
     */
    public function fcpoGetOperationMode($sType = '') 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $blLivemode = $this->oxpayments__fcpolivemode->value;

        if ($sType != '') {
            $sPaymentId = $this->getId();

            $aMap = array(
                'fcpocreditcard' => $oConfig->getConfigParam('blFCPOCC' . $sType . 'Live'),
                'fcpoonlineueberweisung' => $oConfig->getConfigParam('blFCPOSB' . $sType . 'Live'),
            );

            if (in_array($sPaymentId, array_keys($aMap))) {
                $blLivemode = $aMap[$sPaymentId];
            }
        }

        $sReturn = ($blLivemode == true) ? 'live' : 'test';

        return $sReturn;
    }

    /**
     * Adds dynvalues to the payone payment type
     * 
     * @extend getDynValues
     * 
     * @return array dyn values
     */
    public function getDynValues() 
    {
        $aDynValues = parent::getDynValues();
        $aDynValues = $this->_fcGetDynValues($aDynValues);

        return $aDynValues;
    }

    /**
     * Returns the isoalpa of a country by offering an id
     * 
     * @param  string $sCountryId
     * @return string
     */
    public function fcpoGetCountryIsoAlphaById($sCountryId) 
    {
        $sQuery = "SELECT oxisoalpha2 FROM oxcountry WHERE oxid = " . oxDb::getDb()->quote($sCountryId);
        $sIsoAlpha = $this->_oFcpoDb->GetOne($sQuery);

        return $sIsoAlpha;
    }

    /**
     * Returns the isoalpa of a country by offering an id
     * 
     * @param  string $sCountryId
     * @return string
     */
    public function fcpoGetCountryNameById($sCountryId) 
    {
        $sQuery = "SELECT oxtitle FROM oxcountry WHERE oxid = " . oxDb::getDb()->quote($sCountryId);
        $sName = $this->_oFcpoDb->GetOne($sQuery);

        return $sName;
    }

    /**
     * Method assigns a certain mandate to an order
     * 
     * @param  string $sOrderId
     * @param  string $sMandateIdentification
     * @return void
     */
    public function fcpoAddMandateToDb($sOrderId, $sMandateIdentification) 
    {
        $sOrderId = oxDb::getDb()->quote($sOrderId);
        $sMandateIdentification = oxDb::getDb()->quote(basename($sMandateIdentification . '.pdf'));

        $sQuery = "INSERT INTO fcpopdfmandates VALUES (" . $sOrderId . ", " . $sMandateIdentification . ")";
        $this->_oFcpoDb->Execute($sQuery);
    }

    /**
     * Returns the Klarna StoreID for the current bill country
     * 
     * @return string
     */
    public function fcpoGetKlarnaStoreId() 
    {
        $oUser = $this->getUser();
        $sBillCountryId = $oUser->oxuser__oxcountryid->value;

        $sQuery = " SELECT 
                        b.fcpo_storeid 
                    FROM 
                        fcpopayment2country AS a
                    INNER JOIN
                        fcpoklarnastoreids AS b ON a.fcpo_type = b.oxid
                    WHERE 
                        a.fcpo_paymentid = 'KLV' AND 
                        a.fcpo_countryid = " . oxDb::getDb()->quote($sBillCountryId) . " 
                    LIMIT 1";
        $sStoreId = $this->_oFcpoDb->GetOne($sQuery);

        $sStoreId = ($sStoreId) ? $sStoreId : 0;

        return $sStoreId;
    }

    /**
     * Returns user paymentid 
     * 
     * @param  string $sUserOxid
     * @param  string $sPaymentType
     * @return mixed
     */
    public function fcpoGetUserPaymentId($sUserOxid, $sPaymentType) 
    {
        $oDb = oxDb::getDb();
        $sQ = 'select oxpaymentid from oxorder where oxpaymenttype=' . $oDb->quote($sPaymentType) . ' and
                oxuserid=' . $oDb->quote($sUserOxid) . ' order by oxorderdate desc';
        $sOxid = $this->_oFcpoDb->GetOne($sQ);

        return $sOxid;
    }

    /**
     * Check database if the user is allowed to use the given payment method and re
     * 
     * @param string $sSubPaymentId ID of the sub payment method ( Visa, MC, etc. )
     * @param string $sType         payment type PAYONE
     * 
     * @return bool
     */
    public function isPaymentMethodAvailableToUser($sSubPaymentId, $sType, $sUserBillCountryId, $sUserDelCountryId) 
    {
        $sBaseQuery = "SELECT COUNT(*) FROM fcpopayment2country WHERE fcpo_paymentid = '{$sSubPaymentId}' AND fcpo_type = '{$sType}'";
        if ($sUserDelCountryId !== false && $sUserBillCountryId != $sUserDelCountryId) {
            $sWhereCountry = "AND (fcpo_countryid = '{$sUserBillCountryId}' || fcpo_countryid = '{$sUserDelCountryId}')";
        } else {
            $sWhereCountry = "AND fcpo_countryid = '{$sUserBillCountryId}'";
        }
        $sQuery = "SELECT IF(({$sBaseQuery} LIMIT 1) > 0,IF(({$sBaseQuery} {$sWhereCountry} LIMIT 1) > 0,1,0),1)";

        return $this->_oFcpoDb->GetOne($sQuery);
    }

    /**
     * Adds dynvalues for debitcard payment-method
     * 
     * @param  array $aDynValues dynvalues
     * @return array dynvalues (might be modified)
     */
    protected function _fcGetDynValues($aDynValues) 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        if ((bool) $oConfig->getConfigParam('sFCPOSaveBankdata') === true) {
            if ($this->getId() == 'fcpodebitnote') {
                if (!is_array($aDynValues)) {
                    $aDynValues = array();
                }
                $oDynValue = new stdClass();
                $oDynValue->name = 'fcpo_elv_blz';
                $oDynValue->value = '';
                $aDynValues[] = $oDynValue;
                $oDynValue = new stdClass();
                $oDynValue->name = 'fcpo_elv_ktonr';
                $oDynValue->value = '';
                $aDynValues[] = $oDynValue;
                $oDynValue = new stdClass();
                $oDynValue->name = 'fcpo_elv_iban';
                $oDynValue->value = '';
                $aDynValues[] = $oDynValue;
                $oDynValue = new stdClass();
                $oDynValue->name = 'fcpo_elv_bic';
                $oDynValue->value = '';
                $aDynValues[] = $oDynValue;
            }
        }
        return $aDynValues;
    }

    /**
     * Check if a creditworthiness check has to be done
     * ( Has to be done if from boni is greater zero )
     * 
     * @return bool
     */
    public function fcBoniCheckNeeded() 
    {
        $blReturn = ($this->oxpayments__oxfromboni->value > 0) ? true : false;
        return $blReturn;
    }

    /**
     * Returns mandate text from session if available
     * 
     * @return mixed
     */
    public function fcpoGetMandateText() 
    {
        $aMandate = $this->_oFcpoHelper->fcpoGetSessionVariable('fcpoMandate');

        $blMandateTextValid = (
                $aMandate &&
                array_key_exists('mandate_status', $aMandate) !== false &&
                $aMandate['mandate_status'] == 'pending' &&
                array_key_exists('mandate_text', $aMandate) !== false
                );

        $mReturn = false;
        if ($blMandateTextValid) {
            $mReturn = urldecode($aMandate['mandate_text']);
        }

        return $mReturn;
    }

    /**
     * Returns countries assigned to given campaign id
     * 
     * @param  string $sCampaignId
     * @return array
     */
    protected function _fcGetCountries($sCampaignId) 
    {
        $aCountries = array();

        $sQuery = "SELECT fcpo_countryid FROM fcpopayment2country WHERE fcpo_paymentid = 'KLR_{$sCampaignId}'";
        $aRows = $this->_oFcpoDb->getAll($sQuery);
        foreach ($aRows as $aRow) {
            $aCountries[] = $aRow[0];
        }

        return $aCountries;
    }

    /**
     * Returning klarna campaigns
     * 
     * @param  bool $blGetAll
     * @return array
     */
    public function fcpoGetKlarnaCampaigns($blGetAll = false) 
    {
        $aStoreIds = array();

        $sQuery = "SELECT oxid, fcpo_campaign_code, fcpo_campaign_title, fcpo_campaign_language, fcpo_campaign_currency FROM fcpoklarnacampaigns ORDER BY oxid ASC";
        $aRows = $this->_oFcpoDb->getAll($sQuery);
        foreach ($aRows as $aRow) {
            $aCampaign = $this->_fcpoGetKlarnaCampaignArray($aRow);
            $blAdd = ($blGetAll) ? true : $this->_fcpoCheckKlarnaCampaignsResult($aRow[0], $aCampaign);

            if ($blAdd === true) {
                $aStoreIds[$aRow[0]] = $aCampaign;
            }
        }
        return $aStoreIds;
    }

    /**
     * Method returns campaign array on db request result
     * 
     * @param  array $aRow
     * @return array
     */
    protected function _fcpoGetKlarnaCampaignArray($aRow) 
    {
        $aCampaign = array(
            'code' => $aRow[1],
            'title' => $aRow[2],
            'language' => unserialize($aRow[3]),
            'currency' => unserialize($aRow[4]),
        );

        $aCampaign = $this->_fcpoSetArrayDefault($aCampaign, 'language');
        $aCampaign = $this->_fcpoSetArrayDefault($aCampaign, 'currency');

        return $aCampaign;
    }

    /**
     * Method evaluates result of klarna campaign data and returns if it can be added
     * 
     * @param  string $sCountryOxid
     * @param  array  $aCampaign
     * @return boolean
     */
    protected function _fcpoCheckKlarnaCampaignsResult($sCountryOxid, $aCampaign) 
    {
        $blAdd = true;

        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $sCurrLanguage = $oLang->getLanguageAbbr();
        $oUser = $this->getUser();
        $sCurrCountry = $oUser->oxuser__oxcountryid->value;
        $oCurrency = $oConfig->getActShopCurrencyObject();
        $sCurrCurrency = $oCurrency->name;

        $aConnectedCountries = $this->_fcGetCountries($sCountryOxid);
        $blAdd = $this->_fcpoCheckAddCampaign($blAdd, $sCurrCountry, $aConnectedCountries);
        $blAdd = $this->_fcpoCheckAddCampaign($blAdd, $sCurrLanguage, $aCampaign['language']);
        $blAdd = $this->_fcpoCheckAddCampaign($blAdd, $sCurrCurrency, $aCampaign['currency']);

        return $blAdd;
    }

    /**
     * Sets add flag to false if conditions doesn't match
     * 
     * @param  boolean $blAdd
     * @param  string  $sNeedle
     * @param  array   $aHaystack
     * @return boolean
     */
    protected function _fcpoCheckAddCampaign($blAdd, $sNeedle, $aHaystack) 
    {
        if (in_array($sNeedle, $aHaystack) === false) {
            $blAdd = false;
        }

        return $blAdd;
    }

    /**
     * Sets given index to empty array if no array has been detected
     * 
     * @param  array  $aCampaign
     * @param  string $sIndex
     * @return array
     */
    protected function _fcpoSetArrayDefault($aCampaign, $sIndex) 
    {
        if (!is_array($aCampaign[$sIndex])) {
            $aCampaign[$sIndex] = array();
        }

        return $aCampaign;
    }

    /**
     * Determines the operation mode ( live or test ) used for this payment based on payment or form data
     *
     * @param object $oPayment  payment object
     * @param string $aDynvalue form data
     * 
     * @return string
     */
    public function fcpoGetMode($aDynvalue) 
    {
        $sReturn = '';
        $sId = $this->getId();
        $blIdAffected = in_array($sId, array('fcpocreditcard', 'fcpoonlineueberweisung'));

        if ($blIdAffected) {
            $aMap = array(
                'fcpocreditcard' => $aDynvalue['fcpo_ccmode'],
                'fcpoonlineueberweisung' => $this->fcpoGetOperationMode($aDynvalue['fcpo_sotype']),
            );

            $sReturn = $aMap[$sId];
        }

        return $sReturn;
    }

    /**
     * Returns a list of payment types
     * 
     * @param  void
     * @return array
     */
    public function fcpoGetPayonePaymentTypes() 
    {
        $aPaymentTypes = array();

        $sQuery = "SELECT oxid, oxdesc FROM oxpayments WHERE fcpoispayone = 1";
        $aRows = $this->_oFcpoDb->getAll($sQuery);
        foreach ($aRows as $aRow) {
            $sOxid = (isset($aRow['oxid'])) ? $aRow['oxid'] : $aRow[0];
            $sDesc = (isset($aRow['oxdesc'])) ? $aRow['oxdesc'] : $aRow[1];

            $oPaymentType = new stdClass();
            $oPaymentType->sId = $sOxid;
            $oPaymentType->sTitle = $sDesc;
            $aPaymentTypes[] = $oPaymentType;
        }

        return $aPaymentTypes;
    }

    /**
     * Returning red payments
     * 
     * @param  void
     * @return string
     */
    public function fcpoGetRedPayments() 
    {
        $sPayments = '';
        $sQuery = 'SELECT oxid FROM oxpayments WHERE fcpoispayone = 1 AND oxfromboni <= 100';
        $aRows = $this->_oFcpoDb->getAll($sQuery);
        foreach($aRows as $aRow) {
            $sPayment = (isset($aRow[0])) ? $aRow[0] : $aRow['oxid'];
            $sPayments .= $sPayment . ',';
        }
        $sPayments = rtrim($sPayments, ',');

        return $sPayments;
    }

    /**
     * Returning yellow payments
     * 
     * @param  void
     * @return void
     */
    public function fcpoGetYellowPayments() 
    {
        $sPayments = '';
        $sQuery = 'SELECT oxid FROM oxpayments WHERE fcpoispayone = 1 AND oxfromboni > 100 AND oxfromboni <= 300';
        $aRows = $this->_oFcpoDb->getAll($sQuery);
        foreach($aRows as $aRow) {
            $sPayment = (isset($aRow[0])) ? $aRow[0] : $aRow['oxid'];
            $sPayments .= $sPayment . ',';
        }
        $sPayments = rtrim($sPayments, ',');

        return $sPayments;
    }
    
    /**
     * Public getter for checking if current payment is allowed for authorization
     * 
     * @param  void
     * @return bool
     */
    public function fcpoAuthorizeAllowed() 
    {
        $sPaymentId = $this->oxpayments__oxid->value;
        $blCurrentPaymentAffected = in_array($sPaymentId, $this->_aPaymentsNoAuthorize);
        $blAllowed = ($blCurrentPaymentAffected) ? false : true;
        
        return $blAllowed;
    }

}
