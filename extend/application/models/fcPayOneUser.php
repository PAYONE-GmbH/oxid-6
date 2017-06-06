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
     * Sets the credit-worthiness of the user
     *
     * @param array $aResponse response of a API request
     *
     * @return null
     */
    protected function fcpoSetBoni($aResponse) 
    {
        $boni = 100;
        if ($aResponse['scorevalue']) {
            $boni = $aResponse['scorevalue'];
        } else {
            $aResponse = $this->_fcpoCheckUseFallbackBoniversum($aResponse);
            $aMap = array('G' => 500, 'Y' => 300, 'R' => 100);
            if (isset($aMap[$aResponse['score']])) {
                $boni = $aMap[$aResponse['score']];
            }
        }

        $this->oxuser__oxboni->value = $boni;

        $blValidResponse = ($aResponse && is_array($aResponse) && array_key_exists('fcWrongCountry', $aResponse) === false);

        if ($blValidResponse) {
            $this->oxuser__fcpobonicheckdate = new oxField(date('Y-m-d H:i:s'));
        }

        $this->save();
    }

    /**
     * Parses response and set fallback if conditions match
     *
     * @param $aResponse
     * @return array
     */
    protected function _fcpoCheckUseFallbackBoniversum($aResponse) {
        $oConfig = $this->getConfig();
        $sScore = $aResponse['score'];
        $sAddresscheckType = $this->_fcpoGetAddressCheckType();

        $blUseFallBack = (
            $sScore == 'U' &&
            in_array($sAddresscheckType, array('BB', 'PB'))
        );

        if ($blUseFallBack) {
            $sFCPOBoniversumFallback = $oConfig->getConfigParam('sFCPOBoniversumFallback');
            $aResponse['score'] = $sFCPOBoniversumFallback;
        }

        return $aResponse;
    }

    /**
     * Check, correct and return addresschecktype
     *
     * @param void
     * @return string
     */
    protected function _fcpoGetAddressCheckType() {
        $oConfig = $this->getConfig();
        $sBoniCheckType = $oConfig->getConfigParam('sFCPOBonicheck');
        $sAddressCheckType = $oConfig->getConfigParam('sFCPOAddresscheck');

        if ($sBoniCheckType == 'CE') {
            $sAddressCheckType = 'PB';
        }

        return $sAddressCheckType;
    }


    /**
     * Check if the credit-worthiness of the user has to be checked again
     *
     * @return bool
     */
    protected function isNewBonicheckNeeded() 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sTimeLastCheck = strtotime($this->oxuser__fcpobonicheckdate->value);
        $iEnduranceBoniCheck = (int) $oConfig->getConfigParam('sFCPODurabilityBonicheck');
        $sTimeout = (time() - (60 * 60 * 24 * $iEnduranceBoniCheck));

        $blReturn = ($sTimeout > $sTimeLastCheck) ? true : false;

        return $blReturn;
    }

    /**
     * Check if the current basket sum exceeds the minimum sum for the credit-worthiness check
     *
     * @return bool
     */
    protected function isBonicheckNeededForBasket() 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $iStartlimitBonicheck = $oConfig->getConfigParam('sFCPOStartlimitBonicheck');

        $blReturn = true;
        if ($iStartlimitBonicheck && is_numeric($iStartlimitBonicheck)) {
            $oSession = $this->_oFcpoHelper->fcpoGetSession();
            $oBasket = $oSession->getBasket();
            $oPrice = $oBasket->getPrice();

            if ($oPrice->getBruttoPrice() < $iStartlimitBonicheck) {
                $blReturn = false;
            }
        }

        return $blReturn;
    }

    /**
     * Check if the credit-worthiness has to be checked
     *
     * @return bool
     */
    protected function isBonicheckNeeded() 
    {
        $blBoniCheckNeeded = (
            (
                $this->oxuser__oxboni->value == $this->getBoni() ||
                $this->isNewBonicheckNeeded()
            ) &&
            $this->isBonicheckNeededForBasket()
        );

        return $blBoniCheckNeeded;
    }

    public function checkAddressAndScore($blCheckAddress = true, $blCheckBoni = true) {
        // in general we assume that everything is fine with score and address
        $blBoniChecked = $blAddressValid = true;

        // let's see what should be checked
        if ($blCheckBoni) {
            $blBoniChecked = $this->_fcpoPerformBoniCheck();
        }
        if ($blCheckAddress) {
            $blAddressValid = $this->_fcpoPerformAddressCheck();
        }

        // merge results
        $blChecksValid = ($blBoniChecked && $blAddressValid);

        return $blChecksValid;
    }

    /**
     * Performing address check
     *
     * @param void
     * @return bool
     */
    protected function _fcpoPerformAddressCheck() {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sFCPOAddresscheck = $this->_fcpoGetAddresscheckSetting();
        // early return a success if addresscheck is inactive
        if (!$sFCPOAddresscheck) return true;

        // get more addresscheck related settings
        $blFCPOCorrectAddress = (bool) $oConfig->getConfigParam('blFCPOCorrectAddress');
        $blFCPOCheckDelAddress = (bool) $oConfig->getConfigParam('blFCPOCheckDelAddress');

        // perform validations
        $blIsValidAddress = $this->_fcpoValidateAddress($blFCPOCorrectAddress);
        $blIsValidAddress = $this->_fcpoValidateDelAddress($blIsValidAddress, $blFCPOCheckDelAddress);

        return $blIsValidAddress;
    }

    /**
     * Returns addresscheck setting or false if inactive
     *
     * @param void
     * @return mixed bool/string
     */
    protected function _fcpoGetAddresscheckSetting() {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sFCPOAddresscheck = $oConfig->getConfigParam('sFCPOAddresscheck');
        $mFCPOAddresscheck = ($sFCPOAddresscheck == 'NO') ? false : $sFCPOAddresscheck;

        return $mFCPOAddresscheck;
    }

    /**
     * Performing boni check on user
     *
     * @param void
     * @return void
     */
    protected function _fcpoPerformBoniCheck() {
        $sFCPOBonicheck = $this->_fcpoGetBoniSetting();
        $blBoniCheckNeeded = $this->isBonicheckNeeded();

        // early return as success if bonicheck is inactive or not needed
        if (!$sFCPOBonicheck || !$blBoniCheckNeeded) return true;

        return $this->_fcpoValidateBoni();
    }

    /**
     * Returns boni setting or false if inactive
     *
     * @param void
     * @return mixed bool/string
     */
    protected function _fcpoGetBoniSetting() {
        // get raw configured setting
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sFCPOBonicheck = $oConfig->getConfigParam('sFCPOBonicheck');

        // multiple inactivity checks due to php is a non type checking language
        $blBoniInactive = ($sFCPOBonicheck == -1 || $sFCPOBonicheck == '-1' || !$sFCPOBonicheck);

        // sum it up
        $mFCPOBonicheck = ($blBoniInactive) ? false : $sFCPOBonicheck;

        return $mFCPOBonicheck;
    }

    /**
     * Validating delivery address
     * 
     * @param  bool $blIsValidAddress
     * @param  bool $blFCPOCheckDelAddress
     * @return boolean
     */
    protected function _fcpoValidateDelAddress($blIsValidAddress, $blFCPOCheckDelAddress) 
    {
        if ($blIsValidAddress && $blFCPOCheckDelAddress === true) {
            //check delivery address
            $oPORequest = $this->_oFcpoHelper->getFactoryObject('fcporequest');
            $aResponse = $oPORequest->sendRequestAddresscheck($this, true);

            if ($aResponse === false || $aResponse === true) {
                // false = No deliveryaddress given
                // true = Address-check has been skipped because the address has been checked before
                return true;
            }

            $blIsValidAddress = $this->fcpoIsValidAddress($aResponse, false);
        }

        return $blIsValidAddress;
    }

    /**
     * Validates address by requesting payone
     *
     * @param string $sFCPOBonicheck
     * @param bool $blCheckedBoni
     * @param bool $blFCPOCorrectAddress
     * @return bool
     */
    protected function _fcpoValidateAddress($blFCPOCorrectAddress) {
        //check billing address
        $oPORequest = $this->_oFcpoHelper->getFactoryObject('fcporequest');
        $aResponse = $oPORequest->sendRequestAddresscheck($this);

        if ($aResponse === true) {
            // check has been performed recently
            $blIsValidAddress = true;
        } else {
            // address check has been triggered - validate the response
            $blIsValidAddress = $this->fcpoIsValidAddress($aResponse, $blFCPOCorrectAddress);
        }

        return $blIsValidAddress;
    }

    /**
     * Requesting for boni of user if conditions are alright
     *
     * @param void
     * @return void
     */
    protected function _fcpoValidateBoni() {
        // Consumerscore
        $oPORequest = $this->_oFcpoHelper->getFactoryObject('fcporequest');
        $aResponse = $oPORequest->sendRequestConsumerscore($this);
        $this->fcpoSetBoni($aResponse);

        return true;
    }

    /**
     * Overrides oxid standard method getBoni()
     * Sets it to value defined in the admin area of PAYONE if it was configured
     *
     * @return int
     * @extend getBoni()
     */
    public function getBoni() 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $iDefaultBoni = $oConfig->getConfigParam('sFCPODefaultBoni');
        if ($iDefaultBoni !== null && is_numeric($iDefaultBoni) === true) {
            return $iDefaultBoni;
        }
        return parent::getBoni();
    }

    /**
     * Checks if the address given by the user matches the address returned by the PAYONE addresscheck API request
     *
     * @return bool
     */
    protected function fcpoIsValidAddress($aResponse, $blCorrectUserAddress) {
        $blEarlyValidation = (
            $aResponse &&
            is_array($aResponse) &&
            array_key_exists('fcWrongCountry', $aResponse) &&
            $aResponse['fcWrongCountry'] === true
        );

        // early return on quick check
        if ($blEarlyValidation) return true;

        // dig deeper, do corrections if configured
        $blReturn = $this->_fcpoValidateResponse($aResponse, $blCorrectUserAddress);

        return $blReturn;
    }

    /**
     * Validating response of address check
     * 
     * @param  array $aResponse
     * @param  bool  $blCorrectUserAddress
     * @return boolean
     */
    protected function _fcpoValidateResponse($aResponse, $blCorrectUserAddress) 
    {
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $oUtilsView = $this->_oFcpoHelper->fcpoGetUtilsView();

        if ($aResponse['status'] == 'VALID') {
            $blReturn = $this->_fcpoValidateUserDataByResponse($aResponse, $blCorrectUserAddress);
            return $blReturn;
        } elseif ($aResponse['status'] == 'INVALID') {
            $sErrorMsg = $oLang->translateString('FCPO_ADDRESSCHECK_FAILED1') . $aResponse['customermessage'] . $oLang->translateString('FCPO_ADDRESSCHECK_FAILED2');
            $oUtilsView->addErrorToDisplay($sErrorMsg, false, true);
            return false;
        } elseif ($aResponse['status'] == 'ERROR') {
            $sErrorMsg = $oLang->translateString('FCPO_ADDRESSCHECK_FAILED1') . $aResponse['customermessage'] . $oLang->translateString('FCPO_ADDRESSCHECK_FAILED2');
            $oUtilsView->addErrorToDisplay($sErrorMsg, false, true);
            return false;
        }
    }

    /**
     * Validate user data against request response and correct address if configured
     * 
     * @param  array $aResponse
     * @param  bool  $blCorrectUserAddress
     * @return boolean
     */
    protected function _fcpoValidateUserDataByResponse($aResponse, $blCorrectUserAddress) 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $oUtilsView = $this->_oFcpoHelper->fcpoGetUtilsView();
        $mPersonstatus = $oConfig->getConfigParam('blFCPOAddCheck' . $aResponse['personstatus']);

        if ($mPersonstatus) {
            $sErrorMsg = $oLang->translateString('FCPO_ADDRESSCHECK_FAILED1') . $oLang->translateString('FCPO_ADDRESSCHECK_' . $aResponse['personstatus']) . $oLang->translateString('FCPO_ADDRESSCHECK_FAILED2');
            $oUtilsView->addErrorToDisplay($sErrorMsg, false, true);
            return false;
        } else {
            if ($blCorrectUserAddress) {
                if ($aResponse['firstname']) {
                    $this->oxuser__oxfname = new oxField($aResponse['firstname']);
                }
                if ($aResponse['lastname']) {
                    $this->oxuser__oxlname = new oxField($aResponse['lastname']);
                }
                if ($aResponse['streetname']) {
                    $this->oxuser__oxstreet = new oxField($aResponse['streetname']);
                }
                if ($aResponse['streetnumber']) {
                    $this->oxuser__oxstreetnr = new oxField($aResponse['streetnumber']);
                }
                if ($aResponse['zip']) {
                    $this->oxuser__oxzip = new oxField($aResponse['zip']);
                }
                if ($aResponse['city']) {
                    $this->oxuser__oxcity = new oxField($aResponse['city']);
                }
                $this->save();
            }
            // Country auch noch ?!? ( umwandlung iso nach id )
            // $this->oxuser__oxfname->value = $aResponse['country'];
            return true;
        }
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
