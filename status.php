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
 
set_time_limit(0);
ini_set('memory_limit', '1024M');
ini_set('log_errors', 1);
ini_set('error_log', '../../../log/fcpoErrors.log');

if(file_exists(dirname(__FILE__)."/config.ipwhitelist.php")) {
    include_once dirname(__FILE__)."/config.ipwhitelist.php";
} else {
    echo 'Config file missing!';
    exit;
}

$sClientIp = null;
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $aIps = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $sTmpClientIp = trim($aIps[0]);
    $blAllowed = in_array($sTmpClientIp, $aWhitelistForwarded);
    if ($blAllowed) {
        $sClientIp = $sTmpClientIp;
    }
}

$sRemoteIp = isset($sClientIp) ? $sClientIp : $_SERVER['REMOTE_ADDR'];
if(array_search($sRemoteIp, $aWhitelist) === false) {
    $blMatch = false;
    foreach ($aWhitelist as $sIP) {
        if(stripos($sIP, '*') !== false) {
            $sDelimiter = '/';
            
            $sRegex = preg_quote($sIP, $sDelimiter);
            $sRegex = str_replace('\*', '\d{1,3}', $sRegex);
            $sRegex = $sDelimiter.'^'.$sRegex.'$'.$sDelimiter;

            preg_match($sRegex, $sRemoteIp, $aMatches);
            if(is_array($aMatches) && count($aMatches) == 1 && $aMatches[0] == $sRemoteIp) {
                $blMatch = true;
            }
        }
    }
    
    if($blMatch === false) {
        echo 'Access denied';
        exit;
    }
}

include_once dirname(__FILE__) . "/../../../bootstrap.php";
include_once dirname(__FILE__) . "/statusbase.php";

class fcPayOneTransactionStatusHandler extends fcPayOneTransactionStatusBase
{
    protected function _getOrderNr()
    {
        $oDb = oxDb::getDb();
        $sTxid = $this->fcGetPostParam('txid');

        $sQuery = "
            SELECT 
                oxordernr 
            FROM 
                oxorder 
            WHERE 
                fcpotxid = ". $oDb->quote($sTxid) ."
            LIMIT 1
        ";

        $iOrderNr = (int) $oDb->GetOne($sQuery);

        return $iOrderNr;
    }

    /**
     * Log incoming entry and return its ID
     *
     * @return mixed
     * @throws Exception
     */
    public function log() 
    {
        $oUtilsObject = $this->_getUtilsObject();
        $sOxid = $oUtilsObject->generateUId();
        $iOrderNr = $this->_getOrderNr();

        $sQuery = "
            INSERT INTO fcpotransactionstatus (
                OXID,
                FCPO_ORDERNR,
                FCPO_KEY,
                FCPO_TXACTION,
                FCPO_PORTALID,
                FCPO_AID,
                FCPO_CLEARINGTYPE,
                FCPO_TXTIME,
                FCPO_CURRENCY,
                FCPO_USERID,    
                FCPO_ACCESSNAME,
                FCPO_ACCESSCODE,
                FCPO_PARAM,
                FCPO_MODE,
                FCPO_PRICE,
                FCPO_TXID,
                FCPO_REFERENCE,
                FCPO_SEQUENCENUMBER,
                FCPO_COMPANY,
                FCPO_FIRSTNAME,
                FCPO_LASTNAME,
                FCPO_STREET,
                FCPO_ZIP,
                FCPO_CITY,
                FCPO_EMAIL,
                FCPO_COUNTRY,
                FCPO_SHIPPING_COMPANY,
                FCPO_SHIPPING_FIRSTNAME,
                FCPO_SHIPPING_LASTNAME,
                FCPO_SHIPPING_STREET,
                FCPO_SHIPPING_ZIP,
                FCPO_SHIPPING_CITY,
                FCPO_SHIPPING_COUNTRY,
                FCPO_BANKCOUNTRY,
                FCPO_BANKACCOUNT,
                FCPO_BANKCODE,
                FCPO_BANKACCOUNTHOLDER,
                FCPO_CARDEXPIREDATE,
                FCPO_CARDTYPE,  
                FCPO_CARDPAN,
                FCPO_CUSTOMERID,
                FCPO_BALANCE,
                FCPO_RECEIVABLE,
                FCPO_CLEARING_BANKACCOUNTHOLDER,
                FCPO_CLEARING_BANKACCOUNT,
                FCPO_CLEARING_BANKCODE,
                FCPO_CLEARING_BANKNAME,
                FCPO_CLEARING_BANKBIC,
                FCPO_CLEARING_BANKIBAN,         
                FCPO_CLEARING_LEGALNOTE,
                FCPO_CLEARING_DUEDATE,
                FCPO_CLEARING_REFERENCE,
                FCPO_CLEARING_INSTRUCTIONNOTE
            ) VALUES (
                '{$sOxid}',
                '{$iOrderNr}',
                '".$this->fcGetPostParam('key')."',
                '".$this->fcGetPostParam('txaction')."',
                '".$this->fcGetPostParam('portalid')."',
                '".$this->fcGetPostParam('aid')."',
                '".$this->fcGetPostParam('clearingtype')."',
                FROM_UNIXTIME('".$this->fcGetPostParam('txtime')."'),
                '".$this->fcGetPostParam('currency')."',
                '".$this->fcGetPostParam('userid')."',
                '".$this->fcGetPostParam('accessname')."',
                '".$this->fcGetPostParam('accesscode')."',
                '".$this->fcGetPostParam('param')."', 
                '".$this->fcGetPostParam('mode')."',  
                '".$this->fcGetPostParam('price')."', 
                '".$this->fcGetPostParam('txid')."',  
                '".$this->fcGetPostParam('reference')."', 
                '".$this->fcGetPostParam('sequencenumber')."',
                '".$this->fcGetPostParam('company')."',   
                '".$this->fcGetPostParam('firstname')."', 
                '".$this->fcGetPostParam('lastname')."',  
                '".$this->fcGetPostParam('street')."',    
                '".$this->fcGetPostParam('zip')."',   
                '".$this->fcGetPostParam('city')."',  
                '".$this->fcGetPostParam('email')."', 
                '".$this->fcGetPostParam('country')."',   
                '".$this->fcGetPostParam('shipping_company')."',  
                '".$this->fcGetPostParam('shipping_firstname')."',    
                '".$this->fcGetPostParam('shipping_lastname')."', 
                '".$this->fcGetPostParam('shipping_street')."',   
                '".$this->fcGetPostParam('shipping_zip')."',  
                '".$this->fcGetPostParam('shipping_city')."', 
                '".$this->fcGetPostParam('shipping_country')."',  
                '".$this->fcGetPostParam('bankcountry')."',   
                '".$this->fcGetPostParam('bankaccount')."',   
                '".$this->fcGetPostParam('bankcode')."',  
                '".$this->fcGetPostParam('bankaccountholder')."', 
                '".$this->fcGetPostParam('cardexpiredate')."',    
                '".$this->fcGetPostParam('cardtype')."',  
                '".$this->fcGetPostParam('cardpan')."',   
                '".$this->fcGetPostParam('customerid')."',    
                '".$this->fcGetPostParam('balance')."',   
                '".$this->fcGetPostParam('receivable')."',
                '".$this->fcGetPostParam('clearing_bankaccountholder')."',
                '".$this->fcGetPostParam('clearing_bankaccount')."',  
                '".$this->fcGetPostParam('clearing_bankcode')."', 
                '".$this->fcGetPostParam('clearing_bankname')."', 
                '".$this->fcGetPostParam('clearing_bankbic')."',  
                '".$this->fcGetPostParam('clearing_bankiban')."', 
                '".$this->fcGetPostParam('clearing_legalnote')."',
                '".$this->fcGetPostParam('clearing_duedate')."',  
                '".$this->fcGetPostParam('clearing_reference')."',
                '".$this->fcGetPostParam('clearing_instructionnote')."'
            )";

        try {
            oxDb::getDb()->Execute($sQuery);
        } catch (Exception $e) {
            throw $e;
        }

        return $sOxid;
    }
    
    /**
     * Handling configured forwarding of statusmessage to other endpoints
     *
     * @param $sStatusmessageId
     * @return void
     * @throws
     */
    protected function _handleForwarding($sStatusmessageId) {
        $this->_logForwardMessage('Handle forwarding for statusmessage id: '.$sStatusmessageId);
        try {
            $this->_addQueueEntries($sStatusmessageId);
        } catch (Exception $e) {
            throw $e;
        }

        $oConfig = $this->getConfig();
        $sTransactionForwardMethod =
            $oConfig->getConfigParam('sTransactionRedirectMethod');

        if ($sTransactionForwardMethod != 'cronjob') {
            $this->_directRedirect($sStatusmessageId);
        }
    }

    /**
     * Method directly redirects to statusforwardcontroller
     *
     * @param void
     * @return void
     */
    protected function _directRedirect($sStatusmessageId)
    {
        $sKey = $this->fcGetPostParam('key');
        $sParams = '';
        $sParams .= $this->_addParam('key', $sKey);
        $sParams .= $this->_addParam('statusmessageid', $sStatusmessageId);

        $oConfig = $this->getConfig();
        $sShopUrl = $oConfig->getShopUrl();
        $sSslShopUrl = $oConfig->getSslShopUrl();
        $sConfTimeout = $oConfig->getConfigParam('sTransactionRedirectTimeout');
        $iTimeout = ($sConfTimeout) ? (int) $sConfTimeout : 5500;
        $sParams = substr($sParams,1);
        $sBaseUrl = (empty($sSslShopUrl)) ? $sShopUrl : $sSslShopUrl;

        $sForwarderUrl = $sBaseUrl . 'modules/fc/fcpayone/statusforward.php';
        $this->_logForwardMessage('Forward transaction id to own controller:'.$sForwarderUrl.'...');

        $oCurl = curl_init($sForwarderUrl);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sParams);

        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($oCurl, CURLOPT_TIMEOUT_MS, $iTimeout);

        curl_exec($oCurl);
        $aResult = curl_getinfo($oCurl);
        $this->_logForwardMessage('Triggered forward! Result: '.print_r($aResult, true));

        curl_close($oCurl);

    }

    /**
     * Handles shop order status folder by configured mapping
     * (e. g. PENDING to NEW)
     *
     * @param void
     * @return void
     * @throws
     */
    protected function _handleMapping()
    {
        $sTxid = $this->fcGetPostParam('txid');
        $sPayoneStatus = $this->fcGetPostParam('txaction');
        $blCheckMapping = ($sTxid && $sPayoneStatus);
        if (!$blCheckMapping) {
            return;
        }

        $oDb = oxDb::getDb();
        $oOrder = $this->_getOrder($sTxid);
        $sPaymentId = $oDb->quote($oOrder->oxorder__oxpaymenttype->value);
        
        $sQuery = "
            SELECT fcpo_folder 
            FROM fcpostatusmapping 
            WHERE 
                  fcpo_payonestatus = '{$sPayoneStatus}' AND 
                  fcpo_paymentid = {$sPaymentId} 
            ORDER BY oxid ASC 
            LIMIT 1
        ";
        $sFolder = $oDb->GetOne($sQuery);
        if(empty($sFolder)) {
           return;
        }

        try {
            $sQuery = "
                UPDATE oxorder 
                SET oxfolder = '{$sFolder}' 
                WHERE oxid = '{$oOrder->getId()}'
            ";
            $oDb->Execute($sQuery);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Central point for handling an incoming status message call
     *
     * @param void
     * @return void
     */
    public function handle() 
    {
        try {
            $this->_isKeyValid();
            $sStatusmessageId = $this->log();
            $this->_allowDebit();
            $this->_handleAppointed();
            $this->_handlePaid();
            $this->_handleMapping();
            $this->_handleForwarding($sStatusmessageId);

            echo 'TSOK';
        } catch (Exception $e) {
            echo "Error occured! Please check logfile for details.";
            $this->_logException($e->getMessage());
            return;
        }
    }

    /**
     * Returns order by posted or given txid
     *
     * @param null $sTxid
     * @return |null
     */
    protected function _getOrder($sTxid=null) {
        if ($this->_oFcOrder === null) {
            if ($sTxid === null) {
                $sTxid = $this->fcGetPostParam('txid');
            }

            $oDb = oxDb::getDb();
            $sQuery = "SELECT oxid FROM oxorder WHERE fcpotxid = '".$sTxid."'";
            $sOrderId = $oDb->GetOne($sQuery);

            $oOrder = oxNew('oxorder');
            $oOrder->load($sOrderId);

            $this->_oFcOrder = $oOrder;
        }

        return $this->_oFcOrder;

    }

    /**
     * OXID-337
     * Check if appointed signal has been posted and handles it
     *
     * @param void
     * @return void
     * @throws Exception
     */
    protected function _handleAppointed()
    {
        if ($this->fcGetPostParam('txaction') != 'appointed') {
            return;
        }

        try {
            $oOrder = $this->_getOrder();
            $sOrderId = $oOrder->getId();
            $oLang = oxNew('oxLang');

            $sReplacement = $oLang->translateString('FCPO_REMARK_APPOINTED_MISSING');

            $sQuery = "
                        UPDATE 
                            oxorder 
                        SET 
                            oxfolder = 'ORDERFOLDER_NEW', 
                            oxtransstatus = 'OK',
                            oxremark = REPLACE(oxremark, '".$sReplacement."', '')
                        WHERE 
                            oxid = '{$sOrderId}' AND 
                            oxtransstatus IN ('ERROR') AND 
                            oxfolder = 'ORDERFOLDER_PROBLEMS'
            ";

            oxDb::getDb()->Execute($sQuery);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Check if paid signal has been posted and handle it
     *
     * @param void
     * @return void
     * @throws
     */
    protected function _handlePaid()
    {
        if($this->fcGetPostParam('txaction') != 'paid') {
            return;
        }

        try {
            $oOrder = $this->_getOrder();
            $sOrderId = $oOrder->getId();
            $oLang = oxNew('oxLang');

            $sReplacement = $oLang->translateString('FCPO_REMARK_APPOINTED_MISSING');

            $sQuery = "
                        UPDATE 
                            oxorder 
                        SET 
                            oxfolder = 'ORDERFOLDER_NEW', 
                            oxtransstatus = 'OK',
                            oxremark = REPLACE(oxremark, '".$sReplacement."', '')
                        WHERE 
                            oxid = '{$sOrderId}' AND 
                            oxtransstatus IN ('INCOMPLETE', 'ERROR') AND 
                            oxfolder = 'ORDERFOLDER_PROBLEMS'
            ";

            oxDb::getDb()->Execute($sQuery);
        } catch (Exception $e) {
            throw $e;
        }


    }
    
    /**
     * Checks based on the transaction status received by PAYONE whether
     * the debit request is available for this order at the moment.
     * 
     * @param  void
     * @return void
     * @throws
     */
    protected function _allowDebit()
    {
        $sTxid = $this->fcGetPostParam('txid');

        $blReturn = false;
        $sAuthMode = oxDb::getDb()->GetOne("SELECT fcpoauthmode FROM oxorder WHERE fcpotxid = '".$sTxid."'");
        if ($sAuthMode == 'authorization') {
            $blReturn = true;
        } else {
            $iCount = oxDb::getDb()->GetOne("SELECT COUNT(*) FROM fcpotransactionstatus WHERE fcpo_txid = '{$sTxid}' AND fcpo_txaction = 'capture'");
            if ($iCount > 0) {
                $blReturn = true;
            }
        }

        if (!$blReturn) {
            return;
        }

        $oOrder = $this->_getOrder();
        $sOrderId = $oOrder->getId();
        $sQuery = "UPDATE oxorder SET oxpaid = NOW() WHERE oxid = '{$sOrderId}'";
        try {
            oxDb::getDb()->Execute($sQuery);

        } catch (Exception $e){
            throw $e;
        }
    }
    

}

$oScript = oxNew('fcPayOneTransactionStatusHandler');
$oScript->handle();
