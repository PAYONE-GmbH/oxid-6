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

class fcpopaymenthelper extends fcpobasehelper
{
    /**
     * @var self
     */
    protected static $oInstance;

    /**
     * Create instance of payment helper singleton
     *
     * @return static
     */
    public static function getInstance()
    {
        if (self::$oInstance === null) {
            self::$oInstance = oxNew(self::class);
        }
        return self::$oInstance;
    }

    /**
     * Resets singleton class
     * Needed for unit testing
     *
     * @return void
     */
    public static function destroyInstance()
    {
        self::$oInstance = null;
    }

    /**
     * @param  string $sPaymentId
     * @return object|false
     */
    public function loadPaymentMethod($sPaymentId)
    {
        $oPayment = oxNew('oxpayment');
        if ($oPayment->load($sPaymentId) === true) {
            return $oPayment;
        }
        return false;
    }

    /**
     * Returns if given payment method is active
     *
     * @param  string $sPaymentId
     * @return bool
     */
    public function isPaymentMethodActive($sPaymentId)
    {
        $blActive = false;

        $oPayment = $this->loadPaymentMethod($sPaymentId);
        if ($oPayment !== false && (bool)$oPayment->oxpayments__oxactive->value === true) {
            $blActive = true;
        }
        return $blActive;
    }

    /**
     * @param  string $sPaymentId
     * @return bool
     */
    public function isLiveMode($sPaymentId)
    {
        $blLiveMode = false;

        $oPayment = $this->loadPaymentMethod($sPaymentId);
        if ($oPayment !== false && (bool)$oPayment->oxpayments__fcpolivemode->value === true) {
            $blLiveMode = true;
        }
        return $blLiveMode;
    }

    /**
     * @return string
     */
    public function getUnzerSepaAgreement ()
    {
        return 'https://payment.payolution.com/payolution-payment/infoport/sepa/mandate.pdf';
    }
}