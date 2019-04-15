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
 
class MockResult
{
    public $EOF = false;
    
    public $fields = array('someValue','someValue','someValue','someValue', 'someValue');
    
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
 * Description of fcPayOnePaymentTest
 *
 * @author Andre Gregor-Herrmann <andre.herrmann@fatchip.de>
 * @author Fatchip GmbH
 * @date   2016-05-10
 */
class Unit_fcPayOne_Extend_Application_Models_fcPayOnePaymentTest extends OxidTestCase
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
        $method     = $reflection->getMethod($methodName);
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
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }
    
    
    /**
     * Testing fcIsPayOnePaymentType on having a payone payment
     */
    public function test_fcIsPayOnePaymentType_IsPayone() 
    {
        $oTestObject = oxNew('fcPayOnePayment'); 
        $this->assertEquals(true, $oTestObject->fcIsPayOnePaymentType('fcpoinvoice'));
    }

    /**
     * Testing fcpoGetOperationMode for coverage
     */
    public function test_fcpoGetOperationMode_Coverage() 
    {
        $oTestObject = oxNew('fcPayOnePayment');
        $oTestObject->oxpayments__fcpolivemode = new oxField(true);
        
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals('live', $oTestObject->fcpoGetOperationMode('fcpoonlineueberweisung'));
    }
    
    
    /**
     * Testing getDynValues for coverage
     */
    public function test_getDynValues_Coverage() 
    {
        $oDynValue          = new stdClass();
        $oDynValue->name    = 'fcpo_elv_blz';
        $oDynValue->value   = '';
        $aMockDynValues[]   = $oDynValue;
        
        $oTestObject = $this->getMock('fcPayOnePayment', array('_fcGetDynValues'));
        $oTestObject->expects($this->any())->method('_fcGetDynValues')->will($this->returnValue($aMockDynValues));
        
        $this->assertEquals($aMockDynValues, $oTestObject->getDynValues());
    }
    
    
    /**
     * Testing fcpoGetCountryIsoAlphaById for coverage
     */
    public function test_fcpoGetCountryIsoAlphaById_Coverage() 
    {
        $oTestObject = oxNew('fcPayOnePayment');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        
        $this->assertEquals('someValue', $oTestObject->fcpoGetCountryIsoAlphaById('someCountryId'));
    }
    
    
    /**
     * Testing fcpoGetCountryNameById for coverage
     */
    public function test_fcpoGetCountryNameById_Coverage() 
    {
        $oTestObject = oxNew('fcPayOnePayment');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someName'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        
        $this->assertEquals('someName', $oTestObject->fcpoGetCountryNameById('someCountryId'));
    }
    
    
    /**
     * Testing fcpoAddMandateToDb
     */
    public function test_fcpoAddMandateToDb_Coverage() 
    {
        $oTestObject = oxNew('fcPayOnePayment');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        
        $this->assertEquals('someValue', $oTestObject->fcpoGetCountryNameById('someCountryId'));
    }
    
    
    /**
     * Testing _fcpoGetKlarnaStoreId for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcpoGetKlarnaStoreId_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOnePayment', array('getUserBillCountryId'));
        $oTestObject->expects($this->any())->method('getUserBillCountryId')->will($this->returnValue(true));
        
        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someStoreId'));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        
        $this->assertEquals('someStoreId', $this->invokeMethod($oTestObject, 'fcpoGetKlarnaStoreId'));
    }
    
    
    /**
     * Testing fcpoGetUserPaymentId for coverage
     */
    public function test_fcpoGetUserPaymentId_Coverage() 
    {
        $oTestObject = oxNew('fcPayOnePayment');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        
        $this->assertEquals('someValue', $oTestObject->fcpoGetUserPaymentId('someUserId', 'somePaymentType'));
    }
    
    
    /**
     * Testing _fcGetDynValues for coverage
     */
    public function test__fcGetDynValues_Coverage() 
    { 
        $oDynValue = new stdClass();
        $oDynValue->name    = 'fcpo_elv_blz';
        $oDynValue->value   = '';
        $aExpectDynValues[] = $oDynValue;
        $oDynValue          = new stdClass();
        $oDynValue->name    = 'fcpo_elv_ktonr';
        $oDynValue->value   = '';
        $aExpectDynValues[] = $oDynValue;
        $oDynValue          = new stdClass();
        $oDynValue->name    = 'fcpo_elv_iban';
        $oDynValue->value   = '';
        $aExpectDynValues[] = $oDynValue;
        $oDynValue          = new stdClass();
        $oDynValue->name    = 'fcpo_elv_bic';
        $oDynValue->value   = '';
        $aExpectDynValues[] = $oDynValue;
        
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        
        $oTestObject = $this->getMock('fcPayOnePayment', array('getId'));
        $oTestObject->expects($this->any())->method('getId')->will($this->returnValue('fcpodebitnote'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
            
        $this->assertEquals($aExpectDynValues, $oTestObject->_fcGetDynValues(null));
    }
    
    
    /**
     * Testing fcBoniCheckNeeded for coverage
     */
    public function test_fcBoniCheckNeeded_Coverage() 
    {
        $oTestObject = oxNew('fcPayOnePayment'); 
        $this->assertEquals(false, $oTestObject->fcBoniCheckNeeded());
    }
    
    
    /**
     * Testing fcpoGetMandateText for coverage
     */
    public function test_fcpoGetMandateText_Coverage() 
    {
        $aMockMandate = array('mandate_status'=>'pending','mandate_text'=>'someText');
        
        $oTestObject = oxNew('fcPayOnePayment'); 
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($aMockMandate));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals($aMockMandate['mandate_text'], $oTestObject->fcpoGetMandateText());
    }
    
    
    /**
     * Testing _fcGetCountries for coverage
     */
    public function test__fcGetCountries_Coverage() 
    {
        $aExpect = array('someValue');
        
        $aMockResult = array(array('someValue','someValue','someValue','someValue', 'someValue'));
        $oMockDatabase = $this->getMock('oxDb', array('getAll'));
        $oMockDatabase->expects($this->any())->method('getAll')->will($this->returnValue($aMockResult));
        
        $oTestObject = oxNew('fcPayOnePayment');
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        
        $this->assertEquals($aExpect, $oTestObject->_fcGetCountries('someId'));
    }
    

    /**
     * Testing fcpoGetKlarnaCampaigns for coverage
     */
    public function test_fcpoGetKlarnaCampaigns_Coverage() 
    {
        $oMockCurrency          = new stdClass();
        $oMockCurrency->name    = 'someName';
        
        $oMockLang = $this->getMock('oxLang', array('getLanguageAbbr'));
        $oMockLang->expects($this->any())->method('getLanguageAbbr')->will($this->returnValue('someValue'));
        
        $oMockConfig = $this->getMock('oxConfig', array('getActShopCurrencyObject'));
        $oMockConfig->expects($this->any())->method('getActShopCurrencyObject')->will($this->returnValue($oMockCurrency));
        
        $aMockCountries                 = array();
        $oMockUser                      = new stdClass();
        $oMockUser->oxuser__oxcountryid = new oxField('someCountryId');
        
        $oTestObject = $this->getMock('fcPayOnePayment', array('_fcGetCountries', 'getUser'));
        $oTestObject->expects($this->any())->method('_fcGetCountries')->will($this->returnValue($aMockCountries));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $aMockResult    = array(array('someValue','someValue','someValue','someValue', 'someValue'));
        $oMockDatabase  = $this->getMock('oxDb', array('getAll'));
        $oMockDatabase->expects($this->any())->method('getAll')->will($this->returnValue($aMockResult));

        $oMockConfig = $this->getMock('oxConfig', array('getActShopCurrencyObject'));
        $oMockConfig->expects($this->any())->method('getActShopCurrencyObject')->will($this->returnValue($oMockCurrency));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        
        $aResponse = $aExpect = $oTestObject->fcpoGetKlarnaCampaigns(false);
        
        $this->assertEquals($aExpect, $aResponse);
    }
    
    
    /**
     * Testing fcpoGetKlarnaCampaigns with get all option
     */
    public function test_fcpoGetKlarnaCampaigns_GetAll() 
    {
        $oMockCurrency          = new stdClass();
        $oMockCurrency->name    = 'someName';
        
        $oMockLang = $this->getMock('oxLang', array('getLanguageAbbr'));
        $oMockLang->expects($this->any())->method('getLanguageAbbr')->will($this->returnValue('someValue'));
        
        $oMockConfig = $this->getMock('oxConfig', array('getActShopCurrencyObject'));
        $oMockConfig->expects($this->any())->method('getActShopCurrencyObject')->will($this->returnValue($oMockCurrency));
        
        $aMockCountries                 = array();
        $oMockUser                      = new stdClass();
        $oMockUser->oxuser__oxcountryid = new oxField('someCountryId');
        
        $oTestObject = $this->getMock('fcPayOnePayment', array('_fcGetCountries', 'getUser'));
        $oTestObject->expects($this->any())->method('_fcGetCountries')->will($this->returnValue($aMockCountries));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $aMockResult    = array(array('someValue','someValue','someValue','someValue', 'someValue'));
        $oMockDatabase  = $this->getMock('oxDb', array('getAll'));
        $oMockDatabase->expects($this->any())->method('getAll')->will($this->returnValue($aMockResult));

        $oMockConfig = $this->getMock('oxConfig', array('getActShopCurrencyObject'));
        $oMockConfig->expects($this->any())->method('getActShopCurrencyObject')->will($this->returnValue($oMockCurrency));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        
        $aResponse = $aExpect = $oTestObject->fcpoGetKlarnaCampaigns(true);
        
        $this->assertEquals($aExpect, $aResponse);
    }
    
    
    /**
     * Testing fcpoGetMode coverage
     */
    public function test_fcpoGetMode_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOnePayment', array('getId', 'fcpoGetOperationMode'));
        $oTestObject->expects($this->any())->method('getId')->will($this->returnValue('fcpoonlineueberweisung'));
        $oTestObject->expects($this->any())->method('fcpoGetOperationMode')->will($this->returnValue('someValue'));
        
        $aMockDynValues = array('fcpo_ccmode'=>'someValue','fcpo_sotype'=>'someValue');
        
        $this->assertEquals('someValue', $oTestObject->fcpoGetMode($aMockDynValues));
    }
    
}
