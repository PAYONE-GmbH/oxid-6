<?php

class fcpopaypalhelper extends fcpobasehelper
{
    const PPE_EXPRESS = 'fcpopaypal_express';
    const PPE_V2_EXPRESS = 'fcpopaypalv2_express';

    /**
     * @var self
     */
    protected static $oInstance;


    /**
     * Locale codes supported by misc images (marks, shortcuts etc)
     *
     * @var array
     */
    protected $aSupportedLocales = [
        'de_DE',
        'en_AU',
        'en_GB',
        'en_US',
        'es_ES',
        'es_XC',
        'fr_FR',
        'fr_XC',
        'it_IT',
        'ja_JP',
        'nl_NL',
        'pl_PL',
        'zh_CN',
        'zh_XC',
    ];

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
     * @return bool
     */
    protected function showBNPLButton()
    {
        $blReturn = false;
        if ((bool)$this->getMainHelper()->fcpoGetConfig()->getConfigParam('blFCPOPayPalV2BNPL') === true) {
            $blReturn = true;
        }
        return $blReturn;
    }

    /**
     * @return string
     */
    protected function getIntent()
    {
        return "authorize"; // authorize = preauthorize // capture = authorize but Payone said to always use authorize
    }

    /**
     * @return string
     */
    protected function getCurrency()
    {
        return $this->getMainHelper()->fcpoGetSession()->getBasket()->getBasketCurrency()->name;
    }

    /**
     * @return string
     */
    protected function getMerchantId()
    {
        $sMerchantId = "3QK84QGGJE5HW"; // Default for testmode (fixed)
        if (fcpopaymenthelper::getInstance()->isLiveMode(self::PPE_V2_EXPRESS)) {
            $sMerchantId = $this->getMainHelper()->fcpoGetConfig()->getConfigParam('blFCPOPayPalV2MerchantID');
        }
        return $sMerchantId;
    }

    /**
     * @return string
     */
    protected function getClientId()
    {
        $sClientId = "AUn5n-4qxBUkdzQBv6f8yd8F4AWdEvV6nLzbAifDILhKGCjOS62qQLiKbUbpIKH_O2Z3OL8CvX7ucZfh"; // Default for testmode (fixed)
        if (fcpopaymenthelper::getInstance()->isLiveMode(self::PPE_V2_EXPRESS)) {
            $sClientId = "AVNBj3ypjSFZ8jE7shhaY2mVydsWsSrjmHk0qJxmgJoWgHESqyoG35jLOhH3GzgEPHmw7dMFnspH6vim"; // Livemode (fixed)
        }
        return $sClientId;
    }

    /**
     * Check whether specified locale code is supported. Fallback to en_US
     *
     * @param  string $sLocale
     * @return string
     */
    protected function getSupportedLocaleCode($sLocale = null)
    {
        if (!$sLocale || !in_array($sLocale, $this->aSupportedLocales)) {
            return 'en_US';
        }
        return $sLocale;
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        $sCurrentLocal = "de_DE"; ///@TODO
        $sPayPalLocal = $this->getSupportedLocaleCode($sCurrentLocal);
        return $sPayPalLocal;
    }

    /**
     * @return string
     */
    public function getJavascriptUrl()
    {
        $sUrl = "https://www.paypal.com/sdk/js?client-id=".$this->getClientId()."&merchant-id=".$this->getMerchantId()."&currency=".$this->getCurrency()."&intent=".$this->getIntent()."&locale=".$this->getLocale()."&commit=true&vault=false&disable-funding=card,sepa,bancontact";
        if ($this->showBNPLButton() === true) {
            $sUrl .= "&enable-funding=paylater";
        }
        return $sUrl;
    }

    /**
     * @return string
     */
    public function getButtonColor()
    {
        return $this->getMainHelper()->fcpoGetConfig()->getConfigParam('blFCPOPayPalV2ButtonColor');
    }

    /**
     * @return string
     */
    public function getButtonShape()
    {
        return $this->getMainHelper()->fcpoGetConfig()->getConfigParam('blFCPOPayPalV2ButtonShape');
    }
}