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
 
class fcpayone_boni_main extends fcpayone_admindetails
{

    /**
     * Current class template name
     *
     * @var string
     */
    protected $_sThisTemplate = 'fcpayone_boni_main.tpl';

    /**
     * Definitions of multilang files
     *
     * @var array
     */
    protected $_aMultiLangFields = array(
        'sFCPOApprovalText',
        'sFCPODenialText',
    );

    protected $_aDefaultValues = array(
        'sFCPOMalusPPB' => '0',
        'sFCPOMalusPHB' => '150',
        'sFCPOMalusPAB' => '300',
        'sFCPOMalusPKI' => '250',
        'sFCPOMalusPNZ' => '400',
        'sFCPOMalusPPV' => '500',
        'sFCPOMalusPPF' => '400',
    );

    protected $_aValidateCode2Message = array(
        '1' => 'FCPO_BONI_ERROR_SET_TO_BONIVERSUM_PERSON',
    );

    /**
     * Loads payment protection configuration and passes them to Smarty engine, returns
     * name of template file "fcpayone_boni_main.tpl".
     *
     * @return string
     */
    public function render() 
    {
        $sReturn = parent::render();
        $oLang = $this->_oFcpoHelper->fcpoGetLang();

        $iLang = $this->_oFcpoHelper->fcpoGetRequestParameter("subjlang");
        if (empty($iLang)) {
            $iLang = '0';
        }

        $this->_aViewData["subjlang"] = $iLang;

        $oConfig = $this->getConfig();
        $sShopId = $oConfig->getShopId();

        $aConfigs = $this->_oFcpoConfigExport->fcpoGetConfig($sShopId, $iLang);

        $this->_aViewData["confbools"] = $aConfigs['bools'];
        $this->_aViewData["confstrs"] = $aConfigs['strs'];
        $this->_aViewData['sHelpURL'] = $this->_oFcpoHelper->fcpoGetHelpUrl();

        $aConfStrs = $this->_aViewData["confstrs"];
        foreach ($this->_aDefaultValues as $sVarName => $sValue) {
            if(array_key_exists($sVarName, $aConfStrs) === false || empty($aConfStrs[$sVarName])) {
                $aConfStrs[$sVarName] = $sValue;
            }
        }
        $this->_aViewData["confstrs"] = $aConfStrs;

        return $sReturn;
    }

    /**
     * Saves changed configuration parameters.
     *
     * @return mixed
     */
    public function save() 
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();

        $iLang = $this->_oFcpoHelper->fcpoGetRequestParameter("subjlang");
        if (empty($iLang)) {
            $iLang = '0';
        }

        $aConfBools = $this->_oFcpoHelper->fcpoGetRequestParameter("confbools");
        $aConfStrs = $this->_oFcpoHelper->fcpoGetRequestParameter("confstrs");
        if (is_array($aConfBools)) {
            foreach ($aConfBools as $sVarName => $sVarVal) {
                $oConfig->saveShopConfVar("bool", $sVarName, $sVarVal);
            }
        }

        if (is_array($aConfStrs)) {
            foreach ($aConfStrs as $sVarName => $sVarVal) {
                if (array_search($sVarName, $this->_aMultiLangFields) !== false) {
                    $sVarName = $sVarName . '_' . $iLang;
                }
                $oConfig->saveShopConfVar("str", $sVarName, $sVarVal);
            }
        }

        $iValidateCode = $this->_fcpoValidateAddresscheckType();
        $this->_fcpoDisplayMessage($iValidateCode);
    }

    /**
     * Validating addresstype. Fix setting if needed and respond with error code
     *
     * @param void
     * @return int
     */
    protected function _fcpoValidateAddresscheckType() {
        $iValidateCode = $this->_fcpoValidateAddresscheckBoniversum();

        return $iValidateCode;
    }

    /**
     * Validates addresscheck related to boniversion. Correct settings and return error
     * code for notifying user
     *
     * @param void
     * @return int
     */
    protected function _fcpoValidateAddresscheckBoniversum() {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $aConfStrs = $this->_oFcpoHelper->fcpoGetRequestParameter("confstrs");
        $iValidateCode = 0;

        if (isset($aConfStrs['sFCPOBonicheck']) && isset($aConfStrs['sFCPOAddresscheck'])) {
            $sBoniCheckType = $aConfStrs['sFCPOBonicheck'];
            $sAddresscheckType = $aConfStrs['sFCPOAddresscheck'];

            if ($sBoniCheckType == 'CE' && $sAddresscheckType != 'PB') {
                // addresschecktype ALWAYS has to be PB if bonichecktype is CE => Set error code ...
                $iValidateCode = 1;
                // ... and fix setting
                $oConfig->saveShopConfVar("str", 'sFCPOAddresscheck', 'PB');
            }
        }

        return $iValidateCode;
    }



    /**
     * Displays a message in admin frontend if there is an error code present
     *
     * @param $iValidateCode
     * @return void
     */
    protected function _fcpoDisplayMessage($iValidateCode) {
        if ($iValidateCode > 0 && isset($this->_aValidateCode2Message[$iValidateCode])) {
            $oUtilsView = oxRegistry::get('oxUtilsView');
            $sTranslateString = $this->_aValidateCode2Message[$iValidateCode];
            $oLang = $this->_oFcpoHelper->fcpoGetLang();
            $sTranslatedMessage = $oLang->translateString($sTranslateString);
            $oUtilsView->addErrorToDisplay($sTranslatedMessage);
        }
    }

}
