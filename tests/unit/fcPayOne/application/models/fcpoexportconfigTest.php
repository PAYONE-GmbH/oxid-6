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

class MockResultExportConfig
{

    public $EOF = false;
    public $fields = array('aFCPODebitCountries', 'arr', 's:9:"someValue"');

    public function recordCount() 
    {
        return 1;
    }

    public function moveNext() 
    {
        $this->EOF = true;
    }

}

/**
 * Description of fcpoexportconfigTest
 *
 * @author Andre Gregor-Herrmann <andre.herrmann@fatchip.de>
 * @author Fatchip GmbH
 * @date   2016-05-31
 */
class Unit_fcPayOne_Application_Models_fcpoexportconfig extends OxidTestCase
{

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array()) 
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Set protected/private attribute value
     *
     * @param object &$object      Instantiated object that we will run method on.
     * @param string $propertyName property that shall be set
     * @param array  $value        value to be set
     *
     * @return mixed Method return.
     */
    public function invokeSetAttribute(&$object, $propertyName, $value) 
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

    /**
     * Testing fcpoGetConfig for coverage
     */
    public function test_fcpoGetConfig_Coverage() 
    {
        $oTestObject = oxNew('fcpoconfigexport');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $aMockResult = array(
            array(
                'oxvarname'=>'someName',
                'oxvartype'=>'someType',
                'oxvarvalue'=>'someValue',
            ),
        );
        $oMockDatabase = $this->getMock('oxDb', array('getAll', 'quote'));
        $oMockDatabase->expects($this->atLeastOnce())->method('getAll')->will($this->returnValue($aMockResult));
        $oMockDatabase->expects($this->any())->method('quote')->will($this->returnValue(''));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $aResponse = $aExpect = $oTestObject->fcpoGetConfig('1');

        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoExportConfig for coverage
     */
    public function test_fcpoExportConfig_Coverage() 
    {
        $oTestObject = $this->getMock('fcpoconfigexport', array('fcpoGetConfigXml'));
        $oTestObject->expects($this->any())->method('fcpoGetConfigXml')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoHeader')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoExit')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoProcessResultString')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->fcpoExportConfig());
    }

    /**
     * Testing fcpoExportConfig with false xml
     */
    public function test_fcpoExportConfig_FalseXml() 
    {
        $oTestObject = $this->getMock('fcpoconfigexport', array('fcpoGetConfigXml'));
        $oTestObject->expects($this->any())->method('fcpoGetConfigXml')->will($this->returnValue(false));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoHeader')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoExit')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoProcessResultString')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->fcpoExportConfig());
    }

    /**
     * Tests _getChecksumErrors returning result is valid
     *
     * @param  void
     * @return void
     */
    public function test__getChecksumErrors_Valid() 
    {
        $oTestObject = $this->getMock('fcpoconfigexport', array('_fcpoGetCheckSumResult'));
        $oTestObject->method('_fcpoGetCheckSumResult')->will($this->returnValue('correct'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoCheckClassExists')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertFalse($this->invokeMethod($oTestObject, '_getChecksumErrors'));
    }

    /**
     * Tests _getChecksumErrors returning result is invalid
     *
     * @param  void
     * @return void
     */
    public function test__getChecksumErrors_Invalid() 
    {
        $aResponse = array('unittests are fun', 'next message with some content');
        $sResponse = json_encode($aResponse);
        $oTestObject = $this->getMock('fcpoconfigexport', array('_fcpoGetCheckSumResult'));
        $oTestObject->method('_fcpoGetCheckSumResult')->will($this->returnValue($sResponse));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoCheckClassExists')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals($aResponse, $this->invokeMethod($oTestObject, '_getChecksumErrors'));
    }

    /**
     * Tests _getChecksumErrors if class not exists
     *
     * @param  void
     * @return void
     */
    public function test__getChecksumErrors_ClassNotExists() 
    {
        $aResponse = array('unittests are fun', 'next message with some content');
        $sResponse = json_encode($aResponse);
        $oTestObject = $this->getMock('fcpoconfigexport', array('_fcpoGetCheckSumResult'));
        $oTestObject->method('_fcpoGetCheckSumResult')->will($this->returnValue($sResponse));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoCheckClassExists')->will($this->returnValue(false));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_getChecksumErrors'));
    }

    /**
     * Testing fcpoGetConfigXml for coverage
     */
    public function test_fcpoGetConfigXml_Coverage() 
    {
        $aShopIds = array('1');
        $oTestObject = $this->getMock(
            'fcpoconfigexport', array(
            '_fcpoGetShopIds',
            '_fcpoGetShopXmlGeneric',
            '_fcpoGetShopXmlSystem',
            '_fcpoGetShopXmlGlobal',
            '_fcpoGetShopXmlClearingTypes',
            '_fcpoGetShopXmlProtect',
            '_fcpoGetShopXmlMisc',
            '_fcpoGetShopXmlChecksums',
                )
        );
        $oTestObject->expects($this->any())->method('_fcpoGetShopXmlGeneric')->will($this->returnValue(''));
        $oTestObject->expects($this->any())->method('_fcpoGetShopXmlSystem')->will($this->returnValue(''));
        $oTestObject->expects($this->any())->method('_fcpoGetShopXmlGlobal')->will($this->returnValue(''));
        $oTestObject->expects($this->any())->method('_fcpoGetShopXmlClearingTypes')->will($this->returnValue(''));
        $oTestObject->expects($this->any())->method('_fcpoGetShopXmlProtect')->will($this->returnValue(''));
        $oTestObject->expects($this->any())->method('_fcpoGetShopXmlMisc')->will($this->returnValue(''));
        $oTestObject->expects($this->any())->method('_fcpoGetShopXmlChecksums')->will($this->returnValue(''));

        $aShopConfVars['someIndex'] = 'someValue';
        $aShopConfigs = array($aShopConfVars);
        $this->invokeSetAttribute($oTestObject, '_aShopConfigs', $aShopConfigs);

        $sResponse = $sExpect = $oTestObject->fcpoGetConfigXml();

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing fcpoGetShopIds for coverage
     */
    public function test_fcpoGetShopIds_Coverage() 
    {
        $oTestObject = oxNew('fcpoconfigexport');

        $oMockDatabase = $this->getMock('oxDb', array('getCol'));
        $oMockDatabase->expects($this->any())->method('getCol')->will($this->returnValue('someCol'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals('someCol', $oTestObject->fcpoGetShopIds());
    }

    /**
     * Testing _fcpoSetShopConfigVars for coverage
     */
    public function test__fcpoSetShopConfigVars_Coverage() 
    {
        $aShopIds = array('oxbaseshop');
        $oTestObject = oxNew('fcpoconfigexport');

        $this->assertEquals(null, $oTestObject->_fcpoSetShopConfigVars($aShopIds));
    }

    /**
     * Testing _fcpoGetShopXmlGeneric for coverage
     */
    public function test__fcpoGetShopXmlGeneric_Coverage() 
    {
        $aMockShopConfVars['sShopName'] = 'someShopName';
        $oTestObject = oxNew('fcpoconfigexport');

        $sResponse = $sExpect = $oTestObject->_fcpoGetShopXmlGeneric($aMockShopConfVars);

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetShopXmlSystem for coverage
     */
    public function test__fcpoGetShopXmlSystem_Coverage() 
    {
        $aMockModuleInfo['someIndex'] = 'someInfo';

        $aMockShopConfVars['sShopEdition'] = 'someEdition';
        $aMockShopConfVars['sShopVersion'] = 'someVersion';

        $oTestObject = $this->getMock(
            'fcpoconfigexport', array(
            '_getModuleInfo',
                )
        );
        $oTestObject->expects($this->any())->method('_getModuleInfo')->will($this->returnValue($aMockModuleInfo));

        $sResponse = $sExpect = $oTestObject->_fcpoGetShopXmlSystem($aMockShopConfVars);

        $this->assertEquals($sResponse, $sExpect);
    }

    /**
     * Testing _fcpoGetShopXmlGlobal for coverage
     */
    public function test__fcpoGetShopXmlGlobal_Coverage() 
    {
        $aMockMap = array('from' => 'someFrom', 'to' => 'someTo');
        $aMockMappings = array('someAbbr' => array($aMockMap));

        $aMockShopConfVars['sFCPOMerchantID'] = 'someEdition';
        $aMockShopConfVars['sFCPOSubAccountID'] = 'someVersion';
        $aMockShopConfVars['sFCPOPortalID'] = 'someVersion';
        $aMockShopConfVars['sFCPORefPrefix'] = 'someVersion';

        $oTestObject = $this->getMock('fcpoconfigexport', array('_getMappings'));
        $oTestObject->expects($this->any())->method('_getMappings')->will($this->returnValue($aMockMappings));

        $sResponse = $sExpect = $oTestObject->_fcpoGetShopXmlGlobal($aMockShopConfVars);

        $this->assertEquals($sResponse, $sExpect);
    }

    /**
     * Testing _fcpoGetShopXmlClearingTypes for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoGetShopXmlClearingTypes_Coverage() 
    {
        $this->_fcpoAddSampleStatusmapping();
        $oTestObject = oxNew('fcpoconfigexport');
        $aShopConfVars['sFCPOMerchantID'] = '1';
        $aShopConfVars['sFCPOSubAccountID'] = '2';
        $aShopConfVars['sFCPOPortalID'] = '3';
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_fcpoGetShopXmlClearingTypes', array($aShopConfVars));
        //        $this->assertEquals($aExpect, $aResponse);
        $this->_fcpoTruncateTable('fcpostatusmapping');

        $this->assertContains('<title><![CDATA[AmazonPay]]></title>', $aResponse);
    }

    /**
     * Testing _fcpoGetShopXmlProtect for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoGetShopXmlProtect_Coverage() 
    {
        $oTestObject = oxNew('fcpoconfigexport');
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_fcpoGetShopXmlProtect');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _fcpoGetShopXmlMisc for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoGetShopXmlMisc_Coverage() 
    {
        $this->_fcpoAddSampleForwarding();
        $oTestObject = oxNew('fcpoconfigexport');
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_fcpoGetShopXmlMisc');
        $this->assertEquals($aExpect, $aResponse);
        $this->_fcpoTruncateTable('fcpostatusforwarding');
    }

    /**
     * Testing _fcpoGetShopXmlChecksums for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoGetShopXmlChecksums_Coverage() 
    {
        $aTestSetups = array(
            array('return_getChecksumErrors' => false, 'returnfcpoIniGet' => 0, 'returnfcpoFunctionExists' => false),
            array('return_getChecksumErrors' => array('someError'), 'returnfcpoIniGet' => 1, 'returnfcpoFunctionExists' => false),
            array('return_getChecksumErrors' => array('someError'), 'returnfcpoIniGet' => 1, 'returnfcpoFunctionExists' => true),
            array('return_getChecksumErrors' => false, 'returnfcpoIniGet' => 1, 'returnfcpoFunctionExists' => true),
        );

        foreach ($aTestSetups as $aTestSetup) {
            $oTestObject = $this->getMock('fcpoconfigexport', array('_getChecksumErrors'));
            $oTestObject->method('_getChecksumErrors')->will($this->returnValue($aTestSetup['return_getChecksumErrors']));

            $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
            $oHelper->expects($this->any())->method('fcpoIniGet')->will($this->returnValue($aTestSetup['returnfcpoIniGet']));
            $oHelper->expects($this->any())->method('fcpoFunctionExists')->will($this->returnValue($aTestSetup['returnfcpoFunctionExists']));

            $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

            $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_fcpoGetShopXmlChecksums');
            $this->assertEquals($aExpect, $aResponse);
        }
    }

    /**
     * Testing _getPaymentTypes for coverage
     *
     * @param  void
     * @return void
     */
    public function test__getPaymentTypes_Coverage() 
    {
        $oTestObject = oxNew('fcpoconfigexport');
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_getPaymentTypes');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _getRedPayments for coverage
     *
     * @param  void
     * @return void
     */
    public function test__getRedPayments_Coverage() 
    {
        $oTestObject = oxNew('fcpoconfigexport');
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_getRedPayments');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _getYellowPayments for coverage
     *
     * @param  void
     * @return void
     */
    public function test__getYellowPayments_Coverage() 
    {
        $oTestObject = oxNew('fcpoconfigexport');
        $this->_fcpoAddSamplePayment('150');
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_getYellowPayments');
        $this->_fcpoRemoveSamplePayment();
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _getPaymentCountries for coverage
     *
     * @param  void
     * @return void
     */
    public function test__getPaymentCountries_Coverage() 
    {
        $oTestObject = oxNew('fcpoconfigexport');

        $aMockCountries = array('a7c40f631fc920687.20179984');

        foreach ($aMockCountries as $sCountryId) {
            $oCountry = oxNew('oxcountry');
            if ($oCountry->load($sCountryId)) {
                $sCountries .= $oCountry->oxcountry__oxisoalpha2->value . ',';
            }
        }
        $sExpect = rtrim($sCountries, ',');

        $oMockPayment = $this->getMock('oxPayment', array('getCountries'));
        $oMockPayment->expects($this->any())->method('getCountries')->will($this->returnValue($aMockCountries));

        $sResponse = $this->invokeMethod($oTestObject, '_getPaymentCountries', array($oMockPayment));
        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _getForwardings for coverage
     *
     * @param  void
     * @return void
     */
    public function test__getForwardings_Coverage() 
    {
        $oTestObject = oxNew('fcpoconfigexport');
        $this->_fcpoAddSampleForwarding();
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_getForwardings');
        $this->_fcpoTruncateTable('fcpostatusforwarding');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _getForwardings for coverage
     *
     * @param  void
     * @return void
     */
    public function test__getMappings_Coverage() 
    {
        $oTestObject = oxNew('fcpoconfigexport');
        $this->_fcpoAddSampleStatusmapping();
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_getMappings');
        $this->_fcpoTruncateTable('fcpostatusmapping');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _getModuleInfo for older versions
     *
     * @param  void
     * @return void
     */
    public function test__getModuleInfo_OlderShopVersion() 
    {
        $oTestObject = oxNew('fcpoconfigexport');

        $aModules['key'] = 'value';

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue($aModules));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aExpect['key'] = '<![CDATA[value]]>';

        $aResponse = $this->invokeMethod($oTestObject, '_getModuleInfo');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _getModuleInfo for newer versions
     *
     * @param  void
     * @return void
     */
    public function test__getModuleInfo_NewerShopVersion() 
    {
        $oTestObject = oxNew('fcpoconfigexport');

        $oModuleList = oxNew("oxModuleList");
        $sModulesDir = oxRegistry::getConfig()->getModulesDir();
        $aOxidModules = $oModuleList->getModulesFromDir($sModulesDir);
        foreach ($aOxidModules as $oModule) {
            $aModules[$oModule->getId()] = $oModule->getInfo('version');
        }

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue(oxRegistry::getConfig()));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oModuleList));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aResponse = $this->invokeMethod($oTestObject, '_getModuleInfo');
        $this->assertEquals($aModules, $aResponse);
    }

    /**
     * Testing _fcpoGetMultilangConfStrVarName for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetMultilangConfStrVarName_Coverage() 
    {
        $oTestObject = oxNew('fcpoconfigexport');

        $this->assertEquals('sFCPOApprovalText', $oTestObject->fcpoGetMultilangConfStrVarName('sFCPOApprovalText_0', false));
    }

    /**
     * Lil' paypalexpresslogo database helper
     *
     * @param  void
     * @return void
     */
    protected function _fcpoPreparePaypalExpressLogos() 
    {
        $this->_fcpoTruncateTable('fcpopayoneexpresslogos');
        $sQuery = "
            INSERT INTO `fcpopayoneexpresslogos` (`OXID`, `FCPO_ACTIVE`, `FCPO_LANGID`, `FCPO_LOGO`, `FCPO_DEFAULT`) VALUES
            (1, 1, 0, 'fc_andre_sw_02_250px.1.png', 1),
            (2, 1, 1, 'btn_xpressCheckout_en.gif', 0)
        ";

        oxDb::getDb()->Execute($sQuery);
    }

    /**
     * Creates some entries in fcpoklarnastoreids table
     *
     * @param  void
     * @return void
     */
    protected function _fcpoPrepareKlarnaStoreIdTable() 
    {
        $this->_fcpoTruncateTable('fcpoklarnastoreids');
        $sQuery = "
            INSERT INTO `fcpoklarnastoreids` (`OXID`, `FCPO_STOREID`) VALUES ('1', 'samplestoreid')
        ";

        oxDb::getDb()->Execute($sQuery);
    }

    /**
     * Adds a payment to be used for unit testings
     *
     * @param  string $sOxFromBoni
     * @return void
     */
    protected function _fcpoAddSamplePayment($sOxFromBoni) 
    {
        $this->_fcpoRemoveSamplePayment();
        $sQuery = "
            INSERT INTO  `oxpayments` (
                `OXID` ,
                `OXACTIVE` ,
                `OXDESC` ,
                `OXADDSUM` ,
                `OXADDSUMTYPE` ,
                `OXADDSUMRULES` ,
                `OXFROMBONI` ,
                `OXFROMAMOUNT` ,
                `OXTOAMOUNT` ,
                `OXVALDESC` ,
                `OXCHECKED` ,
                `OXDESC_1` ,
                `OXVALDESC_1` ,
                `OXDESC_2` ,
                `OXVALDESC_2` ,
                `OXDESC_3` ,
                `OXVALDESC_3` ,
                `OXLONGDESC` ,
                `OXLONGDESC_1` ,
                `OXLONGDESC_2` ,
                `OXLONGDESC_3` ,
                `OXSORT` ,
                `OXTIMESTAMP` ,
                `FCPOISPAYONE` ,
                `FCPOAUTHMODE` ,
                `FCPOLIVEMODE`
            )
            VALUES (
                'fcpounittest',  '1',  'Testzahlart',  '0',  'abs',  '0',  '{$sOxFromBoni}',  '0',  '1000000',  'Kreditkarte Channel Frontend',  '0',  '',  '',  '',  '',  '',  '', 'Kreditkarte Channel Frontend',  '',  '',  '',  '0', 
                CURRENT_TIMESTAMP ,  '1',  'preauthorization',  '0'
            )
";

        oxDb::getDb()->Execute($sQuery);
    }

    /**
     * Adds a sample forwarding
     *
     * @param  void
     * @return void
     */
    protected function _fcpoAddSampleForwarding() 
    {
        $this->_fcpoTruncateTable('fcpostatusforwarding');
        $sQuery = "
            INSERT INTO `fcpostatusforwarding` (`OXID`, `FCPO_PAYONESTATUS`, `FCPO_URL`, `FCPO_TIMEOUT`) VALUES
            (6, 'paid', 'http://paid.sample', 10);
        ";

        oxDb::getDb()->Execute($sQuery);
    }

    /**
     * Adds a sample statusmapping
     *
     * @param  void
     * @return void
     */
    protected function _fcpoAddSampleStatusmapping() 
    {
        $this->_fcpoTruncateTable('fcpostatusmapping');
        $sQuery = "
            INSERT INTO `fcpostatusmapping` (`OXID`, `FCPO_PAYMENTID`, `FCPO_PAYONESTATUS`, `FCPO_FOLDER`) VALUES
            (1, 'fcpopaypal', 'capture', 'ORDERFOLDER_FINISHED');
        ";

        oxDb::getDb()->Execute($sQuery);
    }

    /**
     * Removes the sample payment
     *
     * @param  string $sOxFromBoni
     * @return void
     */
    protected function _fcpoRemoveSamplePayment() 
    {
        $sQuery = "
            DELETE FROM oxpayments WHERE OXID = 'fcpounittest'
        ";

        oxDb::getDb()->Execute($sQuery);
    }

    /**
     * Truncates table
     *
     * @param  void
     * @return void
     */
    protected function _fcpoTruncateTable($sTableName) 
    {
        $sQuery = "DELETE FROM `{$sTableName}` WHERE true ";

        oxDb::getDb()->Execute($sQuery);
    }

}
