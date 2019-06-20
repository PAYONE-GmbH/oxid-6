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

include_once dirname(__FILE__) . "/../../../bootstrap.php";

class fcPayOneTransactionStatusForwarder extends oxBase {

    protected $_aShopList = null;

    /**
     * Check and return post parameter
     *
     * @param  string $sKey
     * @return string
     */
    public function fcGetPostParam( $sKey )
    {
        $sReturn    = '';
        $mValue     = filter_input(INPUT_GET, $sKey);
        if (!$mValue) {
            $mValue = filter_input(INPUT_POST, $sKey);
        }
        if ($mValue ) {
            if($this->getConfig()->isUtf() ) {
                $mValue = utf8_encode($mValue);
            }
            $sReturn = $mValue;
        }

        return $sReturn;
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

    protected function _forwardRequest($sUrl, $iTimeout) {
        if ($iTimeout == 0) {
            $iTimeout = 45;
        }
        
        $sParams = '';
        foreach($_POST as $sKey => $mValue) {
            $sParams .= $this->_addParam($sKey, $mValue);
        }

        $sParams = substr($sParams,1);

        $oCurl = curl_init($sUrl);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sParams);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, $iTimeout);

        try {
            $oResult = curl_exec($oCurl);
        } catch (Exception $e) {
            // do nothing
        }

        curl_close($oCurl);
    }

    public function handleForwarding() {
        $sPayoneStatus = $this->fcGetPostParam('txaction');
        
        $sQuery = "
            SELECT 
                fcpo_url, 
                fcpo_timeout 
            FROM 
                fcpostatusforwarding 
            WHERE 
                fcpo_payonestatus = '{$sPayoneStatus}'";

        $aRows = oxDb::getDb()->getAll($sQuery);

        foreach ($aRows as $aRow) {
            $sUrl = (string) $aRow[0];
            $iTimeout = (int) $aRow[1];
            $this->_forwardRequest($sUrl, $iTimeout);
        }
    }
}

$oScript = oxNew('fcPayOneTransactionStatusForwarder');
$oScript->handleForwarding();
