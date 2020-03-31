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
include_once dirname(__FILE__) . "/statusbase.php";

class fcPayOneTransactionStatusForwarder extends fcPayOneTransactionStatusBase {
    /**
     * Get requests to forward to and trigger forwarding
     *
     * @param void
     * @return void
     * @throws
     */
    protected function _forwardRequests()
    {
        try {
            $sLimitStatusmessageId =
                $this->fcGetPostParam('statusmessageid');

            if ($sLimitStatusmessageId) {
                $sQueryLimitStatusmessageId =
                    " AND  FCSTATUSMESSAGEID='{$sLimitStatusmessageId}' ";
            }

            $sQuery = "
                SELECT
                    OXID,
                    FCSTATUSMESSAGEID,
                    FCSTATUSFORWARDID
                FROM fcpostatusforwardqueue
                WHERE FCFULFILLED='0'
                {$sQueryLimitStatusmessageId}
            ";

            $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);
            $aRows = $oDb->getAll($sQuery);

            foreach ($aRows as $aRow) {
                $sQueueId = $aRow['FCSTATUSMESSAGEID'];
                $sStatusmessageId = $aRow['FCSTATUSMESSAGEID'];
                $sForwardId = $aRow['FCSTATUSFORWARDID'];

                $this->_forwardRequest($sQueueId, $sForwardId, $sStatusmessageId);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Forward request from queue
     *
     * @param $sQueueId
     * @param $sForwardId
     * @param $sStatusmessageId
     * @return void
     * @throws
     */
    protected function _forwardRequest($sQueueId, $sForwardId, $sStatusmessageId) {
        try {
            $aParams = $this->_fetchPostParams($sStatusmessageId);
            $sParams = $aParams['string'];
            $aRequest = $aParams['array'];
            $aForwardData = $this->_getForwardData($sForwardId);
            $iTimeout = $aForwardData['timeout'];
            $sUrl = $aForwardData['url'];
            $this->_logForwardMessage('Trying to forward to url: '.$sUrl.'...');
            $this->_logForwardMessage($sParams);
            $sParams = substr($sParams,1);

            $oCurl = curl_init($sUrl);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sParams);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_TIMEOUT, $iTimeout);

            $mResult = curl_exec($oCurl);
            $mCurlInfo = curl_getinfo($oCurl);
            $blValidResult = (is_string($mResult) && trim($mResult) == 'TSOK');
            $this->_setForwardingResult($sQueueId, $blValidResult, $aRequest, $mResult, $mCurlInfo);
        } catch (Exception $e) {
            throw $e;
        }

        curl_close($oCurl);
    }

    /**
     * Updates processed queue entry with current data
     *
     * @param $sQueueId
     * @param $blValidResult
     * @param $aRequest
     * @param $mResult
     * @param $mCurlInfo
     * @throws Exception
     */
    protected function _setForwardingResult($sQueueId, $blValidResult, $aRequest, $mResult, $mCurlInfo)
    {
        try {
            $oDb = oxDb::getDb();
            $sFulfilled = ($blValidResult) ? '1' : '0';
            $sFulfilled = $oDb->quote($sFulfilled);
            $sRequest =$oDb->quote(print_r($aRequest, true));
            $sResponse = $oDb->quote((string) $mResult);
            $sResponseInfo = $oDb->quote((string) $mCurlInfo);

            $sQuery = "
            UPDATE fcpostatusforwardqueue
            SET 
                FCTRIES=FCTRIES+1,
                FCLASTTRY=NOW(),
                FCLASTREQUEST=".$sRequest.",
                FCLASTRESPONSE=".$sResponse.",
                FCRESPONSEINFO=".$sResponseInfo.",
                FCFULFILLED=".$sFulfilled."
            WHERE
                OXID=".$oDb->quote($sQueueId);

            $oDb->execute($sQuery);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Collects request data from database and prepare result
     *
     * @param $sStatusmessageId
     * @return array
     * @throws
     */
    protected function _fetchPostParams($sStatusmessageId)
    {
        try {
            $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);
            $sQuery = "
                SELECT * 
                FROM fcpotransactionstatus 
                WHERE OXID=".$oDb->quote($sStatusmessageId);

            $aRow = $oDb->getRow($sQuery);
            if ($aRow === false) {
                $sExceptionMessage =
                    'Could not find transaction status message for ID '.$sStatusmessageId.'!';
                throw new Exception($sExceptionMessage);
            }

            $aRequestParams = $this->_cleanParams($aRow);
            $sParams = '';
            foreach($aRequestParams as $sKey => $mValue) {
                $sParams .= $this->_addParam($sKey, $mValue);
            }

            return array(
                'string' => $sParams,
                'array' => $aRequestParams,
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Returns elementary forward data
     *
     * @param $sForwardId
     * @return
     * @throws
     */
    protected function _getForwardData($sForwardId)
    {
        $oDb = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);
        $sQuery = "
                SELECT 
                    FCPO_URL,
                    FCPO_TIMEOUT
                FROM fcpostatusforwarding 
                WHERE OXID=".$oDb->quote($sForwardId);

        $aRow = $oDb->getRow($sQuery);
        if ($aRow === false) {
            throw new Exception('Could not find forward data for ID '.$sForwardId.'!');
        }

        return array(
            'url' => $aRow['FCPO_URL'],
            'timeout' => $aRow['FCPO_TIMEOUT'],
        );
    }

    /**
     * Central handling of forward request
     *
     * @param void
     * @return void
     */
    public function handleForwarding() {
        try {
            $this->_isKeyValid();
            $this->_forwardRequests();

            echo 'TSOK';
        } catch (Exception $e) {
            echo "Error occured! Please check logfile for details.";
            $this->_logException($e->getMessage());
            return;
        }
    }
}

$oScript = oxNew('fcPayOneTransactionStatusForwarder');
$oScript->handleForwarding();
