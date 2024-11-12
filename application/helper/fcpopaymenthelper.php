<?php

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
}