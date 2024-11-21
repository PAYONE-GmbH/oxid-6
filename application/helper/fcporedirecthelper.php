<?php

class fcporedirecthelper extends fcpobasehelper
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
     * @return string
     */
    protected function getShopUrl()
    {
        return $this->getMainHelper()->fcpoGetConfig()->getCurrentShopUrl();
    }

    /**
     * @return string
     */
    protected function getRToken()
    {
        $sRToken = '';
        if ($this->getMainHelper()->fcpoGetIntShopVersion() >= 4310) {
            $sRToken = '&rtoken='.$this->getMainHelper()->fcpoGetSession()->getRemoteAccessToken();
        }
        return $sRToken;
    }

    /**
     * Returns cancel URL
     *
     * @param  string $sAbortClass
     * @return string
     */
    public function getCancelUrl($sAbortClass)
    {
        return $this->getShopUrl().'index.php?type=cancel&cl='.$sAbortClass.$this->getRToken();
    }

    /**
     * Returns error URL
     *
     * @param  string $sAbortClass
     * @param  bool   $blAddAmazonLogoff
     * @return string
     */
    public function getErrorUrl($sAbortClass, $blAddAmazonLogoff = false)
    {
        $sPaymentErrorParam = '&payerror=-20'; // see source/modules/fc/fcpayone/out/blocks/fcpo_payment_errors.tpl
        $sPaymentErrorTextParam = "&payerrortext=".urlencode($this->getMainHelper()->fcpoGetLang()->translateString('FCPO_PAY_ERROR_REDIRECT', null, false));

        $sAddParam = "";
        if ($blAddAmazonLogoff === true) {
            $sAddParam = "&fcpoamzaction=logoff";
        }

        return $this->getShopUrl().'index.php?type=error&cl='.$sAbortClass.$this->getRToken().$sPaymentErrorParam.$sPaymentErrorTextParam.$sAddParam;
    }

    /**
     * Returns success URL
     *
     * @param string|false $sRefNr
     * @param string|false $sRedirectFunction
     * @param string|false $sToken
     * @param string|false $sDeliveryMD5
     * @return string
     */
    public function getSuccessUrl($sRefNr = false, $sRedirectFunction = false, $sToken = false, $sDeliveryMD5 = false)
    {
        $sSid = $this->getMainHelper()->fcpoGetSession()->sid(true);
        if (!empty($sSid)) {
            $sSid = '&' . $sSid;
        }

        $sAddParams = '';
        if (!empty($sRefNr)) {
            $sAddParams .= '&refnr=' . $sRefNr;
        }

        if (!empty($sRedirectFunction)) {
            $sAddParams .= '&fnc='.$sRedirectFunction;
        } else {
            $sAddParams .= '&fnc=execute';
        }


        if (!empty($sDeliveryMD5)) {
            $sAddParams .= '&sDeliveryAddressMD5=' . $sDeliveryMD5;
        } elseif ($this->getMainHelper()->fcpoGetRequestParameter('sDeliveryAddressMD5')) {
            $sAddParams .= '&sDeliveryAddressMD5=' . $this->getMainHelper()->fcpoGetRequestParameter('sDeliveryAddressMD5');
        }

        $blDownloadableProductsAgreement = $this->getMainHelper()->fcpoGetRequestParameter('oxdownloadableproductsagreement');
        if ($blDownloadableProductsAgreement) {
            $sAddParams .= '&fcdpa=1'; // rewrite for oxdownloadableproductsagreement-param because of length-restriction
        }

        $blServiceProductsAgreement = $this->getMainHelper()->fcpoGetRequestParameter('oxserviceproductsagreement');
        if ($blServiceProductsAgreement) {
            $sAddParams .= '&fcspa=1'; // rewrite for oxserviceproductsagreement-param because of length-restriction
        }

        if (empty($sToken)) {
            $sToken = $this->getMainHelper()->fcpoGetRequestParameter('stoken');
        }

        return $this->getShopUrl().'index.php?cl=order&fcposuccess=1&ord_agb=1&stoken='.$sToken.$sSid.$sAddParams.$this->getRToken();
    }
}