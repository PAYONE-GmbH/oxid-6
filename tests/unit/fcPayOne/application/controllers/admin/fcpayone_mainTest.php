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

class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_main extends OxidTestCase
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
     * Testing render method on popup
     *
     * @param  void
     * @return void
     */
    public function test_Render_Popup() 
    {
        $oTestObject = $this->getMock('fcpayone_main', array('_fcpoLoadCountryList'));
        $oTestObject->method('_fcpoLoadCountryList')->will($this->returnValue(null));

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getVersion')->will($this->returnValue('4.7'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('someValue'));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('fcpayone_popup_main.tpl', $oTestObject->render());
    }

    /**
     * Testing render method on popup
     *
     * @param  void
     * @return void
     */
    public function test_Render_Popup2() 
    {
        $oTestObject = $this->getMock('fcpayone_main', array('_fcpoLoadCountryList'));
        $oTestObject->method('_fcpoLoadCountryList')->will($this->returnValue(null));

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getVersion')->will($this->returnValue('4.2'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('someValue'));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('fcpayone_popup_main.tpl', $oTestObject->render());
    }

    /**
     * Testing render method on standard
     *
     * @param  void
     * @return void
     */
    public function test_Render_Standard() 
    {
        $oTestObject = $this->getMock('fcpayone_main', array('_fcpoLoadCountryList'));
        $oTestObject->method('_fcpoLoadCountryList')->will($this->returnValue(null));

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getVersion')->will($this->returnValue('4.7'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('fcpayone_main.tpl', $oTestObject->render());
    }

    /**
     * Testing fcpoGetCurrencyIso for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetCurrencyIso_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $oMockCurrency = new stdClass();
        $oMockCurrency->name = 'someCurrency';
        $aMockCurrency = array($oMockCurrency);
        $aExpect = array('someCurrency');

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getCurrencyArray')->will($this->returnValue($aMockCurrency));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals($aExpect, $oTestObject->fcpoGetCurrencyIso());
    }

    /**
     * Testing fcpoGetModuleVersion for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetModuleVersion_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oHelper = $this->getMock('fcpohelper', array('fcpoGetModuleVersion'));
        // $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetModuleVersion')->will($this->returnValue('someValue'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someValue', $oTestObject->fcpoGetModuleVersion());
    }

    /**
     * Testing fcpoGetConfBools for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetConfBools_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $this->invokeSetAttribute($oTestObject, '_aConfBools', 'someValue');

        $this->assertEquals('someValue', $oTestObject->fcpoGetConfBools());
    }

    /**
     * Testing fcpoGetConfStrs for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetConfStrs_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $this->invokeSetAttribute($oTestObject, '_aConfStrs', 'someValue');

        $this->assertEquals('someValue', $oTestObject->fcpoGetConfStrs());
    }

    /**
     * Testing fcpoGetConfArrs for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetConfArrs_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $this->invokeSetAttribute($oTestObject, '_aConfArrs', 'someValue');

        $this->assertEquals('someValue', $oTestObject->fcpoGetConfArrs());
    }

    /**
     * Testing fcpoGetCountryList for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetCountryList_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $this->invokeSetAttribute($oTestObject, '_aCountryList', 'someValue');

        $this->assertEquals('someValue', $oTestObject->fcpoGetCountryList());
    }

    /**
     * Testing render method for code coverage
     *
     * @param  void
     * @return void
     */
    public function test_Save_Coverage() 
    {
        $oTestObject = $this->getMock(
            'fcpayone_main', array(
            '_fcpoCheckAndAddStoreId',
            '_fcpoCheckAndAddCampaign',
            '_fcpoCheckAndAddLogos',
            '_fcpoInsertStoreIds',
            '_fcpoInsertCampaigns',
            '_fcpoCheckRequestAmazonPayConfiguration',
            '_handlePayPalExpressLogos',
            '_fcpoInsertProfiles',
            '_fcpoCheckAndAddRatePayProfile',
            '_fcpoLoadConfigs',
            )
        );
        $oTestObject->method('_fcpoCheckAndAddStoreId')->will($this->returnValue(null));
        $oTestObject->method('_fcpoCheckAndAddCampaign')->will($this->returnValue(null));
        $oTestObject->method('_fcpoCheckAndAddLogos')->will($this->returnValue(null));
        $oTestObject->method('_fcpoInsertStoreIds')->will($this->returnValue(null));
        $oTestObject->method('_fcpoInsertCampaigns')->will($this->returnValue(null));
        $oTestObject->method('_fcpoCheckRequestAmazonPayConfiguration')->will($this->returnValue(null));
        $oTestObject->method('_handlePayPalExpressLogos')->will($this->returnValue(null));
        $oTestObject->method('_fcpoInsertProfiles')->will($this->returnValue(null));
        $oTestObject->method('_fcpoCheckAndAddRatePayProfile')->will($this->returnValue(null));
        $oTestObject->method('_fcpoLoadConfigs')->will($this->returnValue(null));

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('saveShopConfVar')->will($this->returnValue(true));
        $oMockConfig->expects($this->any())->method('getShopId')->will($this->returnValue('someShopId'));

        $aConfVars = array();
        $aConfVars['sFCPOApprovalText'] = 'VarValue';

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aConfVars));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);


        $this->assertEquals(null, $oTestObject->save());
    }

    /**
     * Testing load country list for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoLoadCountryList_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oMockLang = $this->getMock('oxLang', array('getTplLanguage'));
        $oMockLang->expects($this->any())->method('getTplLanguage')->will($this->returnValue('0'));

        $oMockCountryList = oxNew('oxCountryList');
        $oMockCountry = oxNew('oxCountry');
        $oMockCountry->oxcountries__oxid = new oxField('someId');
        $oMockCountryList->add($oMockCountry);

        $aConfArrs['aFCPODebitCountries'] = array('someId');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCountryList));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_aConfArrs', $aConfArrs);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoLoadCountryList'));
    }

    /**
     * Testing load configs for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoLoadConfigs_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoLoadConfigs', array('oxbaseshop')));
    }

    /**
     * Testing insert campaigns for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoInsertCampaigns_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $aCampaignData_1['delete'] = 'eenie';
        $aCampaignData_1['code'] = 'meenie';
        $aCampaignData_1['title'] = 'miney';
        $aCampaignData_1['language'] = 'moh';
        $aCampaignData_1['currency'] = 'catch';
        $aCampaignData_2['code'] = 'the';
        $aCampaignData_2['title'] = 'tiger';
        $aCampaignData_2['language'] = 'by';
        $aCampaignData_2['currency'] = 'its';

        $aCampaigns = array(
            '1' => $aCampaignData_1,
            '2' => $aCampaignData_2,
        );

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aCampaigns));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockDb = $this->getMock('oxDb', array('Execute'));
        $oMockDb->expects($this->any())->method('Execute')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoInsertCampaigns'));
    }

    /**
     * Testing _fcpoInsertStoreIds for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoInsertStoreIds_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $aStoreIdData_1['delete'] = 'eenie';
        $aStoreIdData_1['id'] = 'meenie';
        $aStoreIdData_2['id'] = 'miney';

        $aStoreIds = array(
            '1' => $aStoreIdData_1,
            '2' => $aStoreIdData_2,
        );

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aStoreIds));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockDb = $this->getMock('oxDb', array('Execute'));
        $oMockDb->expects($this->any())->method('Execute')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoInsertStoreIds'));
    }

    /**
     * Testing _fcpoInsertProfiles coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoInsertProfiles_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $aMockData = array('some'=>'Data');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockRatePay = $this->getMockBuilder('fcporatepay')->disableOriginalConstructor()->getMock();
        $oMockRatePay->expects($this->any())->method('fcpoInsertProfile')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoRatePay', $oMockRatePay);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoInsertProfiles'));
    }

    /**
     * Testing _fcpoCheckAndAddStoreId for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoCheckAndAddStoreId_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoCheckAndAddStoreId'));
    }

    /**
     * Testing _fcpoCheckAndAddRatePayProfile for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoCheckAndAddRatePayProfile_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockRatePay = $this->getMockBuilder('fcporatepay')->disableOriginalConstructor()->getMock();
        $oMockRatePay->expects($this->any())->method('fcpoAddRatePayProfile')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoRatePay', $oMockRatePay);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoCheckAndAddRatePayProfile'));
    }

    /**
     * Testing _fcpoCheckRequestAmazonPayConfiguration for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoCheckRequestAmazonPayConfiguration_Coverage() {
        $oTestObject = $this->getMock('fcpayone_main', array('_fcpoRequestAndAddAmazonConfig'));
        $oTestObject->method('_fcpoRequestAndAddAmazonConfig')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslation'));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null,$this->invokeMethod($oTestObject, '_fcpoCheckRequestAmazonPayConfiguration'));
    }

    /**
     * Testing _fcpoRequestAndAddAmazonConfig for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoRequestAndAddAmazonConfig_Coverage() {
        $oTestObject = $this->getMock('fcpayone_main', array('_fcpoSaveAmazonConfigFromResponse'));
        $oTestObject->method('_fcpoSaveAmazonConfigFromResponse')->will($this->returnValue(true));

        $aMockResponse = array('someConfig');
        $oMockRequest = $this->getMock('fcporequest', array('sendRequestGetAmazonPayConfiguration'));
        $oMockRequest->expects($this->any())->method('sendRequestGetAmazonPayConfiguration')->will($this->returnValue($aMockResponse));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoRequestAndAddAmazonConfig());
    }

    /**
     * Testing _fcpoSaveAmazonConfigFromResponse for coverage
     */
    public function test__fcpoSaveAmazonConfigFromResponse_Coverage() {
        $oTestObject = oxNew('fcpayone_main');

        $oMockConfig = $this->getMock('oxConfig', array('saveShopConfVar'));
        $oMockConfig->expects($this->any())->method('saveShopConfVar')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'status' => 'OK',
            'add_paydata[seller_id]' => 'someSellerId',
            'add_paydata[client_id]' => 'someClientId',
        );

        $this->assertEquals(true, $oTestObject->_fcpoSaveAmazonConfigFromResponse($aMockResponse));
    }

    /**
     * Testing _fcpoCheckAndAddCampaign for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoCheckAndAddCampaign_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoCheckAndAddCampaign'));
    }

    /**
     * Testing _fcpoCheckAndAddLogos for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoCheckAndAddLogos_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoCheckAndAddLogos'));
    }

    /**
     * Testing _handlePayPalExpressLogos for coverage
     *
     * @param  void
     * @return void
     */
    public function test__handlePayPalExpressLogos_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $aLogo_1['active'] = 'eenie';
        $aLogo_1['langid'] = 'meenie';

        $aLogos = array(
            '1' => $aLogo_1,
        );

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls($aLogos, 1));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_aAdminMessages', array('someMessage'));

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_handlePayPalExpressLogos'));
    }

    /**
     * Testing fcpoIsLogoAdded for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoIsLogoAdded_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $this->assertFalse($oTestObject->fcpoIsLogoAdded());
    }

    /**
     * Testing fcpoIsCampaignAdded for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoIsCampaignAdded_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $this->assertFalse($oTestObject->fcpoIsCampaignAdded());
    }

    /**
     * Testing fcpoIsStoreIdAdded for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoIsStoreIdAdded_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $this->assertFalse($oTestObject->fcpoIsStoreIdAdded());
    }

    /**
     * Testing fcpoKlarnaCampaigns for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetStoreIds_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $this->_fcpoPrepareKlarnaStoreIdTable();

        $aExpect = array('1' => 'samplestoreid');

        $oKlarna = $this->getMockBuilder('fcpoklarna')->disableOriginalConstructor()->getMock();
        $oKlarna->expects($this->any())->method('fcpoGetStoreIds')->will($this->returnValue($aExpect));
        $this->invokeSetAttribute($oTestObject, '_oFcpoKlarna', $oKlarna);

        $this->assertEquals($aExpect, $oTestObject->fcpoGetStoreIds());
        $this->_fcpoTruncateTable('fcpoklarnastoreids');
    }

    /**
     * Testing fcpoGetRatePayProfiles for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetRatePayProfiles_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $aMockData = $aExpect = array('some'=>'Data');

        $oMockRatePay = $this->getMockBuilder('fcporatepay')->disableOriginalConstructor()->getMock();
        $oMockRatePay->expects($this->any())->method('fcpoGetRatePayProfiles')->will($this->returnValue($aMockData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoRatePay', $oMockRatePay);

        $this->assertEquals($aExpect, $oTestObject->fcpoGetRatepayProfiles());
    }

    /**
     * Testing fcpoKlarnaCampaigns for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoKlarnaCampaigns_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $aResponse = $aExpect = $oTestObject->fcpoKlarnaCampaigns();

        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcGetAdminSeperator for older shop versions
     *
     * @param  void
     * @return void
     */
    public function test_fcGetAdminSeperator_OlderShopVersion() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('?', $oTestObject->fcGetAdminSeperator());
    }

    /**
     * Testing fcGetAdminSeperator for newer versions
     *
     * @param  void
     * @return void
     */
    public function test_fcGetAdminSeperator_NewerShopVersion() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('&', $oTestObject->fcGetAdminSeperator());
    }

    /**
     * Testing _getPaymentAbbreviation for coverage
     *
     * @param  void
     * @return void
     */
    public function test__getPaymentAbbreviation_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $sResponse = $this->invokeMethod($oTestObject, '_getPaymentAbbreviation', array('fcpobarzahlen'));
        $this->assertEquals('csh', $sResponse);
    }

    /**
     * Testing _fcpoGetCheckSumResult for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoGetCheckSumResult_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oMockScript = $this->getMock('fcCheckChecksum', array('checkChecksumXml'));
        $oMockScript->expects($this->any())->method('checkChecksumXml')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetInstance')->will($this->returnValue($oMockScript));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someValue', $oTestObject->_fcpoGetCheckSumResult());
    }

    /**
     * Testing export for getting the coverage
     *
     * @param  void
     * @return void
     */
    public function test_Export_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oMockConfigExport = $this->getMock('fcpoconfigexport', array('fcpoExportConfig'));
        $oMockConfigExport->expects($this->any())->method('fcpoExportConfig')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockConfigExport));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->export());
    }

    /**
     * Testing fcGetLanguages for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcGetLanguages_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oLang = new stdClass();
        $oLang->active = 1;
        $oLang->oxid = 'someOxid';
        $oLang->name = 'someName';
        $aLanguages[] = $oLang;

        $oMockLang = $this->getMockBuilder('oxLang')->disableOriginalConstructor()->getMock();
        $oMockLang->expects($this->any())->method('getLanguageArray')->will($this->returnValue($aLanguages));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aExpect[$oLang->oxid] = $oLang->name;

        $this->assertEquals($aExpect, $this->invokeMethod($oTestObject, 'fcGetLanguages'));
    }

    /**
     * Testing fcGetCurrencies for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcGetCurrencies_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oCurrency = new stdClass();
        $oCurrency->name = 'someName';
        $aCurrencies[] = $oCurrency;

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getCurrencyArray')->will($this->returnValue($aCurrencies));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aExpect[$oCurrency->name] = $oCurrency->name;

        $this->assertEquals($aExpect, $this->invokeMethod($oTestObject, 'fcGetCurrencies'));
    }

    /**
     * Testing fcpoGetPayPalLogos for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetPayPalLogos_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $this->_fcpoPreparePaypalExpressLogos();
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, 'fcpoGetPayPalLogos');
        $this->assertEquals($aExpect, $aResponse);
        $this->_fcpoTruncateTable('fcpopayoneexpresslogos');
    }

    /**
     * Testing getCCFields on coverage
     *
     * @param  void
     * @return void
     */
    public function test_getCCFields_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $aExpect = array(
            'Number',
            'CVC',
            'Month',
            'Year',
        );

        $this->assertEquals($aExpect, $oTestObject->getCCFields());
    }

    /**
     * Testing getCCTypes for coverage
     *
     * @param  void
     * @return void
     */
    public function test_getCCTypes_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, 'getCCTypes', array('Month'));
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing getCCStyles for coverage
     *
     * @param  void
     * @return void
     */
    public function test_getCCStyles_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, 'getCCStyles');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing getConfigParam for coverage
     *
     * @param  void
     * @return void
     */
    public function test_getConfigParam_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sResponse = $this->invokeMethod($oTestObject, 'getConfigParam', array('initValue'));
        $sExpect = 'someValue';

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing fcpoGetJsCardPreviewCode for coverage
     *
     * @param  void
     * @return void
     */
    public function test_fcpoGetJsCardPreviewCode_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, 'fcpoGetJsCardPreviewCode');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _fcpoGetJsPreviewCodeErrorBlock for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoGetJsPreviewCodeErrorBlock_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_fcpoGetJsPreviewCodeErrorBlock');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _fcpoGetJsPreviewCodeDefaultStyle for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoGetJsPreviewCodeDefaultStyle_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_fcpoGetJsPreviewCodeDefaultStyle');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _fcpoGetJsPreviewCodeFields for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoGetJsPreviewCodeFields_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $aResponse = $aExpect = $this->invokeMethod($oTestObject, '_fcpoGetJsPreviewCodeFields');
        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _fcGetJsPreviewCodeValue for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcGetJsPreviewCodeValue_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aTestSetups = array(
            array('params' => array('selector', 'someConfVal', true, true), 'expect' => 'someConfVal'),
            array('params' => array('style', 'someConfVal', true, true), 'expect' => 'someValue'),
            array('params' => array('width', 'someConfVal', true, true), 'expect' => 'someValue'),
            array('params' => array('someValue', 'someConfVal', false, false), 'expect' => 'someValue'),
        );

        foreach ($aTestSetups as $aCurrentTestSetup) {
            $aResponse = $this->invokeMethod($oTestObject, '_fcGetJsPreviewCodeValue', $aCurrentTestSetup['params']);
            $this->assertEquals($aCurrentTestSetup['expect'], $aResponse);
        }
    }

    /**
     * Testing _fcpoSetDefault for coverage
     *
     * @param  void
     * @return void
     */
    public function test__fcpoSetDefault_Coverage() 
    {
        $oTestObject = oxNew('fcpayone_main');

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('saveShopConfVar')->will($this->returnValue(null));
        $oMockConfig->expects($this->any())->method('getShopConfVar')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someValue', $oTestObject->_fcpoSetDefault(array('some'=>'Data'), 'any', 'value'));
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
            INSERT INTO `oxpayments` (`OXID`, `OXACTIVE`, `OXDESC`, `OXADDSUM`, `OXADDSUMTYPE`, `OXADDSUMRULES`, `OXFROMBONI`, `OXFROMAMOUNT`, `OXTOAMOUNT`, `OXVALDESC`, `OXCHECKED`, `OXDESC_1`, `OXVALDESC_1`, `OXDESC_2`, `OXVALDESC_2`, `OXDESC_3`, `OXVALDESC_3`, `OXLONGDESC`, `OXLONGDESC_1`, `OXLONGDESC_2`, `OXLONGDESC_3`, `OXSORT`, `OXTSPAYMENTID`, `OXTIMESTAMP`, `FCPOISPAYONE`, `FCPOAUTHMODE`, `FCPOLIVEMODE`) VALUES
            ('fcpounittest', 1, 'Testzahlart', 0, 'abs', 0, '{$sOxFromBoni}', 0, 1000000, '', 0, 'Kreditkarte Channel Frontend', '', '', '', '', '', '', '', '', '', 0, '', '2016-04-27 15:37:25', 1, 'preauthorization', 0);
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
        $sQuery = "DELETE FROM `{$sTableName}` ";

        oxDb::getDb()->Execute($sQuery);
    }

}
