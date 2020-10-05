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
 

class fcpayone_log extends fcpayone_admindetails
{

    /**
     * Current class template name
     *
     * @var string
     */
    protected $_sThisTemplate = 'fcpayone_log.tpl';

    /**
     * Array with existing status of order
     *
     * @var array
     */
    protected $_aStatus = null;
    
    
    /**
     * Holds a current response status 
     *
     * @var array
     */
    protected $_aResponse = null;

    
    /**
     * Loads selected transactions status, passes
     * it's data to Smarty engine and returns name of template file
     * "fcpayone_log.tpl".
     *
     * @return string
     */
    public function render() 
    {
        parent::render();

        $oLogEntry = oxNew("fcpotransactionstatus");

        $sOxid = $this->_oFcpoHelper->fcpoGetRequestParameter("oxid");
        if ($sOxid != "-1" && isset($sOxid)) {
            // load object
            $oLogEntry->load($sOxid);
            $this->_aViewData["edit"] = $oLogEntry;
        }

        $this->_aViewData['sHelpURL'] = $this->_oFcpoHelper->fcpoGetHelpUrl();

        return $this->_sThisTemplate;
    }
    

    /**
     * Get all transaction status for the given order
     *
     * @param object $oOrder order object
     *
     * @return array
     */
    public function getStatus($oOrder) 
    {
        if(!$this->_aStatus) {
            $oDb = $this->_oFcpoHelper->fcpoGetDb();
            $aRows = $oDb->getAll("SELECT oxid FROM fcpotransactionstatus WHERE fcpo_txid = '{$oOrder->oxorder__fcpotxid->value}' ORDER BY oxid ASC");
            $aStatus = array();
            foreach ($aRows as $aRow) {
                $oTransactionStatus = oxNew('fcpotransactionstatus');
                $oTransactionStatus->load($aRow[0]);
                $aStatus[] = $oTransactionStatus;
            }
            $this->_aStatus = $aStatus;
        }
        return $this->_aStatus;
    }
    

    /**
     * Triggers capture request to PAYONE API and displays the result
     *
     * @return null
     */
    public function capture() 
    {
        $sOxid = $this->_oFcpoHelper->fcpoGetRequestParameter("oxid");
        if ($sOxid != "-1" && isset($sOxid)) {
            $oOrder = oxNew("oxorder");
            $oOrder->load($sOxid);

            $dAmount = $this->_oFcpoHelper->fcpoGetRequestParameter('capture_amount');
            if($dAmount && $dAmount > 0) {
                $oPORequest = $this->_oFcpoHelper->getFactoryObject('fcporequest');
                $oResponse = $oPORequest->sendRequestCapture($oOrder, $dAmount);
                $this->_aResponse = $oResponse;
            }
        }
    }
    
    
    /**
     * Returns capture message if there is a relevant one
     * 
     * @param  void
     * @return string
     */
    public function getCaptureMessage() 
    {
        $sReturn = "";
        
        if ($this->_aResponse ) {
            $oLang = $this->_oFcpoHelper->fcpoGetLang();
            if($this->_aResponse['status'] == 'APPROVED' ) {
                $sReturn = '<span style="color: green;">'.$oLang->translateString('FCPO_CAPTURE_APPROVED', null, true).'</span>';
            } 
            else if($this->_aResponse['status'] == 'ERROR' ) {
                $sReturn = '<span style="color: red;">'.$oLang->translateString('FCPO_CAPTURE_ERROR', null, true).$this->_aResponse['errormessage'].'</span>';
            }
        }
        
        return $sReturn;
    }

    /**
     * Triggering forward redirects of current status message
     *
     * @param void
     * @return void
     */
    public function fcpoTriggerForwardRedirects()
    {
        $sStatusmessageId = $this->_oFcpoHelper->fcpoGetRequestParameter("oxid");
        if (!$sStatusmessageId || $sStatusmessageId == -1) {
            return;
        }
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sPortalKey = $oConfig->getConfigParam('sFCPOPortalKey');
        $sKey = md5($sPortalKey);

        $oConfig = $this->getConfig();
        $sShopUrl = $oConfig->getShopUrl();
        $sSslShopUrl = $oConfig->getSslShopUrl();

        $sParams = '';
        $sParams .= $this->_addParam('key', $sKey);
        $sParams .= $this->_addParam('statusmessageid', $sStatusmessageId);
        $sParams = substr($sParams,1);
        $sBaseUrl = (empty($sSslShopUrl)) ? $sShopUrl : $sSslShopUrl;
        $sConfTimeout = $oConfig->getConfigParam('sTransactionRedirectTimeout');

        $sForwarderUrl = $sBaseUrl . 'modules/fc/fcpayone/statusforward.php';

        $oCurl = curl_init($sForwarderUrl);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sParams);

        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);

        curl_exec($oCurl);
        curl_close($oCurl);
        $this->render();
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