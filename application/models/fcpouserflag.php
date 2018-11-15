<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 16.01.18
 * Time: 11:27
 */

class fcpouserflag extends oxBase {

    /**
     * Object core table name
     *
     * @var string
     */
    protected $_sCoreTbl = 'fcpouserflags';

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'fcpouserflag';

    /**
     * Helper object for dealing with different shop versions
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * Centralized Database instance
     * @var object
     */
    protected $_oFcpoDb = null;

    /**
     * The timestamp that should be used to determine penalty times
     * @var string
     */
    protected $_sTimeStamp = null;

    /**
     * List of blocked paymentids
     * @var array
     */
    protected $_aBlockedPaymentIds = array();

    /**
     * ID of n:m table assigned to this flag
     * @var null
     */
    protected $_sAssignId = null;

    /**
     * Init needed data
     */
    public function __construct() {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
        $this->_oFcpoDb = oxDb::getDb();
        $this->init($this->_sCoreTbl);
    }

    /**
     * Overloaded method to automatically set effects
     *
     * @return mixed
     */
    public function load($sOXID) {
        $mReturn = parent::load($sOXID);
        if ($mReturn !== false) {
            $this->_fcpoSetEffects();
        }

        return $mReturn;
    }

    /**
     * Loads userflag by error code
     *
     * @param $sErrorCode
     * @return mixed
     */
    public function fcpoLoadByErrorCode($sErrorCode) {
        $sOxid = $this->_fcpoGetIdByErrorCode($sErrorCode);
        $mReturn = $this->load($sOxid);

        return $mReturn;
    }

    /**
     * Returns if userflag is in active use
     *
     * @param void
     * @return bool
     */
    public function fcpoGetIsActive() {
        $blReturn = $this->_fcpoFlagIsActive();

        return $blReturn;
    }

    /**
     * Returns translated message, optional a customer message as fallback
     *
     * @param $sCustomMessage
     * @return sting
     */
    public function fcpoGetTranslatedMessage($sCustomMessage='') {
        $sFlagDesc = $this->fcpouserflags__fcpodesc->value;
        $blSaveMessageInDb = (
            $sCustomMessage != '' &&
            $sFlagDesc == 'CUSTOM'
        );
        $blFetchMessageFromDb = (
            (
                $sCustomMessage == '' &&
                $sFlagDesc == 'CUSTOM'
            ) || $blSaveMessageInDb
        );

        if ($blSaveMessageInDb) {
            $this->fcpoSetDisplayMessage($sCustomMessage);
        }

        if ($blFetchMessageFromDb) {
            $sTranslatedMessage = $this->_fcpoGetMessageFromDb();
            $sTranslatedMessage = html_entity_decode($sTranslatedMessage);
        } else {
            // user flag has a defined translation string
            $oLang = $this->getLanguage();
            $sTranslatedMessage = (string) $oLang->translateString($sFlagDesc);
        }

        return $sTranslatedMessage;
    }

    /**
     * Returns saved message
     *
     * @param void
     * @return string
     */
    protected function _fcpoGetMessageFromDb() {
        if ($this->_sAssignId) {
            $oDb = $this->_oFcpoHelper->fcpoGetDb();
            $sQuery = "
          SELECT FCPODISPLAYMESSAGE 
          FROM fcpouser2flag 
          WHERE OXID=".$oDb->quote($this->_sAssignId);
        }

        $sMessage = (string) $oDb->getOne($sQuery);
        return $sMessage;
    }

    /**
     * Tryes to fetch userflag by error code
     *
     * @param $sErrorCode
     * @return string
     */
    protected function _fcpoGetIdByErrorCode($sErrorCode) {
        $oDb = $this->_oFcpoHelper->fcpoGetDb();
        $sQuery = "SELECT OXID FROM "
            .$this->_sCoreTbl
            ." WHERE FCPOCODE=".$oDb->quote($sErrorCode);

        $sOxid = (string) $oDb->GetOne($sQuery);

        return $sOxid;
    }

    /**
     * Setter for timestamp of when the user received the flag
     *
     * @param string $sTimeStamp
     * @return void
     */
    public function fcpoSetTimeStamp($sTimeStamp) {
        $this->_sTimeStamp = $sTimeStamp;
    }

    /**
     * Sets assign id for current flag
     *
     * @param $sOxid
     * @return void
     */
    public function fcpoSetAssignId($sOxid) {
        $this->_sAssignId = $sOxid;
    }

    /**
     * Sets custom display message to assigned id
     *
     * @param $sMessage
     * @return void
     */
    public function fcpoSetDisplayMessage($sMessage) {
        if ($this->_sAssignId) {
            // mandatory for persisting the message
            $oDb = $this->_oFcpoHelper->fcpoGetDb();
            $sQuery = "
                UPDATE fcpouser2flag 
                SET FCPODISPLAYMESSAGE=".$oDb->quote($sMessage)."
                WHERE OXID=".$oDb->quote($this->_sAssignId)."
                LIMIT 1
            ";
            $oDb->Execute($sQuery);
        }
    }

    /**
     * Returns an array of paymentids which are currently
     *
     * @param void
     * @return array
     */
    public function fcpoGetBlockedPaymentIds() {
        $aReturn = array();
        $blFlagActive = $this->_fcpoFlagIsActive();

        if ($blFlagActive) {
            $aReturn = $this->_aBlockedPaymentIds;
        }

        return $aReturn;
    }

    /**
     * Checks if this userflag is active related to timestamp of flag assigment
     * and its set duration. Setting a duration of 0 means infinite active state
     *
     * @param void
     * @return bool
     */
    protected function _fcpoFlagIsActive() {
        $iDurationHours =  $this->fcpouserflags__fcpoflagduration->value;
        $iTimeStampActiveUntil = $this->_fcpoGetTimeStampActiveUntil();
        $iTimeStampNow = time();
        $blReturn = ($iTimeStampActiveUntil >= $iTimeStampNow || $iDurationHours === 0);

        return $blReturn;
    }

    /**
     * Returns the time until flag is active
     *
     * @param void
     * @return int
     */
    protected function _fcpoGetTimeStampActiveUntil() {
        $iDurationHours =  $this->fcpouserflags__fcpoflagduration->value;
        $iTimeStampFlagAssigned = strtotime($this->_sTimeStamp);
        $sTimeStringDuration = '+ '.$iDurationHours.' hours';
        $iTimeStampActiveUntil = strtotime($sTimeStringDuration ,$iTimeStampFlagAssigned);
        $iTimeStampActiveUntil = (int) $iTimeStampActiveUntil;

        return $iTimeStampActiveUntil;
    }

    /**
     * Sets effects by effect-code
     *
     * @param void
     * @return void
     */
    protected function _fcpoSetEffects() {
        $this->_fcpoSetPaymentsBlocked();
    }

    /**
     * Set blocked payments
     *
     * @param void
     * @return void
     */
    protected function _fcpoSetPaymentsBlocked() {
        $sEffectCode = $this->fcpouserflags__fcpoeffect->value;

        switch($sEffectCode) {
            case 'RPR':
                // case ratpay payments are blocked
                $this->_fcpoAddBlockedPayment('fcporp_bill');
                break;
        }
    }

    /**
     * Adds a payment id to blocked payments
     *
     * @param $sPaymentId
     * @return void
     */
    protected function _fcpoAddBlockedPayment($sPaymentId) {
        $this->_aBlockedPaymentIds[] = $sPaymentId;
    }
}