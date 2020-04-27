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
    public function fcGetPostParam($sKey)
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
        if(defined('STDIN')) {
            return;
        }

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

    /**
     * Adding param
     *
     * @param $sKey
     * @param $mValue
     * @return string
     */
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

    /**
     * Method collects redirect targets and add them to statusforward queue
     *
     * @param $sStatusmessageId
     * @param $sPayoneStatus
     * @return void
     * @throws
     */
    protected function _addQueueEntries($sStatusmessageId, $sPayoneStatus=null)
    {
        try {
            if ($sPayoneStatus === null) {
                $sPayoneStatus = $this->fcGetPostParam('txaction');
            }

            $sQuery = "
            SELECT 
                OXID
            FROM 
                fcpostatusforwarding 
            WHERE 
                fcpo_payonestatus = '{$sPayoneStatus}'";

            $aRows = oxDb::getDb()->getAll($sQuery);

            $this->_logForwardMessage('Add fowardings to queue: '.print_r($aRows, true));

            foreach ($aRows as $aRow) {
                $sForwardId = (string) $aRow[0];
                $this->_addToQueue($sStatusmessageId, $sForwardId);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Add certain combination of transaction and forward configuration
     * to queue
     *
     * @param $sStatusmessageId
     * @param $sForwardId
     * @throws Exception
     */
    protected function _addToQueue($sStatusmessageId, $sForwardId)
    {
        try {
            if ($this->_queueEntryExists($sStatusmessageId, $sForwardId)) {
                $this->_logForwardMessage(
                    'Entry already exitsts. Skipping. StatusmessageId: '.
                    $sStatusmessageId.
                    ', ForwardId: '.
                    $sForwardId
                );
                return;
            }
            $oUtilsObject = $this->_getUtilsObject();
            $sOxid = $oUtilsObject->generateUId();

            $sQuery = "
                INSERT INTO fcpostatusforwardqueue
                (
                    OXID,
                    FCSTATUSMESSAGEID,
                    FCSTATUSFORWARDID,
                    FCTRIES,
                    FCLASTTRY,
                    FCLASTREQUEST,
                    FCLASTRESPONSE
                )
                VALUES
                (
                    '{$sOxid}',
                    '{$sStatusmessageId}',
                    '{$sForwardId}',
                    '0',
                    '0000-00-00 00:00:00',
                    '',
                    ''
                )
            ";

            oxDb::getDb()->Execute($sQuery);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Checks if a certain combination of statusmessageid already
     * exists
     *
     * @param $sStatusmessageId
     * @param $sForwardId
     * @return bool
     */
    protected function _queueEntryExists($sStatusmessageId, $sForwardId)
    {
        $sQuery = "
                SELECT COUNT(*) 
                FROM fcpostatusforwardqueue
                WHERE
                    FCSTATUSMESSAGEID='{$sStatusmessageId}' AND
                    FCSTATUSFORWARDID='{$sForwardId}'
        ";

        $iRows = (int) oxDb::getDb()->getOne($sQuery);

        return (bool) ($iRows > 0);
    }
}