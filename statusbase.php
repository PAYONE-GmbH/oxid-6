<?php

class fcPayOneTransactionStatusBase extends oxBase {

    protected $_aShopList = null;

    protected $_sLogFile = 'log/fcpo_message_forwarding.log';

    protected $_sExceptionLog = 'log/fcpo_statusmessage_exception.log';

    protected $_oFcOrder = null;

    protected $_oUtilsObject = null;

    /**
     * Returns instance of oxUtilsObject
     *
     * @param void
     * @return |null
     * @throws Exception
     */
    protected function _getUtilsObject()
    {
        if ($this->_oUtilsObject === null) {
            try {
                $this->_oUtilsObject = oxNew('oxUtilsObject');
            } catch (Exception $e) {
                throw $e;
            }
        }

        return $this->_oUtilsObject;
    }

    /**
     * Check if logging is activated by configuration
     *
     * @param void
     * @return bool
     */
    protected function _fcCheckLoggingAllowed()
    {
        $oConfig = $this->getConfig();
        $sLogMethod =
            $oConfig->getConfigParam('sTransactionRedirectLogging');

        $blLoggingAllowed = $sLogMethod == 'all';

        return $blLoggingAllowed;
    }

    /**
     * Check and return post parameter
     *
     * @param  string $sKey
     * @return string
     */
    public function fcGetPostParam( $sKey )
    {
        $sReturn    = '';
        $mValue     = filter_input(INPUT_GET, $sKey, FILTER_SANITIZE_SPECIAL_CHARS);
        if (!$mValue) {
            $mValue = filter_input(INPUT_POST, $sKey, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        if ($mValue ) {
            if($this->getConfig()->isUtf() ) {
                $mValue = utf8_encode($mValue);
            }
            $sReturn = $mValue;
        }

        return $sReturn;
    }

    protected function _getShopList()
    {
        if($this->_aShopList === null) {
            $aShops = array();

            $sQuery = "SELECT oxid FROM oxshops";
            $aRows = oxDb::getDb()->getAll($sQuery);

            foreach ($aRows as $aRow) {
                $aShops[] = $aRow[0];
            }

            $this->_aShopList = $aShops;
        }
        return $this->_aShopList;
    }

    protected function _getConfigParams($sParam)
    {
        $aShops = $this->_getShopList();
        $aParams = array();
        foreach ($aShops as $sShop) {
            $mValue = $this->getConfig()->getShopConfVar($sParam, $sShop);
            if($mValue) {
                $aParams[$sShop] = $mValue;
            }
        }

        return $aParams;
    }

    /**
     * Check if key is available and valid. Throw exception if not
     *
     * @param void
     * @return void
     * @throws Exception
     */
    protected function _isKeyValid()
    {
        $sKey = $this->fcGetPostParam('key');
        if (!$sKey) {
            throw new Exception('Key missing!');
        }

        $aKeys = array_merge(
            array_values($this->_getConfigParams('sFCPOPortalKey')),
            array_values($this->_getConfigParams('sFCPOSecinvoicePortalKey'))
        );

        $blValid = false;
        foreach ($aKeys as $i => $sConfigKey) {
            if(md5($sConfigKey) != $sKey) {
                continue;
            }
            $blValid = true;
            break;
        }

        if(!$blValid) {
            throw new Exception('Invalid key!');
        }
    }

    /**
     * Logs exception for later analysis
     *
     * @param $sMessage
     * @return void
     */
    protected function _logException($sMessage)
    {
        $sBasePath = dirname(__FILE__) . "/../../../";
        $sLogFilePath = $sBasePath.$this->_sExceptionLog;
        $sPrefix = "[".date('Y-m-d H:i:s')."] ";
        $sFullMessage = $sPrefix.$sMessage."\n";

        $oLogFile = fopen($sLogFilePath, 'a');
        fwrite($oLogFile, $sFullMessage);
        fclose($oLogFile);
    }


    /**
     * Logs given message if logging is activated
     *
     * @param $sMessage
     * @return void
     */
    protected function _logForwardMessage($sMessage)
    {
        $blLoggingAllowed = $this->_fcCheckLoggingAllowed();
        if (!$blLoggingAllowed) return;

        $sBasePath = dirname(__FILE__) . "/../../../";
        $sLogFilePath = $sBasePath.$this->_sLogFile;
        $sPrefix = "[".date('Y-m-d H:i:s')."] ";
        $sFullMessage = $sPrefix.$sMessage."\n";

        $oLogFile = fopen($sLogFilePath, 'a');
        fwrite($oLogFile, $sFullMessage);
        fclose($oLogFile);
    }

    protected function _addParam($sKey, $mValue)
    {
        $sParams = '';
        if(is_array($mValue)) {
            foreach ($mValue as $sKey2 => $mValue2) {
                $sParams .= $this->_addParam($sKey.'['.$sKey2.']', $mValue2);
            }
        } else {
            $sParams .= "&".$sKey."=".urlencode($mValue);
        }
        return $sParams;
    }
}