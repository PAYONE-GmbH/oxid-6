<?php

/**
 * Created by PhpStorm.
 * User: andre
 * Date: 13.07.17
 * Time: 17:50
 */
class fcPayOneUserView extends fcPayOneUserView_parent
{

    /**
     * Helper object for dealing with different shop versions
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * Helper object for dealing with different shop versions
     * @var object
     */
    protected $_oFcpoDb = null;


    /**
     * init object construction
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
        $this->_oFcpoDb = oxDb::getDb();
    }

    /**
     * Method will be called when returning from amazonlogin
     *
     * @param void
     * @return void
     */
    public function fcpoAmazonLoginReturn()
    {
        $oSession = $this->getSession();
        $oUtilsServer = oxRegistry::get('oxUtilsServer');
        $sPaymentId = 'fcpoamazonpay';

        // OXID-233 : if the user is logged in, we save the id in session for later
        // AmazonPay process uses a new user, created on the fly
        // Then we need the original Id to link back the order to the initial user
        $user = oxRegistry::getSession()->getUser();
        if ($user) {
            oxRegistry::getSession()->setVariable('sOxidPreAmzUser', $user->getId());
        }

        // delete possible old data
        $this->_oFcpoHelper->fcpoDeleteSessionVariable('sAmazonLoginAccessToken');

        $sAmazonLoginAccessTokenParam = $this->_oFcpoHelper->fcpoGetRequestParameter('access_token');
        $sAmazonLoginAccessTokenParam = urldecode($sAmazonLoginAccessTokenParam);
        $sAmazonLoginAccessTokenCookie = $oUtilsServer->getOxCookie('amazon_Login_accessToken');
        $blNeededDataAvailable = (bool)($sAmazonLoginAccessTokenParam || $sAmazonLoginAccessTokenCookie);

        if ($blNeededDataAvailable) {
            $sAmazonLoginAccessToken =
                ($sAmazonLoginAccessTokenParam) ? $sAmazonLoginAccessTokenParam : $sAmazonLoginAccessTokenCookie;
            $this->_oFcpoHelper->fcpoSetSessionVariable('sAmazonLoginAccessToken', $sAmazonLoginAccessToken);
            $this->_oFcpoHelper->fcpoSetSessionVariable('paymentid', $sPaymentId);
            $this->_oFcpoHelper->fcpoSetSessionVariable('_selected_paymentid', $sPaymentId);
            $oBasket = $oSession->getBasket();
            $oBasket->setPayment($sPaymentId);
        } else {
            $this->_fcpoHandleAmazonNoTokenFound();
        }

        // go ahead with rendering
        $this->render();
    }

    /**
     * Handles the case that there is no access token available/accessable
     *
     * @param void
     * @return void
     */
    protected function _fcpoHandleAmazonNoTokenFound()
    {
        $oConfig = $this->getConfig();
        $aAllowedDoubleRedirectModes = array('redirect', 'auto');
        $sFCPOAmazonLoginMode = $oConfig->getConfigParam('sFCPOAmazonLoginMode');
        $blAllowedForDoubleRedirect = (in_array($sFCPOAmazonLoginMode, $aAllowedDoubleRedirectModes));

        if ($blAllowedForDoubleRedirect) {
            // we need to fetch the token from location hash (via js) and put it into a cookie first
            $this->_aViewData['blFCPOAmazonCatchHash'] = true;
            $this->render();
        } else {
            // @todo: Redirect to basket with message, currently redirect without comment
            $oUtils = $this->_oFcpoHelper->fcpoGetUtils();
            $sShopUrl = $oConfig->getShopUrl();
            $oUtils->redirect($sShopUrl . "index.php?cl=basket");
        }
    }

    /**
     * Returns user error message if there is some. false if none
     *
     * @param void
     * @return mixed string|bool
     */
    public function fcpoGetUserErrorMessage()
    {
        $mReturn = false;
        $sMessage = $this->_oFcpoHelper->fcpoGetRequestParameter('fcpoerror');
        if ($sMessage) {
            $sMessage = urldecode($sMessage);
            $mReturn = $sMessage;
        }

        return $mReturn;
    }

}