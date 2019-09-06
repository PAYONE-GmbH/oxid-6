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
 
class fcPayOneBasket extends fcPayOneBasket_parent
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
     * Returns wether paypal express is active or not
     * 
     * @return bool
     */
    public function fcpoIsPayPalExpressActive() 
    {
        $oDb = $this->_oFcpoHelper->fcpoGetDb();
        $sQuery = "SELECT oxactive FROM oxpayments WHERE oxid = 'fcpopaypal_express'";
        return (bool)$oDb->GetOne($sQuery);
    }
    
    
    /**
     * Returns pic that is configured in database
     * 
     * @param  void
     * @return string
     */
    public function fcpoGetPayPalExpressPic() 
    {
        $oDb = $this->_oFcpoHelper->fcpoGetDb();
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $iLangId = $oLang->getBaseLanguage();
        $sQuery = "SELECT fcpo_logo FROM fcpopayoneexpresslogos WHERE fcpo_logo != '' AND fcpo_langid = '{$iLangId}' ORDER BY fcpo_default DESC";
        $sPic   = $oDb->GetOne($sQuery);
        
        return $sPic;
    }

    /**
     * Returns matching paydirekt express picture by config
     *
     * @param void
     * @return string
     */
    public function fcpoGetPaydirektExpressPic()
    {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sButtonType = $oConfig->getConfigParam('sPaydirektExpressButtonType');
        $aAssignMap = array(
            'green' => 'paydirekt-express-gruen.png',
            'green2' => 'paydirekt-express-gruen2.png',
            'white' => 'paydirekt-express-weiss.png',
            'white2' => 'paydirekt-express-weiss2.png',
        );
        $blAvailable = in_array($sButtonType, array_keys($aAssignMap));
        $sPic = ($blAvailable) ? $aAssignMap[$sButtonType] : $aAssignMap['green'];

        return $sPic;
    }

    /**
     * Iterates through basket items and calculates its delivery costs
     *
     * @return oxPrice
     */
    public function fcpoCalcDeliveryCost()
    {
        $myConfig = $this->getConfig();
        $oDeliveryPrice = oxNew('oxprice');
        if ($this->getConfig()->getConfigParam('blDeliveryVatOnTop')) {
            $oDeliveryPrice->setNettoPriceMode();
        } else {
            $oDeliveryPrice->setBruttoPriceMode();
        }
        $oUser = oxNew('oxUser');
        $oUser->oxuser__oxcountryid = new oxField('a7c40f631fc920687.20179984');
        $fDelVATPercent = $this->getAdditionalServicesVatPercent();
        $oDeliveryPrice->setVat($fDelVATPercent);
        $aDeliveryList = oxRegistry::get("oxDeliveryList")->getDeliveryList(
            $this,
            $oUser,
            $oUser->oxuser__oxcountryid->value,
            $this->getShippingId()
        );
        if (count($aDeliveryList) > 0) {
            foreach ($aDeliveryList as $oDelivery) {
                //debug trace
                if ($myConfig->getConfigParam('iDebug') == 5) {
                    echo("DelCost : " . $oDelivery->oxdelivery__oxtitle->value . "<br>");
                }
                $oDeliveryPrice->addPrice($oDelivery->getDeliveryPrice($fDelVATPercent));
            }
        }

        return $oDeliveryPrice;
    }

}
