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
 
class fcPayOneUser extends fcPayOneUser_parent
{

    /**
     * Helper object for dealing with different shop versions
     *
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * List of userflag ids of user
     * @var array
     */
    protected $_aUserFlags = null;

    /**
     * Blocked payments for user (unvalidated)
     * @var array
     */
    protected $_aBlockedPaymentIds = array();

    /**
     * Forbidden payments for user (validated)
     * @var array
     */
    protected $_aForbiddenPaymentIds = array();

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
     * Overwriting load method for directly setting user flags onload
     *
     * @param $sOXID
     * @return mixed
     */
    public function load($sOXID) {
        $mReturn = parent::load($sOXID);
        if ($mReturn !== false) {
            $this->_fcpoSetUserFlags();
        }

        return $mReturn;
    }

    /**
     * Returns current userflags
     *
     * @return array
     */
    public function fcpoGetFlagsOfUser() {
        if ($this->_aUserFlags === null) {
            $this->_fcpoSetUserFlags();
        }
        return $this->_aUserFlags;
    }

    /**
     * Returns if given payment is allowed by flags
     *
     * @param $sPaymentId
     * @return bool
     */
    public function fcpoPaymentCurrentlyAllowedByFlags($sPaymentId) {
        $aForbiddenPayments = $this->fcpoGetForbiddenPaymentIds();
        $blReturn = (in_array($sPaymentId, $aForbiddenPayments)) ? false : true;

        return $blReturn;
    }

    /**
     * Returns an array of forbidden paymentids
     *
     * @param void
     * @return array
     */
    public function fcpoGetForbiddenPaymentIds() {
        $this->_fcpoAddForbiddenByUserFlags();

        return $this->_aForbiddenPaymentIds;
    }

    /**
     * Adds (or refreshes) a payone user flag
     *
     * @param $oUserFlag
     * @return void
     */
    public function fcpoAddPayoneUserFlag($oUserFlag) {
        $oDb = $this->_oFcpoHelper->fcpoGetDb();
        $oUtilsObject = $this->_oFcpoHelper->getFactoryObject('oxUtilsObject');
        $sUserFlagId = $oUserFlag->fcpouserflags__oxid->value;
        $sUserId = $this->getId();
        $sNewOxid = $oUtilsObject->generateUId();

        $sQuery = "
          REPLACE INTO fcpouser2flag
          (
            OXID,
            OXUSERID,
            FCPOUSERFLAGID,
            OXTIMESTAMP
          )
          VALUES
          (
            ".$oDb->quote($sNewOxid).",
            ".$oDb->quote($sUserId).",
            ".$oDb->quote($sUserFlagId).",
            NOW()
          )
        ";

        $oDb->Execute($sQuery);
    }

    /**
     * Adds assigned payone userflags to user
     *
     * @param $aForbiddenPayments
     * @return void
     */
    protected function _fcpoAddForbiddenByUserFlags() {
        $aUserFlags = $this->fcpoGetFlagsOfUser();
        foreach ($aUserFlags as $oUserFlag) {
            $aPaymentsNotAllowedByFlag = $oUserFlag->fcpoGetBlockedPaymentIds();
            $this->_aForbiddenPaymentIds = array_merge($this->_aForbiddenPaymentIds, $aPaymentsNotAllowedByFlag);
        }
    }

    /**
     * Sets current flags of user
     *
     * @param void
     * @return array
     */
    protected function _fcpoSetUserFlags() {
        $this->_aUserFlags = array();
        $aUserFlagInfos = $this->_fcpoGetUserFlagInfos();
        foreach ($aUserFlagInfos as $oUserFlagInfo) {
            $sOxid = $oUserFlagInfo->sOxid;
            $sUserFlagId = $oUserFlagInfo->sUserFlagId;
            $sTimeStamp = $oUserFlagInfo->sTimeStamp;



            $oUserFlag = oxNew('fcpouserflag');
            if ($oUserFlag->load($sUserFlagId)) {
                $oUserFlag->fcpoSetAssignId($sOxid);
                $oUserFlag->fcpoSetTimeStamp($sTimeStamp);
                $this->_aUserFlags[$sUserFlagId] = $oUserFlag;
            }
        }
    }

    /**
     * Returns an array of userflag infos mandatory for
     * determing effects
     *
     * @param void
     * @return array
     */
    protected function _fcpoGetUserFlagInfos() {
        $aUserFlagInfos = array();
        $oDb = $this->_oFcpoHelper->fcpoGetDb(true);
        $sUserId = $this->getId();
        $sQuery = "
          SELECT
            OXID, 
            FCPOUSERFLAGID,
            FCPODISPLAYMESSAGE,
            OXTIMESTAMP
          FROM 
            fcpouser2flag 
          WHERE
            OXUSERID=".$oDb->quote($sUserId)."
        ";
        $aRows = $oDb->getAll($sQuery);

        foreach ($aRows as $aRow) {
            $oUserFlag = new stdClass();
            $oUserFlag->sOxid = $aRow['OXID'];
            $oUserFlag->sUserFlagId = $aRow['FCPOUSERFLAGID'];
            $oUserFlag->sTimeStamp = $aRow['OXTIMESTAMP'];
            $oUserFlag->sDisplayMessage = $aRow['FCPODISPLAYMESSAGE'];
            $aUserFlagInfos[] = $oUserFlag;
        }

        return $aUserFlagInfos;
    }

    /**
     * Logs user into session
     *
     * @param void
     * @return void
     */
    protected function _fcpoLogMeIn($sUserId=null) {
        if ($sUserId === null) {
            $sUserId = $this->getId();
        }
        $this->_oFcpoHelper->fcpoSetSessionVariable('usr', $sUserId);
    }

    /**
     * Method manages adding/merging userdata
     *
     * @param array $aResponse
     * @return void
     */
    public function fcpoSetAmazonOrderReferenceDetailsResponse($aResponse) {
        $sAmazonEmailAddress = $this->_fcpoAmazonEmailEncode($aResponse['add_paydata[email]']);
        $aResponse['add_paydata[email]'] = $sAmazonEmailAddress;
        $this->_fcpoAddOrUpdateAmazonUser($aResponse);
    }

    /**
     * Returns country iso code of users country
     *
     * @param int $iVersion
     * @return string
     */
    public function fcpoGetUserCountryIso($iVersion=2)
    {
        $oCountry = $this->_oFcpoHelper->getFactoryObject('oxCountry');
        if(!$oCountry->load($this->oxuser__oxcountryid->value)) {
            return '';
        }
        $sField = "oxcountry__oxisoalpha".$iVersion;

        return $oCountry->$sField->value;
    }

    /**
     * Makes this Email unique to be able to handle amazon users different from standard users
     * Currently the email address simply gets a prefix
     *
     * @param $sEmail
     * @return string
     */
    protected function _fcpoAmazonEmailEncode($sEmail) {
        $oViewConf = $this->_oFcpoHelper->getFactoryObject('oxViewConfig');

        return $oViewConf->fcpoAmazonEmailEncode($sEmail);
    }

    /**
     * Returns the origin email of an amazon encoded email
     *
     * @param $sEmail
     * @return string
     */
    protected function _fcpoAmazonEmailDecode($sEmail) {
        $oViewConf = $this->_oFcpoHelper->getFactoryObject('oxViewConfig');

        return $oViewConf->fcpoAmazonEmailDecode($sEmail);
    }

    /**
     * Checks if a user should be added or updated, redirects to matching method
     * and logs user in
     *
     * @param $aResponse
     * @return void
     */
    protected function _fcpoAddOrUpdateAmazonUser($aResponse) {
        $sAmazonEmailAddress = $aResponse['add_paydata[email]'];
        $blUserExists = $this->_fcpoUserExists($sAmazonEmailAddress);
        if ($blUserExists) {
            $sUserId = $this->_fcpoUpdateAmazonUser($aResponse);
        } else {
            $sUserId = $this->_fcpoAddAmazonUser($aResponse);
        }
        // logoff and on again
        $this->_fcpoLogMeIn($sUserId);
    }

    /**
     * Method adds a new amazon user into OXIDs user system. User won't get a password
     *
     * @param $aResponse
     * @return string
     */
    protected function _fcpoAddAmazonUser($aResponse) {
        $aStreetParts = $this->_fcpoSplitStreetAndStreetNr($aResponse['add_paydata[billing_street]']);
        $sCountryId = $this->_fcpoGetCountryIdByIso2($aResponse['add_paydata[billing_country]']);

        $oUser = $this->_oFcpoHelper->getFactoryObject('oxUser');
        $sUserOxid = $oUser->getId();
        $oUser->oxuser__oxusername = new oxField($aResponse['add_paydata[email]']);
        $oUser->oxuser__oxstreet = new oxField($aStreetParts['street']);
        $oUser->oxuser__oxstreetnr = new oxField($aStreetParts['streetnr']);
        $oUser->oxuser__oxzip = new oxField($aResponse['add_paydata[billing_zip]']);
        $oUser->oxuser__oxfon = new oxField($aResponse['add_paydata[billing_telephonenumber]']);
        $oUser->oxuser__oxfname = new oxField($aResponse['add_paydata[billing_firstname]']);
        $oUser->oxuser__oxlname = new oxField($aResponse['add_paydata[billing_lastname]']);
        $oUser->oxuser__oxcity = new oxField($aResponse['add_paydata[billing_city]']);
        $oUser->oxuser__oxcompany = new oxField($aResponse['add_paydata[billing_company]']);
        $oUser->oxuser__oxcountryid = new oxField($sCountryId);
        $oUser->addToGroup('oxidnotyetordered');

        $oUser->save();

        // add and set deliveryaddress
        $this->_fcpoAddDeliveryAddress($aResponse, $sUserOxid);

        // handle the multi purpose address field
        $this->_fcpoHandleAmazonPayMultiPurposeField($aResponse);

        return $sUserOxid;
    }

    /**
     * Updating user. Checking current address, if different add new address as additional address to user
     * iff current address is not known until now
     *
     * @param $aResponse
     * @return string
     */
    protected function _fcpoUpdateAmazonUser($aResponse) {
        $sAmazonEmailAddress = $aResponse['add_paydata[email]'];
        $sUserOxid = $this->_fcpoGetUserOxidByEmail($sAmazonEmailAddress);

        $oUser = $this->_oFcpoHelper->getFactoryObject('oxUser');
        $oUser->load($sUserOxid);

        $aStreetParts = $this->_fcpoSplitStreetAndStreetNr($aResponse['add_paydata[billing_street]']);
        $sCountryId = $this->_fcpoGetCountryIdByIso2($aResponse['add_paydata[billing_country]']);

        $oUser->oxuser__oxusername = new oxField($aResponse['add_paydata[email]']);
        $oUser->oxuser__oxstreet = new oxField($aStreetParts['street']);
        $oUser->oxuser__oxstreetnr = new oxField($aStreetParts['streetnr']);
        $oUser->oxuser__oxzip = new oxField($aResponse['add_paydata[billing_zip]']);
        $oUser->oxuser__oxfon = new oxField($aResponse['add_paydata[billing_telephonenumber]']);
        $oUser->oxuser__oxfname = new oxField(trim($aResponse['add_paydata[billing_firstname]']));
        $oUser->oxuser__oxlname = new oxField(trim($aResponse['add_paydata[billing_lastname]']));
        $oUser->oxuser__oxcity = new oxField($aResponse['add_paydata[billing_city]']);
        $oUser->oxuser__oxcompany = new oxField($aResponse['add_paydata[billing_company]']);
        $oUser->oxuser__oxcountryid = new oxField($sCountryId);
        $oUser->addToGroup('oxidnotyetordered');

        $oUser->save();

        // add and set deliveryaddress
        $this->_fcpoAddDeliveryAddress($aResponse, $sUserOxid);

        // handle the multi purpose address field
        $this->_fcpoHandleAmazonPayMultiPurposeField($aResponse);

        return $sUserOxid;
    }

    /**
     * Method handles the multi purpose Address line 1 field from AmazonPay.
     * Depending on the transmitted fields, and the values, the user fields are updated accordingly.
     *
     * @param $aResponse
     * @return void
     */
    public function _fcpoHandleAmazonPayMultiPurposeField($aResponse)
    {
        $oDelAddr = $oAddress = $this->_oFcpoHelper->getFactoryObject('oxaddress');
        $sDelAddrId = $this->_oFcpoHelper->fcpoGetSessionVariable('deladrid');
        if (!empty($sDelAddrId)) {
            $oDelAddr->load($sDelAddrId);

            if (isset($aResponse['add_paydata[shipping_pobox]'])) {
                $oDelAddr->oxaddress__oxaddinfo = new oxField($aResponse['add_paydata[shipping_pobox]']);
            }

            if (isset($aResponse['add_paydata[shipping_company]'])) {
                $sCompany = $aResponse['add_paydata[shipping_company]'];
                if (preg_match('/.*c\/o.*/i', $sCompany)) {
                    $oDelAddr->oxaddress__oxaddinfo = new oxField($sCompany);
                } elseif (preg_match('/.*[0-9]+.*/', $sCompany)) {
                    $oDelAddr->oxaddress__oxaddinfo = new oxField($sCompany);
                } else {
                    $oDelAddr->oxaddress__oxcompany = new oxField($sCompany);
                }
            }

            $oDelAddr->save();
        }
    }

    /**
     * Method adds a delivery address to user and directly set the deladrid session variable
     *
     * @param array $aResponse
     * @param string $sUserOxid
     * @return void
     */
    public function _fcpoAddDeliveryAddress($aResponse, $sUserOxid, $blFixUtf8=false) {
        if ($blFixUtf8) {
            $aResponse = array_map('utf8_decode', $aResponse);
        }
        $aStreetParts = $this->_fcpoSplitStreetAndStreetNr($aResponse['add_paydata[shipping_street]']);
        $sCountryId = $this->_fcpoGetCountryIdByIso2($aResponse['add_paydata[shipping_country]']);
        $sFirstName = trim($aResponse['add_paydata[shipping_firstname]']);
        $sLastName = trim($aResponse['add_paydata[shipping_lastname]']);

        if (empty($sLastName)) {
            $aNameParts = $this->_fcpoSplitNameParts($sFirstName);
            $sFirstName = $aNameParts['firstname'];
            $sLastName = $aNameParts['lastname'];
        }

        $oAddress = $this->_oFcpoHelper->getFactoryObject('oxaddress');
        $oAddress->oxaddress__oxuserid = new oxField($sUserOxid);
        $oAddress->oxaddress__oxaddressuserid = new oxField($sUserOxid);
        $oAddress->oxaddress__oxfname = new oxField($sFirstName);
        $oAddress->oxaddress__oxlname = new oxField($sLastName);
        $oAddress->oxaddress__oxstreet = new oxField($aStreetParts['street']);
        $oAddress->oxaddress__oxstreetnr = new oxField($aStreetParts['streetnr']);
        $oAddress->oxaddress__oxfon = new oxField($aResponse['add_paydata[shipping_telephonenumber]']);
        $oAddress->oxaddress__oxcity = new oxField($aResponse['add_paydata[shipping_city]']);
        $oAddress->oxaddress__oxcountry = new oxField($aResponse['add_paydata[shipping_country]']);
        $oAddress->oxaddress__oxcountryid = new oxField($sCountryId);
        $oAddress->oxaddress__oxzip = new oxField($aResponse['add_paydata[shipping_zip]']);
        $oAddress->oxaddress__oxaddinfo = new oxField($aResponse['add_paydata[shipping_addressaddition]']);
        $oAddress->oxaddress__oxcompany = new oxField($aResponse['add_paydata[shipping_company]']);

        // check if address exists
        $sEncodedDeliveryAddress = $oAddress->getEncodedDeliveryAddress();
        $blExists = $this->_fcpoCheckAddressExists($sEncodedDeliveryAddress);
        if ($blExists) {
            $oAddress->load($sEncodedDeliveryAddress);
        } else {
            $oAddress->setId($sEncodedDeliveryAddress);
            $oAddress->save();
        }

        $this->_oFcpoHelper->fcpoSetSessionVariable('deladrid', $sEncodedDeliveryAddress);
    }

    /**
     * Takes a complete name string and seperates into first and lastname
     *
     * @param $sSingleNameString
     * @return array
     */
    protected function _fcpoSplitNameParts($sSingleNameString) {
        $aParts = explode(' ', $sSingleNameString);
        $sLastName = array_pop($aParts);
        $sFirstName = implode(' ', $aParts);

        $aReturn['firstname'] = $sFirstName;
        $aReturn['lastname'] = $sLastName;

        $aReturn = array_map('trim', $aReturn);

        return $aReturn;
    }

    /**
     * Checks if address is already existing
     *
     * @param $sEncodedDeliveryAddress
     * @return bool
     */
    protected function _fcpoCheckAddressExists($sEncodedDeliveryAddress) {
        $oAddress = $this->_oFcpoHelper->getFactoryObject('oxaddress');
        $blReturn = false;
        if ($oAddress->load($sEncodedDeliveryAddress)) {
            $blReturn = true;
        }

        return $blReturn;
    }

    /**
     * Method splits street and streetnr from string
     *
     * @param string $sStreetAndStreetNr
     * @return array
     */
    protected function _fcpoSplitStreetAndStreetNr($sStreetAndStreetNr) {
        /**
         * @todo currently very basic by simply splitting of space
         */
        $aStreetParts = explode(' ', $sStreetAndStreetNr);
        $blReturnDefault = (
            !is_array($aStreetParts) ||
            count($aStreetParts) <= 1
        );

        if ($blReturnDefault) {
            $aReturn['street'] = $sStreetAndStreetNr;
            $aReturn['streetnr'] = '';
            return $aReturn;
        }

        $aReturn['streetnr'] = array_pop($aStreetParts);
        $aReturn['street'] = implode(' ', $aStreetParts);

        return $aReturn;
    }

    /**
     * Returns id of a countrycode
     *
     * @param $sIso2Country
     * @return string
     */
    protected function _fcpoGetCountryIdByIso2($sIso2Country) {
        $oCountry = $this->_oFcpoHelper->getFactoryObject('oxCountry');
        $sOxid = $oCountry->getIdByCode($sIso2Country);

        return $sOxid;
    }

    /**
     * Method checks if a user WITH password exists using the given email-address
     *
     * @param string $sEmailAddress
     * @param bool $blWithPasswd
     * @return bool
     */
    protected function _fcpoUserExists($sEmailAddress, $blWithPasswd=false) {
        $blReturn = false;
        $sUserOxid = $this->_fcpoGetUserOxidByEmail($sEmailAddress);
        if ($sUserOxid && !$blWithPasswd) {
            $blReturn = true;
        } elseif ($sUserOxid && $blWithPasswd) {
            $this->load($sUserOxid);
            $blReturn = ($this->oxuser__oxpassword->value) ? true : false;
        }

        return $blReturn;
    }

    /**
     * Method delivers OXID of a user by offering an email address or false if email does not exist
     *
     * @param string $sAmazonEmailAddress
     * @return mixed
     */
    protected function _fcpoGetUserOxidByEmail($sAmazonEmailAddress) {
        $oDb = $this->_oFcpoHelper->fcpoGetDb();
        $sQuery = "SELECT OXID FROM oxuser WHERE OXUSERNAME=".$oDb->quote($sAmazonEmailAddress);
        $mReturn = $oDb->GetOne($sQuery);

        return $mReturn;
    }

    /**
     * Unsetting groups
     * 
     * @param  void
     * @return void
     */
    public function fcpoUnsetGroups() 
    {
        $this->_oGroups = null;
    }

}
