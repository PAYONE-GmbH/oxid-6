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
     * Map for translating database fields to call params
     * @var array
     */
    protected $_aDbFields2Params = array(
        'FCPO_KEY'=>'key',
        'FCPO_TXACTION'=>'txaction',
        'FCPO_PORTALID'=>'portalid',
        'FCPO_AID'=>'aid',
        'FCPO_CLEARINGTYPE'=>'clearingtype',
        'FCPO_TXTIME'=>'txtime',
        'FCPO_CURRENCY'=>'currency',
        'FCPO_USERID'=>'userid',
        'FCPO_ACCESSNAME'=>'accessname',
        'FCPO_ACCESSCODE'=>'accesscode',
        'FCPO_PARAM'=>'param',
        'FCPO_MODE'=>'mode',
        'FCPO_PRICE'=>'price',
        'FCPO_TXID'=>'txid',
        'FCPO_REFERENCE'=>'reference',
        'FCPO_SEQUENCENUMBER'=>'reference',
        'FCPO_COMPANY'=>'company',
        'FCPO_FIRSTNAME'=>'firstname',
        'FCPO_LASTNAME'=>'lastname',
        'FCPO_STREET'=>'street',
        'FCPO_ZIP'=>'zip',
        'FCPO_CITY'=>'city',
        'FCPO_EMAIL'=>'email',
        'FCPO_COUNTRY'=>'country',
        'FCPO_SHIPPING_COMPANY'=>'shipping_company',
        'FCPO_SHIPPING_FIRSTNAME'=>'shipping_firstname',
        'FCPO_SHIPPING_LASTNAME'=>'shipping_lastname',
        'FCPO_SHIPPING_STREET'=>'shipping_street',
        'FCPO_SHIPPING_ZIP'=>'shipping_zip',
        'FCPO_SHIPPING_CITY'=>'shipping_city',
        'FCPO_SHIPPING_COUNTRY'=>'shipping_country',
        'FCPO_BANKCOUNTRY'=>'bankcountry',
        'FCPO_BANKACCOUNT'=>'bankaccount',
        'FCPO_BANKCODE'=>'bankcode',
        'FCPO_BANKACCOUNTHOLDER'=>'bankaccountholder',
        'FCPO_CARDEXPIREDATE'=>'cardexpiredate',
        'FCPO_CARDTYPE'=>'cardtype',
        'FCPO_CARDPAN'=>'cardpan',
        'FCPO_CUSTOMERID'=>'customerid',
        'FCPO_BALANCE'=>'balance',
        'FCPO_RECEIVABLE'=>'receivable',
        'FCPO_CLEARING_BANKACCOUNTHOLDER'=>'clearing_bankaccountholder',
        'FCPO_CLEARING_BANKACCOUNT'=>'clearing_bankaccount',
        'FCPO_CLEARING_BANKCODE'=>'clearing_bankcode',
        'FCPO_CLEARING_BANKNAME'=>'clearing_bankname',
        'FCPO_CLEARING_BANKBIC'=>'clearing_bankbic',
        'FCPO_CLEARING_BANKIBAN'=>'clearing_bankiban',
        'FCPO_CLEARING_LEGALNOTE'=>'clearing_legalnote',
        'FCPO_CLEARING_DUEDATE'=>'clearing_duedate',
        'FCPO_CLEARING_REFERENCE'=>'clearing_reference',
        'FCPO_CLEARING_INSTRUCTIONNOTE'=>'clearing_instructionnote',
    );

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
            $this->_logForwardMessage('Found requests to forward: '.print_r($aRows, true));

            foreach ($aRows as $aRow) {
                $sQueueId = $aRow['OXID'];
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
            $sUrl = $aForwardData['url'];
            $this->_logForwardMessage('Trying to forward to url: '.$sUrl.'...');
            $this->_logForwardMessage($sParams);
            $sParams = substr($sParams,1);

            $oCurl = curl_init($sUrl);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sParams);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);

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
            $sResponseInfo = $oDb->quote((string) print_r($mCurlInfo, true));

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
            $this->_logForwardMessage("Updating Request with query:\n".$sQuery."\n");

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
     * Removes all empty params and translate db fields to corresponding
     * call
     *
     * @param $aParams
     * @return array
     */
    protected function _cleanParams($aParams)
    {
        $aCleanedParams = array();
        foreach ($aParams as $sKey=>$sValue) {
            $blValid = (
                isset($this->_aDbFields2Params[$sKey]) &&
                $sValue != ''
            );
            if (!$blValid) {
                continue;
            }
            $sCallKey = $this->_aDbFields2Params[$sKey];
            $aCleanedParams[$sCallKey] = $sValue;
        }

        return $aCleanedParams;
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
        } catch (Exception $e) {
            echo "Error occured! Please check logfile for details.";
            $this->_logException($e->getMessage());
            return;
        }
    }
}

$oScript = oxNew('fcPayOneTransactionStatusForwarder');
$oScript->handleForwarding();
