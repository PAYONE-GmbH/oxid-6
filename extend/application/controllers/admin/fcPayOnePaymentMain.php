<?php


class fcPayOnePaymentMain extends fcPayOnePaymentMain_parent
{
    /**
     * Helper object for dealing with different shop versions
     *
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * List of boolean config values
     *
     * @var array
     */
    protected $_aConfBools = array();

    /**
     * fcpoconfigexport instance
     *
     * @var object
     */
    protected $_oFcpoConfigExport = null;


    /**
     * init object construction
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->_oFcpoConfigExport = oxNew('fcpoconfigexport');
        $this->_oFcpoHelper = oxNew('fcpohelper');
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sShopId = $oConfig->getShopId();
        $this->_fcpoLoadConfigs($sShopId);
    }

    /**
     * Template getter for boolean config values
     *
     * @param  void
     * @return array
     */
    public function fcpoGetConfBools()
    {
        return $this->_aConfBools;
    }

    /**
     * Save Method overwriting
     *
     * @param void
     * @return void
     */
    public function save()
    {
        parent::save();

        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $aConfBools = $this->_oFcpoHelper->fcpoGetRequestParameter("confbools");

        if (is_array($aConfBools)) {
            foreach ($aConfBools as $sVarName => $sVarVal) {
                $oConfig->saveShopConfVar("bool", $sVarName, (bool) $sVarVal);
            }
        }

        $sShopId = $oConfig->getShopId();
        $this->_fcpoLoadConfigs($sShopId);
    }

    /**
     * Loads configurations of payone and make them accessable
     *
     * @param  void
     * @return void
     */
    protected function _fcpoLoadConfigs($sShopId)
    {
        $aConfigs = $this->_oFcpoConfigExport->fcpoGetConfig($sShopId);
        $this->_aConfBools = $aConfigs['bools'];
    }
}