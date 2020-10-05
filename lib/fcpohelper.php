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
class fcpohelper extends oxBase
{

    /**
     * Flags if shop uses registry
     *
     * @var static boolean
     */
    protected static $_blUseRegistry = null;

    /**
     * Building essential stuff
     */
    public function __construct() 
    {
        parent::__construct();
    }

    /**
     * Returns a factory instance of given object
     * 
     * @param  string $sName
     * @return object 
     */
    public function getFactoryObject($sName) 
    {
        return oxNew($sName);
    }

    /**
     * Wrapper for ini get calls
     * 
     * @param  string $sConfigVar
     * @return mixed
     */
    public function fcpoIniGet($sConfigVar) 
    {
        return ini_get($sConfigVar);
    }

    /**
     * Wrapper for returning if function with given name exists
     * 
     * @param  string $sFunctionName
     * @return bool
     */
    public function fcpoFunctionExists($sFunctionName) 
    {
        return function_exists($sFunctionName);
    }

    /**
     * Wrapper for returning if file in given path exists
     * 
     * @param  string $sFilePath
     * @return bool
     */
    public function fcpoFileExists($sFilePath) 
    {
        return file_exists($sFilePath);
    }

    /**
     * Creates an instance of a class
     * 
     * @param  string $sClassName
     * @param  string $sIncludePath optional
     * @throws oxexception
     * @return object
     */
    public function fcpoGetInstance($sClassName, $sIncludePath = "") 
    {
        try {
            if ($sIncludePath) {
                include_once $sIncludePath;
            }
            $oObjInstance = new $sClassName();
        } catch (oxException $oEx) {
            throw $oEx;
        }

        return $oObjInstance;
    }

    /**
     * Wrapper method for getting a session variable
     * 
     * @param  string $sVariable
     * @return mixed
     */
    public function fcpoGetSessionVariable($sVariable) 
    {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = $this->getSession()->getVar($sVariable);
        } else {
            $mReturn = $this->getSession()->getVariable($sVariable);
        }

        return $mReturn;
    }

    /**
     * Wrapper method for setting a session variable
     * 
     * @param  string $sVariable
     * @param  string $sValue
     * @return mixed
     */
    public function fcpoSetSessionVariable($sVariable, $sValue) 
    {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = $this->getSession()->setVar($sVariable, $sValue);
        } else {
            $mReturn = $this->getSession()->setVariable($sVariable, $sValue);
        }

        return $mReturn;
    }

    /**
     * Wrapper method for setting a session variable
     * 
     * @param  string $sVariable
     * @param  string $sValue
     * @return mixed
     */
    public function fcpoDeleteSessionVariable($sVariable) 
    {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = $this->getSession()->deleteVar($sVariable);
        } else {
            $mReturn = $this->getSession()->deleteVariable($sVariable);
        }

        return $mReturn;
    }

    /**
     * static Getter for config instance
     * 
     * @param void
     * @param mixed
     * @return oxconfig
     */
    public static function fcpoGetStaticConfig() 
    {
        if (self::_useRegistry() === true) {
            $oReturn = oxRegistry::getConfig();
        } else {
            $oReturn = oxConfig::getInstance();
        }

        return $oReturn;
    }

    /**
     * Getter for config instance
     * 
     * @param void
     * @return \OxidEsales\Eshop\Core\Config
     */
    public function fcpoGetConfig() 
    {
        return $this->getConfig();
    }

    /**
     * Getter for session instance
     * 
     * @return \OxidEsales\Eshop\Core\Session
     */
    public function fcpoGetSession() 
    {
        return $this->getSession();
    }

    /**
     * Getter for database instance
     * 
     * @param $blAssoc with assoc mode
     * @param mixed
     */
    public function fcpoGetDb($blAssoc = false) 
    {
        if ($blAssoc) {
            return ($this->_fcUseDeprecatedInstantiation()) ? oxDb::getDb(true) : oxDb::getDb(oxDB::FETCH_MODE_ASSOC);
        } else {
            return ($this->_fcUseDeprecatedInstantiation()) ? oxDb::getDb() : oxDb::getDb(oxDb::FETCH_MODE_NUM);
        }
    }

    /**
     * Wrapper method for getting a request parameter
     * 
     * @param  string $sParameter
     * @return mixed
     */
    public function fcpoGetRequestParameter($sParameter) 
    {
        $oConfig = $this->getConfig();

        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = $oConfig->getParameter($sParameter);
        } else {
            $mReturn = $oConfig->getRequestParameter($sParameter);
        }

        return $mReturn;
    }

    /**
     * Returns a language Instance
     * 
     * @param  void
     * @return mixed
     */
    public function fcpoGetLang() 
    {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = oxLang::getInstance();
        } else {
            $mReturn = oxRegistry::get('oxLang');
        }

        return $mReturn;
    }

    /**
     * Returns a utilsfile instance
     * 
     * @param  void
     * @return mixed
     */
    public function fcpoGetUtilsFile() 
    {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = oxUtilsFile::getInstance();
        } else {
            $mReturn = oxRegistry::get('oxUtilsFile');
        }

        return $mReturn;
    }

    /**
     * Returns a utilsobject instance
     * 
     * @param  void
     * @return mixed
     */
    public function fcpoGetUtilsObject() 
    {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = oxUtilsObject::getInstance();
        } else {
            $mReturn = oxRegistry::get('oxUtilsObject');
        }

        return $mReturn;
    }

    /**
     * Returns an instance of oxutils
     * 
     * @param  void
     * @return mixed
     */
    public function fcpoGetUtils() 
    {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = oxUtils::getInstance();
        } else {
            $mReturn = oxRegistry::getUtils();
        }

        return $mReturn;
    }

    /**
     * Returns an instance of oxutilsview
     * 
     * @param  void
     * @return mixed
     */
    public function fcpoGetUtilsView() 
    {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = oxUtilsView::getInstance();
        } else {
            $mReturn = oxRegistry::get('oxUtilsView');
        }

        return $mReturn;
    }

    /**
     * Returns an instance of oxviewvonfig
     *
     * @param void
     * @return mixed
     */
    public function fcpoGetViewConfig() {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = oxViewConfig::getInstance();
        } else {
            $mReturn = oxRegistry::get('oxViewConfig');
        }

        return $mReturn;
    }

    /**
     * Returns an instance of oxutilserver
     * 
     * @param  void
     * @return mixed
     */
    public function fcpoGetUtilsServer() 
    {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = oxUtilsServer::getInstance();
        } else {
            $mReturn = oxRegistry::get('oxUtilsServer');
        }

        return $mReturn;
    }

    /**
     * Returns an instance of oxUtilsDate
     * 
     * @param  void
     * @return mixed
     */
    public function fcpoGetUtilsDate() 
    {
        if ($this->_fcUseDeprecatedInstantiation()) {
            $mReturn = oxUtilsDate::getInstance();
        } else {
            $mReturn = oxRegistry::get('oxUtilsDate');
        }

        return $mReturn;
    }

    /**
     * Method returns current module version
     * 
     * @param  void
     * @return string
     */
    public static function fcpoGetStaticModuleVersion()
    {
       return '1.0.1';
    }

    /**
     * Method returns current module version
     *
     * @param  void
     * @return string
     */
    public function fcpoGetModuleVersion()
    {
        include_once __DIR__."/../metadata.php";
        return $aModule['version'];
    }

    /**
     * Returns the superglobal $_FILES
     * 
     * @param  void
     * @return void
     */
    public function fcpoGetFiles() 
    {
        return $_FILES;
    }

    /**
     * Processing and returning result string
     * 
     * @param  string $sContent
     * @return string
     */
    public function fcpoProcessResultString($sContent) 
    {
        return $sContent;
    }

    /**
     * Output content as header
     * 
     * @param  string $sContent
     * @return string
     */
    public function fcpoHeader($sContent) 
    {
        header($sContent);
    }

    /**
     * Wrapper for php exit on beeing able to be mocked
     * 
     * @param  void
     * @return void
     */
    public function fcpoExit() 
    {
        exit;
    }

    /**
     * Retunrs if incoming class name exists or not
     * 
     * @param  string $sClassName
     * @return bool
     */
    public function fcpoCheckClassExists($sClassName) 
    {
        return class_exists($sClassName);
    }

    /**
     * Returns current integrator version
     * 
     * @param  void
     * @return string
     */
    public function fcpoGetIntegratorVersion() 
    {
        $oConfig = $this->getConfig();
        $sEdition = $oConfig->getActiveShop()->oxshops__oxedition->value;
        $sVersion = $oConfig->getActiveView()->getShopVersion();
        $sIntegratorVersion = $sEdition . $sVersion;

        return $sIntegratorVersion;
    }

    /**
     * Returns shopversion as integer
     * 
     * @param  void
     * @return int
     */
    public function fcpoGetIntShopVersion() 
    {
        $oConfig = $this->getConfig();
        $sVersion = $oConfig->getActiveShop()->oxshops__oxversion->value;
        $iVersion = (int) str_replace('.', '', $sVersion);
        // fix for ce/pe 4.10.0+
        if ($iVersion > 1000) {
            $iVersion *= 10;
        } else {
            while ($iVersion < 1000) {
                $iVersion = $iVersion * 10;
            }
        }
        return $iVersion;
    }

    /**
     * Returns the current shop name
     * 
     * @param  void
     * @return string
     */
    public function fcpoGetShopName() 
    {
        $oConfig = $this->getConfig();

        return $oConfig->getActiveShop()->oxshops__oxname->value;
    }

    /**
     * Returns help url
     * 
     * @param  void
     * @return string
     */
    public function fcpoGetHelpUrl() 
    {
        return "http://www.payone.de";
    }

    /**
     * 
     * 
     * @param void
     * @return array
     */
    public function fcpoGetPayoneStatusList() 
    {
        return array(
            'appointed',
            'capture',
            'paid',
            'underpaid',
            'cancelation',
            'refund',
            'debit',
            'reminder',
            'vauthorization',
            'vsettlement',
            'transfer',
            'invoice',
       );
    }

    /**
     * Returns a static instance of given object name
     *
     * @param $sObjectName
     * @return mixed
     */
    public function getStaticInstance($sObjectName) {
        return oxRegistry::get($sObjectName);
    }
 
    /**
     * Loads shop version and formats it in a certain way
     *
     * @param  void
     * @return string
     */
    public function fcpoGetIntegratorId() 
    {
        $oConfig = $this->getConfig();

        $sEdition = $oConfig->getActiveShop()->oxshops__oxedition->value;
        if ($sEdition == 'CE') {
            return '2027000';
        } elseif ($sEdition == 'PE') {
            return '2028000';
        } elseif ($sEdition == 'EE') {
            return '2029000';
        }
        return '';
    }

    /**
     * Item price in smallest available unit
     *
     * @param  oxBasketItem/double $mValue
     * @return int
     */
    public function fcpoGetCentPrice($mValue)
    {
        $oConfig = $this->getConfig();
        $dBruttoPrice = 0.00;
        if ($mValue instanceof oxBasketItem) {
            $oPrice = $mValue->getPrice();
            $dBruttoPricePosSum = $oPrice->getBruttoPrice();
            $dAmount = $mValue->getAmount();
            $dBruttoPrice = round($dBruttoPricePosSum/$dAmount, 2);
        } else if (is_float($mValue)) {
            $dBruttoPrice = $mValue;
        }

        $oCur = $oConfig->getActShopCurrencyObject();
        $dFactor = (double) pow(10, $oCur->decimal);

        return ($dBruttoPrice * $dFactor);
    }

    /**
     * Returns if deprecated instation should be used
     * 
     * @param  void
     * @return bool
     */
    protected function _fcUseDeprecatedInstantiation() 
    {
        $oConfig = $this->getConfig();
        if ((version_compare($oConfig->getVersion(), "4.8.0") < 1 && $oConfig->getEdition() == "CE") 
            || (version_compare($oConfig->getVersion(), "4.8.0") < 1 && $oConfig->getEdition() == "PE") 
            || (version_compare($oConfig->getVersion(), "5.1.0") < 1 && $oConfig->getEdition() == "EE")
       ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Static getter for checking newer available methods and classes in shop
     * 
     * @param  void
     * @return bool
     */
    protected static function _useRegistry() 
    {
        if (self::$_blUseRegistry === null) {
            self::$_blUseRegistry = false;
            if (class_exists('oxRegistry')) {
                $oConf = oxRegistry::getConfig();
                if (method_exists($oConf, 'getRequestParameter')) {
                    self::$_blUseRegistry = true;
                }
            }
        }
        return self::$_blUseRegistry;
    }

}
