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
  
class MockResultOrder
{

    public $EOF = false;
    public $fields = array('someValue');

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
 * Description of fcPayOneOrderTest
 *
 * @author Andre Gregor-Herrmann <andre.herrmann@fatchip.de>
 * @author Fatchip GmbH
 * @date   2016-05-04
 */
class Unit_fcPayOne_Extend_Application_Models_fcPayOneOrder extends OxidTestCaseCompatibilityWrapper
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
     * Testing isPayOnePaymentType for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_isPayOnePaymentType_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__oxpaymenttype = new oxField('fcpodebitnote');

        $this->assertEquals(true, $oTestObject->isPayOnePaymentType());
    }

    /**
     * Testing isPayOneIframePayment for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_isPayOneIframePayment_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__oxpaymenttype = new oxField('fcpodebitnote');

        $this->assertEquals(false, $oTestObject->isPayOneIframePayment());
    }

    /**
     * Testing fcpoDoesUserAlreadyExist for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcpoDoesUserAlreadyExist_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someUserId'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals('someUserId', $oTestObject->fcpoDoesUserAlreadyExist('someMail'));
    }

    /**
     * Testing fcpoGetIdByUserName for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcpoGetIdByUserName_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someUserId'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals('someUserId', $oTestObject->fcpoGetIdByUserName('someUserName'));
    }

    /**
     * Testing fcpoGetIdByCode for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcpoGetIdByCode_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals('someValue', $oTestObject->fcpoGetIdByCode('someCode'));
    }

    /**
     * Testing fcpoGetSalByFirstName for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcpoGetSalByFirstName_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someSalutation'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals('someSalutation', $oTestObject->fcpoGetSalByFirstName('someFirstname'));
    }

    /**
     * Testing fcpoGetAddressIdByResponse for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcpoGetAddressIdByResponse_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoGetIdByCode'));
        $oTestObject->expects($this->any())->method('_fcpoGetIdByCode')->will($this->returnValue('someId'));

        $oMockDatabase = $this->getMock('oxDb', array('GetOne', 'quote'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someAddressId'));
        $oMockDatabase->expects($this->any())->method('quote')->will($this->returnValue('someValue'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $aMockResponse = array();
        $aMockResponse['add_paydata[shipping_firstname]'] = 'someFirstname';
        $aMockResponse['add_paydata[shipping_lastname]'] = 'someLastname';
        $aMockResponse['add_paydata[shipping_city]'] = 'someCity';
        $aMockResponse['add_paydata[shipping_zip]'] = 'someZip';
        $aMockResponse['add_paydata[shipping_country]'] = 'someCountry';

        $this->assertEquals('someAddressId', $oTestObject->fcpoGetAddressIdByResponse($aMockResponse, 'someStreet', 'someStreetNr'));
    }

    /**
     * Testing _fcProcessUserAgentInfo for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcProcessUserAgentInfo_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals('someStupidAgent', $this->invokeMethod($oTestObject, '_fcProcessUserAgentInfo', array('someStupidAgent')));
    }

    /**
     * Testing _fcpoCheckUserAgent for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoCheckUserAgent_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcProcessUserAgentInfo', '_fcGetCurrentVersion', '_fcpoValidateToken'));
        $oTestObject->expects($this->any())->method('_fcProcessUserAgentInfo')->will($this->onConsecutiveCalls('someVar', 'someOtherVar'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4700));
        $oTestObject->expects($this->any())->method('_fcpoValidateToken')->will($this->returnValue(true));

        $oMockUtilsServer = $this->getMock('oxUtilsServer', array('getServerVar'));
        $oMockUtilsServer->expects($this->any())->method('getServerVar')->will($this->returnValue('someVar'));

        $oMockSession = $this->getMock('oxSession', array('getRemoteAccessToken'));
        $oMockSession->expects($this->any())->method('getRemoteAccessToken')->will($this->returnValue('someVar'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsServer')->will($this->returnValue($oMockUtilsServer));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someValue'));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('someOtherVar'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoCheckUserAgent'));
    }

    /**
     * Testing _fcpoValidateToken for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoValidateToken_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(false, $this->invokeMethod($oTestObject, '_fcpoValidateToken', array('someVar', 'someOtherVar')));
    }

    /**
     * Testing _fcGetCurrentVersion for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcGetCurrentVersion_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4800));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(4800, $this->invokeMethod($oTestObject, '_fcGetCurrentVersion'));
    }

    /**
     * Testing _isRedirectAfterSave for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__isRedirectAfterSave_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(false));

        $this->invokeSetAttribute($oTestObject, '_blIsRedirectAfterSave', null);
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_isRedirectAfterSave());
    }

    /**
     * Testing finalizeOrder for case order is not payone   
     * 
     * @param  void
     * @return void
     */
    public function test_finalizeOrder_NoPayone() 
    {
        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('somePaymentId'));

        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));

        $oMockUtils = $this->getMock('oxUtils', array('logger'));
        $oMockUtils->expects($this->any())->method('logger')->will($this->returnValue(true));

        $oTestObject = $this->getMock(
            'fcPayOneOrder', array(
            'isPayOnePaymentType',
            '_checkOrderExist',
            'validateOrder',
            '_loadFromBasket',
            '_setUser',
            '_setPayment',
            '_fcpoCheckRefNr',
            '_executePayment',
            'save',
            '_fcGetCurrentVersion',
            '_updateOrderDate',
            '_setOrderStatus',
            '_updateWishlist',
            '_updateNoticeList',
            '_markVouchers',
            '_sendOrderByEmail',
            'setId',
            '_setFolder',
            '_fcpoProcessOrder',
            '_executeTsProtection',
            'getTsProductId',
            '_isRedirectAfterSave',
            '_fcpoEarlyValidation',
            '_fcpoHandleBasket',
            '_fcpoExecutePayment',
            '_fcpoSaveAfterRedirect',
            '_fcpoHandleTsProtection',
            '_fcpoSetOrderStatus',
            '_fcpoMarkVouchers',
            '_fcpoFinishOrder',
                )
        );
        //        $oTestObject = $this->getMockBuilder('fcPayOneOrder')->disableOriginalConstructor()->getMock();        
        $oTestObject->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_checkOrderExist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('validateOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_loadFromBasket')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setPayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckRefNr')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executePayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('_updateOrderDate')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_updateWishlist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_updateNoticeList')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_markVouchers')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_sendOrderByEmail')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('setId')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setFolder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoProcessOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executeTsProtection')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getTsProductId')->will($this->returnValue('someTsId'));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoEarlyValidation')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoHandleBasket')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoExecutePayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSaveAfterRedirect')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleTsProtection')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoSetOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoMarkVouchers')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoFinishOrder')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(3, $oTestObject->finalizeOrder($oMockBasket, $oMockUser, false));
    }

    /**
     * Testing finalizeOrder for case order is payone   
     * 
     * @param  void
     * @return void
     */
    public function test_finalizeOrder_IsPayone() 
    {
        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('somePaymentId'));

        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));

        $oMockUtils = $this->getMock('oxUtils', array('logger'));
        $oMockUtils->expects($this->any())->method('logger')->will($this->returnValue(true));

        $oTestObject = $this->getMock(
            'fcPayOneOrder', array(
            'isPayOnePaymentType',
            '_checkOrderExist',
            'validateOrder',
            '_loadFromBasket',
            '_setUser',
            '_setPayment',
            '_fcpoCheckRefNr',
            '_executePayment',
            'save',
            '_fcGetCurrentVersion',
            '_updateOrderDate',
            '_setOrderStatus',
            '_updateWishlist',
            '_updateNoticeList',
            '_markVouchers',
            '_sendOrderByEmail',
            'setId',
            '_setFolder',
            '_fcpoProcessOrder',
            '_executeTsProtection',
            'getTsProductId',
            '_isRedirectAfterSave',
            '_fcpoEarlyValidation',
            '_fcpoHandleBasket',
            '_fcpoExecutePayment',
            '_fcpoSaveAfterRedirect',
            '_fcpoHandleTsProtection',
            '_fcpoSetOrderStatus',
            '_fcpoMarkVouchers',
            '_fcpoFinishOrder',
                )
        );
        $oTestObject->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_checkOrderExist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('validateOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_loadFromBasket')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setPayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckRefNr')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executePayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('_updateOrderDate')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_updateWishlist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_updateNoticeList')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_markVouchers')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_sendOrderByEmail')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('setId')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setFolder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoProcessOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executeTsProtection')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getTsProductId')->will($this->returnValue('someTsId'));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoEarlyValidation')->will($this->returnValue(1));
        $oTestObject->expects($this->any())->method('_fcpoHandleBasket')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoExecutePayment')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoSaveAfterRedirect')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleTsProtection')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoMarkVouchers')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoFinishOrder')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(1, $oTestObject->finalizeOrder($oMockBasket, $oMockUser, false));
    }

    /**
     * Testing finalizeOrder for case order is payone and order exists   
     * 
     * @param  void
     * @return void
     */
    public function test_finalizeOrder_IsPayone_OrderExists() 
    {
        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('somePaymentId'));

        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));

        $oMockUtils = $this->getMock('oxUtils', array('logger'));
        $oMockUtils->expects($this->any())->method('logger')->will($this->returnValue(true));

        $oTestObject = $this->getMock(
            'fcPayOneOrder', array(
            'isPayOnePaymentType',
            '_checkOrderExist',
            'validateOrder',
            '_loadFromBasket',
            '_setUser',
            '_setPayment',
            '_fcpoCheckRefNr',
            '_executePayment',
            'save',
            '_fcGetCurrentVersion',
            '_updateOrderDate',
            '_setOrderStatus',
            '_updateWishlist',
            '_updateNoticeList',
            '_markVouchers',
            '_sendOrderByEmail',
            'setId',
            '_setFolder',
            '_fcpoProcessOrder',
            '_executeTsProtection',
            'getTsProductId',
            '_isRedirectAfterSave',
            '_fcpoEarlyValidation',
            '_fcpoHandleBasket',
            '_fcpoExecutePayment',
            '_fcpoSaveAfterRedirect',
            '_fcpoHandleTsProtection',
            '_fcpoSetOrderStatus',
            '_fcpoMarkVouchers',
            '_fcpoFinishOrder',
            '_fcpoAdjustAmazonPayUserDetails'
                )
        );
        $oTestObject->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_checkOrderExist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('validateOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_loadFromBasket')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setPayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckRefNr')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executePayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('_updateOrderDate')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_updateWishlist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_updateNoticeList')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_markVouchers')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_sendOrderByEmail')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('setId')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setFolder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoProcessOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executeTsProtection')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getTsProductId')->will($this->returnValue('someTsId'));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoEarlyValidation')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoHandleBasket')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoExecutePayment')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoSaveAfterRedirect')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleTsProtection')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoMarkVouchers')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoFinishOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoAdjustAmazonPayUserDetails');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(1, $oTestObject->finalizeOrder($oMockBasket, $oMockUser, false));
    }

    /**
     * Testing finalizeOrder for case order is payone and order to be recalculated and having orderstate 
     * 
     * @param  void
     * @return void
     */
    public function test_finalizeOrder_IsPayone_RecalcOrderHasOrderState() 
    {
        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('somePaymentId'));

        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));

        $oMockUtils = $this->getMock('oxUtils', array('logger'));
        $oMockUtils->expects($this->any())->method('logger')->will($this->returnValue(true));

        $oTestObject = $this->getMock(
            'fcPayOneOrder', array(
                'isPayOnePaymentType',
                '_checkOrderExist',
                'validateOrder',
                '_loadFromBasket',
                '_setUser',
                '_setPayment',
                '_fcpoCheckRefNr',
                '_executePayment',
                'save',
                '_fcGetCurrentVersion',
                '_updateOrderDate',
                '_setOrderStatus',
                '_updateWishlist',
                '_updateNoticeList',
                '_markVouchers',
                '_sendOrderByEmail',
                'setId',
                '_setFolder',
                '_fcpoProcessOrder',
                '_executeTsProtection',
                'getTsProductId',
                '_isRedirectAfterSave',
                '_fcpoEarlyValidation',
                '_fcpoHandleBasket',
                '_fcpoExecutePayment',
                '_fcpoSaveAfterRedirect',
                '_fcpoHandleTsProtection',
                '_fcpoSetOrderStatus',
                '_fcpoMarkVouchers',
                '_fcpoFinishOrder',
                '_fcpoAdjustAmazonPayUserDetails'
            )
        );
        $oTestObject->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_checkOrderExist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('validateOrder')->will($this->returnValue(1));
        $oTestObject->expects($this->any())->method('_loadFromBasket')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setPayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckRefNr')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executePayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('_updateOrderDate')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_updateWishlist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_updateNoticeList')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_markVouchers')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_sendOrderByEmail')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('setId')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setFolder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoProcessOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executeTsProtection')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getTsProductId')->will($this->returnValue('someTsId'));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoEarlyValidation')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoHandleBasket')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoExecutePayment')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoSaveAfterRedirect')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleTsProtection')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoSetOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoMarkVouchers')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoFinishOrder')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoAdjustAmazonPayUserDetails');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->finalizeOrder($oMockBasket, $oMockUser, false));
    }

    /**
     * Testing finalizeOrder for case order is payone and order to be recalculated and having no orderstate 
     * 
     * @param  void
     * @return void
     */
    public function test_finalizeOrder_IsPayone_RecalcOrderNoOrderState() 
    {
        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId', 'getTsProductId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('somePaymentId'));
        $oMockBasket->expects($this->any())->method('getTsProductId')->will($this->returnValue('someTsId'));

        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));

        $oMockUtils = $this->getMock('oxUtils', array('logger'));
        $oMockUtils->expects($this->any())->method('logger')->will($this->returnValue(true));

        $oTestObject = $this->getMock(
            'fcPayOneOrder', array(
            'isPayOnePaymentType',
            '_checkOrderExist',
            'validateOrder',
            '_loadFromBasket',
            '_setUser',
            '_setPayment',
            '_fcpoCheckRefNr',
            '_executePayment',
            'save',
            '_fcGetCurrentVersion',
            '_updateOrderDate',
            '_setOrderStatus',
            '_updateWishlist',
            '_updateNoticeList',
            '_markVouchers',
            '_sendOrderByEmail',
            'setId',
            '_setFolder',
            '_fcpoProcessOrder',
            '_executeTsProtection',
            'getTsProductId',
            '_isRedirectAfterSave',
            '_fcpoEarlyValidation',
            '_fcpoHandleBasket',
            '_fcpoExecutePayment',
            '_fcpoSaveAfterRedirect',
            '_fcpoHandleTsProtection',
            '_fcpoSetOrderStatus',
            '_fcpoMarkVouchers',
            '_fcpoFinishOrder',
                )
        );
        $oTestObject->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_checkOrderExist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('validateOrder')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_loadFromBasket')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setPayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckRefNr')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executePayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('_updateOrderDate')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_updateWishlist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_updateNoticeList')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_markVouchers')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_sendOrderByEmail')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('setId')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_setFolder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoProcessOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executeTsProtection')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getTsProductId')->will($this->returnValue('someTsId'));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoEarlyValidation')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoHandleBasket')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoExecutePayment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSaveAfterRedirect')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleTsProtection')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoMarkVouchers')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoFinishOrder')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(1, $oTestObject->finalizeOrder($oMockBasket, $oMockUser, false));
    }

    /**
     * Testing _fcpoProcessOrder for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoProcessOrder_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoCheckTxid', '_fcpoSaveOrderValues', '_fcpoCheckUserAgent'));
        $oTestObject->expects($this->any())->method('_fcpoCheckTxid')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSaveOrderValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckUserAgent')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(false));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockBasket = $this->getMock('oxBasket', array('getPaymentId'));
        $oMockBasket->expects($this->any())->method('getPaymentId')->will($this->returnValue('fcpocreditcard'));

        $blResponse = $this->invokeMethod($oTestObject, '_fcpoProcessOrder', array($oMockBasket, 'someTxid'));

        $this->assertEquals(null, $blResponse);
    }

    /**
     * Testing _fcpoExecutePayment in case save after redirect is active
     */
    public function test__fcpoExecutePayment_SaveAfterRedirectEarlyReturn() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoCheckRefNr', '_fcpoProcessOrder', '_executePayment'));
        $oTestObject->expects($this->any())->method('_fcpoCheckRefNr')->will($this->returnValue('someValue'));
        $oTestObject->expects($this->any())->method('_fcpoProcessOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executePayment')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockBasket = oxNew('oxBasket');
        $oMockUserPayment = oxNew('oxUserPayment');

        $this->assertEquals('someValue', $oTestObject->_fcpoExecutePayment(true, $oMockBasket, $oMockUserPayment, true));
    }

    /**
     * Testing _fcpoExecutePayment in case save after redirect is active
     */
    public function test__fcpoExecutePayment_SaveAfterRedirectLaterReturn() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoCheckRefNr', '_fcpoProcessOrder', '_executePayment'));
        $oTestObject->expects($this->any())->method('_fcpoCheckRefNr')->will($this->returnValue(''));
        $oTestObject->expects($this->any())->method('_fcpoProcessOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executePayment')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockBasket = oxNew('oxBasket');
        $oMockUserPayment = oxNew('oxUserPayment');

        $this->assertEquals(null, $oTestObject->_fcpoExecutePayment(true, $oMockBasket, $oMockUserPayment, true));
    }

    /**
     * Testing _fcpoExecutePayment in case save after redirect is inactive
     */
    public function test__fcpoExecutePayment_NoSaveAfterRedirectEarlyReturn() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoCheckRefNr', '_fcpoProcessOrder', '_executePayment'));
        $oTestObject->expects($this->any())->method('_fcpoCheckRefNr')->will($this->returnValue(''));
        $oTestObject->expects($this->any())->method('_fcpoProcessOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executePayment')->will($this->returnValue(false));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockBasket = oxNew('oxBasket');
        $oMockUserPayment = oxNew('oxUserPayment');

        $this->assertEquals(false, $oTestObject->_fcpoExecutePayment(false, $oMockBasket, $oMockUserPayment, false));
    }

    /**
     * Testing _fcpoExecutePayment in case save after redirect is inactive
     */
    public function test__fcpoExecutePayment_NoSaveAfterRedirectLateReturn() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoCheckRefNr', '_fcpoProcessOrder', '_executePayment'));
        $oTestObject->expects($this->any())->method('_fcpoCheckRefNr')->will($this->returnValue(''));
        $oTestObject->expects($this->any())->method('_fcpoProcessOrder')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_executePayment')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockBasket = oxNew('oxBasket');
        $oMockUserPayment = oxNew('oxUserPayment');

        $this->assertEquals(null, $oTestObject->_fcpoExecutePayment(false, $oMockBasket, $oMockUserPayment, true));
    }

    /**
     * Testing _fcpoHandleBasket for case that save after redirect is active
     */
    public function test__fcpoHandleBasket_SaveAfterRedirect() 
    {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoCheckRefNr', '_fcpoProcessOrder', '_executePayment'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockBasket = oxNew('oxBasket');

        $this->assertEquals(null, $oTestObject->_fcpoHandleBasket(true, $oMockBasket));
    }

    /**
     * Testing _fcpoHandleBasket for case that save after redirect is inactive
     */
    public function test__fcpoHandleBasket_NoSaveAfterRedirect() 
    {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoCheckRefNr', '_fcpoProcessOrder', '_executePayment'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockBasket = oxNew('oxBasket');

        $this->assertEquals(null, $oTestObject->_fcpoHandleBasket(false, $oMockBasket));
    }

    /**
     * Testing _fcpoEarlyValidation for case that save after redirect is inactive
     */
    public function test__fcpoEarlyValidation_NoSaveAfterRedirect() 
    {
        $oMockUtils = $this->getMock('oxUtils', array('logger'));
        $oMockUtils->expects($this->any())->method('logger')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_checkOrderExist', 'setId', 'validateOrder'));
        $oTestObject->expects($this->any())->method('_checkOrderExist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('setId')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('validateOrder')->will($this->returnValue(1));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockBasket = oxNew('oxBasket');
        $oMockUser = oxNew('oxUser');

        $this->assertEquals(true, $oTestObject->_fcpoEarlyValidation(false, $oMockBasket, $oMockUser, false));
    }

    /**
     * Testing _fcpoEarlyValidation for case that save after redirect is inactive
     */
    public function test__fcpoEarlyValidation_SaveAfterRedirect() 
    {
        $oMockUtils = $this->getMock('oxUtils', array('logger'));
        $oMockUtils->expects($this->any())->method('logger')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_checkOrderExist', 'setId', 'validateOrder'));
        $oTestObject->expects($this->any())->method('_checkOrderExist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('setId')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('validateOrder')->will($this->returnValue(1));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockBasket = oxNew('oxBasket');
        $oMockUser = oxNew('oxUser');

        $this->assertEquals(true, $oTestObject->_fcpoEarlyValidation(true, $oMockBasket, $oMockUser, false));
    }

    /**
     * Testing _fcpoEarlyValidation for case that save after redirect is inactive
     */
    public function test__fcpoEarlyValidation_Null() 
    {
        $oMockUtils = $this->getMock('oxUtils', array('logger'));
        $oMockUtils->expects($this->any())->method('logger')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_checkOrderExist', 'setId', 'validateOrder'));
        $oTestObject->expects($this->any())->method('_checkOrderExist')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('setId')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('validateOrder')->will($this->returnValue(1));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockBasket = oxNew('oxBasket');
        $oMockUser = oxNew('oxUser');

        $this->assertEquals(null, $oTestObject->_fcpoEarlyValidation(true, $oMockBasket, $oMockUser, true));
    }

    /**
     * Testing _fcpoFinishOrder for case sending via mail
     */
    public function test__fcpoFinishOrder_SendMail() 
    {
        $oMockBasket = oxNew('oxBasket');
        $oMockUser = oxNew('oxUser');
        $oMockUserPayment = oxNew('oxUserPayment');

        $oTestObject = $this->getMock('fcPayOneOrder', array('_sendOrderByEmail'));
        $oTestObject->expects($this->any())->method('_sendOrderByEmail')->will($this->returnValue(1));

        $this->assertEquals(1, $oTestObject->_fcpoFinishOrder(false, $oMockUser, $oMockBasket, $oMockUserPayment));
    }

    /**
     * Testing _fcpoFinishOrder for case getting state ok
     */
    public function test__fcpoFinishOrder_StateOK() 
    {
        $oMockBasket = oxNew('oxBasket');
        $oMockUser = oxNew('oxUser');
        $oMockUserPayment = oxNew('oxUserPayment');

        $oTestObject = $this->getMock('fcPayOneOrder', array('_sendOrderByEmail'));
        $oTestObject->expects($this->any())->method('_sendOrderByEmail')->will($this->returnValue(1));

        $this->assertEquals(1, $oTestObject->_fcpoFinishOrder(true, $oMockUser, $oMockBasket, $oMockUserPayment));
    }

    /**
     * Testing _fcpoSaveAfterRedirect for coverage
     */
    public function test__fcpoSaveAfterRedirect_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals(null, $oTestObject->_fcpoSaveAfterRedirect(true));
    }

    /**
     * Testing _fcpoSetOrderStatus for state ok
     */
    public function test__fcpoSetOrderStatus_Ok() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_setOrderStatus', '_fcpoGetAppointedError'));
        $oTestObject->expects($this->any())->method('_setOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetAppointedError')->will($this->returnValue(false));

        $this->assertEquals(null, $oTestObject->_fcpoSetOrderStatus());
    }

    /**
     * Testing _fcpoSetOrderStatus for state error
     */
    public function test__fcpoSetOrderStatus_Error() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_setOrderStatus', '_fcpoGetAppointedError'));
        $oTestObject->expects($this->any())->method('_setOrderStatus')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetAppointedError')->will($this->returnValue(true));

        $this->assertEquals(null, $oTestObject->_fcpoSetOrderStatus());
    }

    public function test__fcpoMarkVouchers_Coverage() 
    {
        $oMockBasket = oxNew('oxBasket');
        $oMockUser = oxNew('oxUser');

        $oTestObject = $this->getMock('fcPayOneOrder', array('_markVouchers'));
        $oTestObject->expects($this->any())->method('_markVouchers')->will($this->returnValue(true));

        $this->assertEquals(null, $oTestObject->_fcpoMarkVouchers(false, $oMockUser, $oMockBasket));
    }

    /**
     * Testing fcpoGetMandateFilename for coverage
     */
    public function test_fcpoGetMandateFilename_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('getId'));
        $oTestObject->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someFile'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals('someFile', $oTestObject->fcpoGetMandateFilename());
    }

    /**
     * Testing fcpoGetStatus coverage
     */
    public function test_fcpoGetStatus_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('getId'));
        $oTestObject->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $oMockTransactionStatus = $this->getMock('fcPayOne', array('load'));
        $oMockTransactionStatus->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockTransactionStatus));

        $aMockResult = array(array('someValue'));
        $oMockDatabase = $this->getMock('oxDb', array('getAll'));
        $oMockDatabase->expects($this->any())->method('getAll')->will($this->returnValue($aMockResult));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $aResponse = $aExpect = $oTestObject->fcpoGetStatus();

        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _fcpoSaveOrderValues for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoSaveOrderValues_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('someParameter'));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoSaveOrderValues', array('someTxid', '1')));
    }

    /**
     * Testing _fcpoCheckTxid for case txid is in session
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoCheckTxid_TxidInSession() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__oxremark = new oxField('');

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(''));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockDatabase = $this->getMock('oxDb', array('getOne'));
        $oMockDatabase->expects($this->any())->method('getOne')->will($this->returnValue(false));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $blResponse = $this->invokeMethod($oTestObject, '_fcpoCheckTxid');

        $this->assertEquals(true, $blResponse);
    }

    /**
     * Testing _fcpoCheckTxid for case txid is not in session
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoCheckTxid_TxidNotInSession() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__oxremark = new oxField('');

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(''));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oMockDatabase = $this->getMock('oxDb', array('getOne'));
        $oMockDatabase->expects($this->any())->method('getOne')->will($this->returnValue(false));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $blResponse = $this->invokeMethod($oTestObject, '_fcpoCheckTxid');

        $this->assertEquals(true, $blResponse);
    }

    /**
     * Testing _fcpoCheckRefNr for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoCheckRefNr_Coverage() 
    {
        $oMockRequest = $this->getMock('fcporequest', array('getRefNr', 'sendRequestAuthorization'));
        $oMockRequest->expects($this->any())->method('getRefNr')->will($this->returnValue('someRefValue'));
        $oMockRequest->expects($this->any())->method('sendRequestAuthorization')->will($this->returnValue(array()));

        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcpoGetMode'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcpoGetMode')->will($this->returnValue('test'));
        $oMockPayment->oxpayments__fcpoauthmode = new oxField('someAuthMode');

        $oTestObject = oxNew('fcPayOneOrder');

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someTranslatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someSessionValue'));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('someRequestValue'));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockRequest, $oMockPayment));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someTranslatedString', $oTestObject->_fcpoCheckRefNr());
    }

    /**
     * Testing _insert for older versions
     * 
     * @param  void
     * @return void
     */
    public function test__insert_OldVersion() 
    {
        $oMockConfig = $this->getMock('oxConfig', array('getShopId', 'getVersion'));
        $oMockConfig->expects($this->any())->method('getShopId')->will($this->returnValue('oxbaseshop'));
        $oMockConfig->expects($this->any())->method('getVersion')->will($this->returnValue('4.5.0'));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcGetCurrentVersion', '_setNumber', '_getCounterIdent'));
        $oTestObject->expects($this->any())->method('_setNumber')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_getCounterIdent')->will($this->returnValue('someCounterIdent'));
        $oTestObject->oxorder__oxordernr = new oxField('');

        $oMockUtilsDate = $this->getMock('oxUtilsDate', array('getTime', 'formatDBDate'));
        $oMockUtilsDate->expects($this->any())->method('getTime')->will($this->returnValue(time()));
        $oMockUtilsDate->expects($this->any())->method('formatDBDate')->will($this->returnValue('someFormattedTime'));

        $oMockCounter = $this->getMock('oxCounter', array('update'));
        $oMockCounter->expects($this->any())->method('update')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsDate')->will($this->returnValue($oMockUtilsDate));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCounter));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $blResponse = $blExpect = $this->invokeMethod($oTestObject, '_insert');

        $this->assertEquals($blExpect, $blResponse);
    }

    /**
     * Testing _insert for newer versions with ordernr set
     * 
     * @param  void
     * @return void
     */
    public function test__insert_NewVersion_OrderNrSet() 
    {
        $oMockConfig = $this->getMock('oxConfig', array('getShopId'));
        $oMockConfig->expects($this->any())->method('getShopId')->will($this->returnValue('oxbaseshop'));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcGetCurrentVersion', '_setNumber', '_getCounterIdent'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('_setNumber')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_getCounterIdent')->will($this->returnValue('someCounterIdent'));
        $oTestObject->oxorder__oxordernr = new oxField('1234');

        $oMockUtilsDate = $this->getMock('oxUtilsDate', array('getTime', 'formatDBDate'));
        $oMockUtilsDate->expects($this->any())->method('getTime')->will($this->returnValue(time()));
        $oMockUtilsDate->expects($this->any())->method('formatDBDate')->will($this->returnValue('someFormattedTime'));

        $oMockCounter = $this->getMock('oxCounter', array('update'));
        $oMockCounter->expects($this->any())->method('update')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsDate')->will($this->returnValue($oMockUtilsDate));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCounter));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $blResponse = $blExpect = $this->invokeMethod($oTestObject, '_insert');

        $this->assertEquals($blExpect, $blResponse);
    }

    /**
     * Testing _insert for newer versions without ordernr set
     * 
     * @param  void
     * @return void
     */
    public function test__insert_NewVersion_OrderNrNotSet() 
    {
        $oMockConfig = $this->getMock('oxConfig', array('getShopId'));
        $oMockConfig->expects($this->any())->method('getShopId')->will($this->returnValue('oxbaseshop'));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcGetCurrentVersion', '_setNumber', '_getCounterIdent'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('_setNumber')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_getCounterIdent')->will($this->returnValue('someCounterIdent'));
        $oTestObject->oxorder__oxordernr = new oxField('');

        $oMockUtilsDate = $this->getMock('oxUtilsDate', array('getTime', 'formatDBDate'));
        $oMockUtilsDate->expects($this->any())->method('getTime')->will($this->returnValue(time()));
        $oMockUtilsDate->expects($this->any())->method('formatDBDate')->will($this->returnValue('someFormattedTime'));

        $oMockCounter = $this->getMock('oxCounter', array('update'));
        $oMockCounter->expects($this->any())->method('update')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsDate')->will($this->returnValue($oMockUtilsDate));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCounter));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $blResponse = $blExpect = $this->invokeMethod($oTestObject, '_insert');

        $this->assertEquals($blExpect, $blResponse);
    }

    /**
     * Testing _insert for newer versions with orderdate set
     * 
     * @param  void
     * @return void
     */
    public function test__insert_NewVersion_OrderDateSet() 
    {
        $oMockConfig = $this->getMock('oxConfig', array('getShopId'));
        $oMockConfig->expects($this->any())->method('getShopId')->will($this->returnValue('oxbaseshop'));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcGetCurrentVersion', '_setNumber', '_getCounterIdent'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('_setNumber')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_getCounterIdent')->will($this->returnValue('someCounterIdent'));
        $oTestObject->oxorder__oxordernr = new oxField('');
        $oTestObject->oxorder__oxorderdate = new oxField('2016-05-09');

        $oMockUtilsDate = $this->getMock('oxUtilsDate', array('getTime', 'formatDBDate'));
        $oMockUtilsDate->expects($this->any())->method('getTime')->will($this->returnValue(time()));
        $oMockUtilsDate->expects($this->any())->method('formatDBDate')->will($this->returnValue('someFormattedTime'));

        $oMockCounter = $this->getMock('oxCounter', array('update'));
        $oMockCounter->expects($this->any())->method('update')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsDate')->will($this->returnValue($oMockUtilsDate));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCounter));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $blResponse = $blExpect = $this->invokeMethod($oTestObject, '_insert');

        $this->assertEquals($blExpect, $blResponse);
    }

    /**
     * Testing save with presave
     * 
     * @param  void
     * @return void
     */
    public function test_save_Presave() 
    {
        $oMockShop = $this->getMock('oxShop', array('getId'));
        $oMockShop->expects($this->any())->method('getId')->will($this->returnValue('oxbaseshop'));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam', 'getActiveShop', 'getShopId'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));
        $oMockConfig->expects($this->any())->method('getActiveShop')->will($this->returnValue($oMockShop));
        $oMockConfig->expects($this->any())->method('getShopId')->will($this->returnValue('oxbaseshop'));

        $oMockOrderArticle = $this->getMock('oxOrderArticle', array('save'));
        $oMockOrderArticle->expects($this->any())->method('save')->will($this->returnValue(true));

        $aOrderArticles = array();
        $aOrderArticles[] = $oMockOrderArticle;

        $oMockUtilsDate = $this->getMock('oxUtilsDate', array('getTime', 'formatDBDate'));
        $oMockUtilsDate->expects($this->any())->method('getTime')->will($this->returnValue(time()));
        $oMockUtilsDate->expects($this->any())->method('formatDBDate')->will($this->returnValue('someFormattedTime'));

        $oTestObject = $this->getMock('fcPayOneOrder', array('getOrderArticles', 'isPayOnePaymentType', '_isRedirectAfterSave', 'getShopId'));
        $oTestObject->expects($this->any())->method('getOrderArticles')->will($this->returnValue($aOrderArticles));
        $oTestObject->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getShopId')->will($this->returnValue('oxbaseshop'));
        $oTestObject->oxorder__oxshopid = new oxField(false);

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsDate')->will($this->returnValue($oMockUtilsDate));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sResponse = $sExpect = $oTestObject->save();

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing save without presave
     * 
     * @param  void
     * @return void
     */
    public function test_save_NoPresave() 
    {
        $oMockShop = $this->getMock('oxShop', array('getId'));
        $oMockShop->expects($this->any())->method('getId')->will($this->returnValue('oxbaseshop'));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam', 'getActiveShop', 'getShopId'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oMockConfig->expects($this->any())->method('getActiveShop')->will($this->returnValue($oMockShop));
        $oMockConfig->expects($this->any())->method('getShopId')->will($this->returnValue('oxbaseshop'));

        $oMockOrderArticle = $this->getMock('oxOrderArticle', array('save'));
        $oMockOrderArticle->expects($this->any())->method('save')->will($this->returnValue(true));

        $oMockUtilsDate = $this->getMock('oxUtilsDate', array('getTime', 'formatDBDate'));
        $oMockUtilsDate->expects($this->any())->method('getTime')->will($this->returnValue(time()));
        $oMockUtilsDate->expects($this->any())->method('formatDBDate')->will($this->returnValue('someFormattedTime'));

        $aOrderArticles = array();
        $aOrderArticles[] = $oMockOrderArticle;

        $oTestObject = $this->getMock('fcPayOneOrder', array('getOrderArticles', 'isPayOnePaymentType', '_isRedirectAfterSave', 'getShopId'));
        $oTestObject->expects($this->any())->method('getOrderArticles')->will($this->returnValue($aOrderArticles));
        $oTestObject->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getShopId')->will($this->returnValue('oxbaseshop'));
        $oTestObject->oxorder__oxshopid = new oxField(false);

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsDate')->will($this->returnValue($oMockUtilsDate));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sResponse = $sExpect = $oTestObject->save();

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing allowCapture with authorization
     * 
     * @param  void
     * @return void
     */
    public function test_allowCapture_Authorization() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__fcpoauthmode = new oxField('authorization');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue(0));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals(false, $oTestObject->allowCapture());
    }

    /**
     * Testing allowCapture without authorization
     * 
     * @param  void
     * @return void
     */
    public function test_allowCapture_Preauthorization() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__fcpoauthmode = new oxField('preauthorization');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue(0));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals(false, $oTestObject->allowCapture());
    }

    /**
     * Testing allowDebit set to authorization
     * 
     * @param  void
     * @return void
     */
    public function test_allowDebit_Authorization() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__fcpoauthmode = new oxField('authorization');
        $oTestObject->oxorder__fcpotxid = new oxField('123456789');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue(0));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals(true, $oTestObject->allowDebit());
    }

    /**
     * Testing allowDebit set to authorization
     * 
     * @param  void
     * @return void
     */
    public function test_allowDebit_Preauthorization() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__fcpoauthmode = new oxField('preauthorization');
        $oTestObject->oxorder__fcpotxid = new oxField('123456789');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue(0));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals(false, $oTestObject->allowDebit());
    }

    /**
     * Testing allowAccountSettlement for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_allowAccountSettlement_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $this->assertEquals(false, $oTestObject->allowAccountSettlement());
    }

    /**
     * Testing debitNeedsBankData for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_debitNeedsBankData_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $this->assertEquals(false, $oTestObject->debitNeedsBankData());
    }

    /**
     * Testing isDetailedProductInfoNeeded for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_isDetailedProductInfoNeeded_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__oxpaymenttype = new oxField('fcpoklarna');

        $this->assertEquals(true, $oTestObject->isDetailedProductInfoNeeded());
    }

    /**
     * Testing getSequenceNumber for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getSequenceNumber_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__fcpotxid = new oxField('someTxid');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue(1));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals(2, $oTestObject->getSequenceNumber());
    }

    /**
     * Testing getLastStatus for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getLastStatus_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');

        $oMockTrans = $this->getMock('fcpotransactionstatus', array('load'));
        $oMockTrans->expects($this->any())->method('load')->will($this->returnValue(true));

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue(1));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockTrans));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals($oMockTrans, $oTestObject->getLastStatus());
    }

    /**
     * Testing getResponse for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getResponse_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->invokeSetAttribute($oTestObject, '_aResponse', null);

        $aResponse = array('someResponse');

        $oMockRequestLog = $this->getMock('fcporequestlog', array('load', 'getResponseArray'));
        $oMockRequestLog->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockRequestLog->expects($this->any())->method('getResponseArray')->will($this->returnValue($aResponse));

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue(1));
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequestLog));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals($aResponse, $oTestObject->getResponse());
    }

    /**
     * Testing getResponseParameter for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getResponseParameter_Coverage() 
    {
        $aMockResponse = array('someIndex' => 'someParameter');
        $oTestObject = $this->getMock('fcPayOneOrder', array('getResponse'));
        $oTestObject->expects($this->any())->method('getResponse')->will($this->returnValue($aMockResponse));

        $this->assertEquals('someParameter', $oTestObject->getResponseParameter('someIndex'));
    }

    /**
     * Testing getFcpoBankaccountholder for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getFcpoBankaccountholder_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('getResponseParameter'));
        $oTestObject->expects($this->any())->method('getResponseParameter')->will($this->returnValue('someParameter'));

        $this->assertEquals('someParameter', $oTestObject->getFcpoBankaccountholder());
    }

    /**
     * Testing getFcpoBankname for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getFcpoBankname_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('getResponseParameter'));
        $oTestObject->expects($this->any())->method('getResponseParameter')->will($this->returnValue('someParameter'));

        $this->assertEquals('someParameter', $oTestObject->getFcpoBankname());
    }

    /**
     * Testing getFcpoBankcode for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getFcpoBankcode_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('getResponseParameter'));
        $oTestObject->expects($this->any())->method('getResponseParameter')->will($this->returnValue('someParameter'));

        $this->assertEquals('someParameter', $oTestObject->getFcpoBankcode());
    }

    /**
     * Testing getFcpoBanknumber for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getFcpoBanknumber_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('getResponseParameter'));
        $oTestObject->expects($this->any())->method('getResponseParameter')->will($this->returnValue('someParameter'));

        $this->assertEquals('someParameter', $oTestObject->getFcpoBanknumber());
    }

    /**
     * Testing getFcpoBiccode for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getFcpoBiccode_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('getResponseParameter'));
        $oTestObject->expects($this->any())->method('getResponseParameter')->will($this->returnValue('someParameter'));

        $this->assertEquals('someParameter', $oTestObject->getFcpoBiccode());
    }

    /**
     * Testing getFcpoIbannumber for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getFcpoIbannumber_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('getResponseParameter'));
        $oTestObject->expects($this->any())->method('getResponseParameter')->will($this->returnValue('someParameter'));

        $this->assertEquals('someParameter', $oTestObject->getFcpoIbannumber());
    }

    /**
     * Testing getFcpoCapturableAmount for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_getFcpoCapturableAmount_Coverage() 
    {
        $oMockTransaction = oxNew('fcpotransactionstatus');
        $oMockTransaction->fcpotransactionstatus__fcpo_receivable = new oxField(50);
        $oTestObject = $this->getMock('fcPayOneOrder', array('getLastStatus'));
        $oTestObject->oxorder__oxtotalordersum = new oxField(100);

        $this->assertEquals(100, $oTestObject->getFcpoCapturableAmount());
    }

    /**
     * Testing validateStock on older shop version
     * 
     * @param  void
     * @return void
     */
    public function test_validateStock_OldShopVersion() 
    {
        $this->wrapExpectException('oxOutOfStockException');
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oMockProduct = $this->getMock('oxArticle', array('getId', 'checkForStock'));
        $oMockProduct->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockProduct->expects($this->any())->method('checkForStock')->will($this->returnValue(false));
        $oMockProduct->oxarticles__oxartnum = new oxField('someArtNum');

        $oMockBasketItem = $this->getMock('oxBasketItem', array('getArticle'));
        $oMockBasketItem->expects($this->any())->method('getArticle')->will($this->returnValue($oMockProduct));
        $aContents[] = $oMockBasketItem;
        $oMockBasket = $this->getMock('oxBasket', array('getContents', 'removeItem', 'getArtStockInBasket'));
        $oMockBasket->expects($this->any())->method('getContents')->will($this->returnValue($aContents));
        $oMockBasket->expects($this->any())->method('removeItem')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getArtStockInBasket')->will($this->returnValue(2));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_isRedirectAfterSave', '_fcGetCurrentVersion', 'fcGetArtStockInBasket'));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4200));
        $oTestObject->expects($this->any())->method('fcGetArtStockInBasket')->will($this->returnValue(2));

        $this->assertEquals(null, $oTestObject->validateStock($oMockBasket));
    }

    /**
     * Testing validateStock on newer shop version
     * 
     * @param  void
     * @return void
     */
    public function test_validateStock_NewerShopVersion() 
    {
        $this->wrapExpectException('oxOutOfStockException');
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oMockProduct = $this->getMock('oxArticle', array('getId', 'checkForStock'));
        $oMockProduct->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockProduct->expects($this->any())->method('checkForStock')->will($this->returnValue(false));
        $oMockProduct->oxarticles__oxartnum = new oxField('someArtNum');

        $oMockBasketItem = $this->getMock('oxBasketItem', array('getArticle'));
        $oMockBasketItem->expects($this->any())->method('getArticle')->will($this->returnValue($oMockProduct));
        $aContents[] = $oMockBasketItem;
        $oMockBasket = $this->getMock('oxBasket', array('getContents', 'removeItem', 'getArtStockInBasket'));
        $oMockBasket->expects($this->any())->method('getContents')->will($this->returnValue($aContents));
        $oMockBasket->expects($this->any())->method('removeItem')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getArtStockInBasket')->will($this->returnValue(2));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_isRedirectAfterSave', '_fcGetCurrentVersion', 'fcGetArtStockInBasket'));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('fcGetArtStockInBasket')->will($this->returnValue(2));

        $this->assertEquals(null, $oTestObject->validateStock($oMockBasket));
    }

    /**
     * Testing validateStock on oxNoArticleException
     * 
     * @param  void
     * @return void
     */
    public function test_validateStock_ExceptionNoArticle() 
    {
        $this->wrapExpectException('oxNoArticleException');
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oMockProduct = $this->getMock('oxArticle', array('getId', 'checkForStock'));
        $oMockProduct->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockProduct->expects($this->any())->method('checkForStock')->will($this->returnValue(false));
        $oMockProduct->oxarticles__oxartnum = new oxField('someArtNum');

        $oMockException = new oxNoArticleException;

        $oMockBasketItem = $this->getMock('oxBasketItem', array('getArticle'));
        $oMockBasketItem->expects($this->any())->method('getArticle')->will($this->throwException($oMockException));
        $aContents[] = $oMockBasketItem;
        $oMockBasket = $this->getMock('oxBasket', array('getContents', 'removeItem', 'getArtStockInBasket'));
        $oMockBasket->expects($this->any())->method('getContents')->will($this->returnValue($aContents));
        $oMockBasket->expects($this->any())->method('removeItem')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getArtStockInBasket')->will($this->returnValue(2));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_isRedirectAfterSave', '_fcGetCurrentVersion', 'fcGetArtStockInBasket'));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('fcGetArtStockInBasket')->will($this->returnValue(2));

        $this->assertEquals($oMockException, $oTestObject->validateStock($oMockBasket));
    }

    /**
     * Testing validateStock on oxArticleInputException
     * 
     * @param  void
     * @return void
     */
    public function test_validateStock_ExceptionInput() 
    {
        $this->wrapExpectException('oxArticleInputException');
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oMockProduct = $this->getMock('oxArticle', array('getId', 'checkForStock'));
        $oMockProduct->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockProduct->expects($this->any())->method('checkForStock')->will($this->returnValue(false));
        $oMockProduct->oxarticles__oxartnum = new oxField('someArtNum');

        $oMockException = new oxArticleInputException;

        $oMockBasketItem = $this->getMock('oxBasketItem', array('getArticle'));
        $oMockBasketItem->expects($this->any())->method('getArticle')->will($this->throwException($oMockException));
        $aContents[] = $oMockBasketItem;
        $oMockBasket = $this->getMock('oxBasket', array('getContents', 'removeItem', 'getArtStockInBasket'));
        $oMockBasket->expects($this->any())->method('getContents')->will($this->returnValue($aContents));
        $oMockBasket->expects($this->any())->method('removeItem')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getArtStockInBasket')->will($this->returnValue(2));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_isRedirectAfterSave', '_fcGetCurrentVersion', 'fcGetArtStockInBasket'));
        $oTestObject->expects($this->any())->method('_isRedirectAfterSave')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('fcGetArtStockInBasket')->will($this->returnValue(2));

        $this->assertEquals($oMockException, $oTestObject->validateStock($oMockBasket));
    }

    /**
     * Testing fcGetArtStockInBasket for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcGetArtStockInBasket_Coverage() 
    {
        $oMockProduct = $this->getMock('oxArticle', array('getId'));
        $oMockProduct->expects($this->any())->method('getId')->will($this->returnValue('someId'));


        $oMockOrderArticle = $this->getMock('oxOrderArticle', array('getArticle', 'getAmount'));
        $oMockOrderArticle->expects($this->any())->method('getArticle')->will($this->returnValue($oMockProduct));
        $oMockOrderArticle->expects($this->any())->method('getAmount')->will($this->returnValue(2));
        $aContents[] = $oMockOrderArticle;

        $oMockBasket = $this->getMock('oxBasket', array('getContents'));
        $oMockBasket->expects($this->any())->method('getContents')->will($this->returnValue($aContents));

        $oTestObject = oxNew('fcPayOneOrder');

        $this->assertEquals(2, $oTestObject->fcGetArtStockInBasket($oMockBasket, 'someId'));
    }

    /**
     * Testing fcIsPayPalOrder for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcIsPayPalOrder_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oTestObject->oxorder__oxpaymenttype = new oxField('fcpopaypal_express');

        $this->assertEquals(true, $oTestObject->fcIsPayPalOrder());
    }

    /**
     * Testing fcHandleAuthorization for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcHandleAuthorization_Coverage() 
    {
        $aMockResponse = array();
        $oMockRequest = $this->getMock('fcporequest', array('getRefNr', 'sendRequestAuthorization'));
        $oMockRequest->expects($this->any())->method('getRefNr')->will($this->returnValue('someRefValue'));
        $oMockRequest->expects($this->any())->method('sendRequestAuthorization')->will($this->returnValue($aMockResponse));

        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcpoGetMode'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcpoGetMode')->will($this->returnValue('test'));
        $oMockPayment->oxpayments__fcpoauthmode = new oxField('someAuthMode');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoHandleAuthorizationResponse', 'getOrderUser', '_fcpoGetNextOrderNr'));
        $oTestObject->expects($this->any())->method('_fcpoHandleAuthorizationResponse')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getOrderUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetNextOrderNr')->will($this->returnValue('someOrderId'));
        $oTestObject->oxorder__oxpaymenttype = new oxField('somePaymentType');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockRequest, $oMockPayment));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(array()));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->fcHandleAuthorization());
    }

    /**
     * Testing _fcpoGetNextOrderNr in case of older shop version
     */
    public function test__fcpoGetNextOrderNr_OldShopVersion() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_getCounterIdent'));
        $oTestObject->expects($this->any())->method('_getCounterIdent')->will($this->returnValue('someCounterIdent'));

        $oMockDb = $this->getMock('oxDb', array('GetOne'));
        $oMockDb->expects($this->any())->method('GetOne')->will($this->returnValue('someOrderNr'));

        $oMockCounter = $this->getMock('oxCounter', array('getNext'));
        $oMockCounter->expects($this->any())->method('getNext')->will($this->returnValue('someOrderNr'));

        $oMockConfig = $this->getMock('oxConfig', array('getVersion'));
        $oMockConfig->expects($this->any())->method('getVersion')->will($this->returnValue('4.5.9'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCounter));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $this->assertEquals('someOrderNr', $oTestObject->_fcpoGetNextOrderNr());
    }

    /**
     * Testing _fcpoGetNextOrderNr in case of newer shop version
     */
    public function test__fcpoGetNextOrderNr_NewerShopVersion() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_getCounterIdent'));
        $oTestObject->expects($this->any())->method('_getCounterIdent')->will($this->returnValue('someCounterIdent'));

        $oMockDb = $this->getMock('oxDb', array('GetOne'));
        $oMockDb->expects($this->any())->method('GetOne')->will($this->returnValue('someOrderNr'));

        $oMockCounter = $this->getMock('oxCounter', array('getNext'));
        $oMockCounter->expects($this->any())->method('getNext')->will($this->returnValue('someOrderNr'));

        $oMockConfig = $this->getMock('oxConfig', array('getVersion'));
        $oMockConfig->expects($this->any())->method('getVersion')->will($this->returnValue('4.7.0'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCounter));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $this->assertEquals('someOrderNr', $oTestObject->_fcpoGetNextOrderNr());
    }

    /**
     * Testing _fcpoGetOrderNotChecked for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoGetOrderNotChecked_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(500));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(0, $oTestObject->_fcpoGetOrderNotChecked());
    }

    /**
     * Testing _fcpoHandleAuthorizationResponse in case of error response
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandleAuthorizationResponse_Error() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoHandleAutrhorizationError', '_fcpoHandleAutrhorizationApproved', '_fcpoHandleAutrhorizationRedirect'));
        $oTestObject->expects($this->any())->method('_fcpoHandleAutrhorizationError')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleAutrhorizationApproved')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleAutrhorizationRedirect')->will($this->returnValue(true));

        $aMockResponse['status'] = 'ERROR';
        $oMockPaymentGateway = oxNew('oxPaymentGateway');
        $sMockRefNr = $sMockMode = $sMockAuthorizationType = 'someValue';
        $blMockReturnRedirectUrl = false;

        $this->assertEquals(false, $oTestObject->_fcpoHandleAuthorizationResponse($aMockResponse, $oMockPaymentGateway, $sMockRefNr, $sMockMode, $sMockAuthorizationType, $blMockReturnRedirectUrl));
    }

    /**
     * Testing _fcpoHandleAuthorizationResponse in case of approved response
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandleAuthorizationResponse_Approved() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoHandleAutrhorizationError', '_fcpoHandleAutrhorizationApproved', '_fcpoHandleAutrhorizationRedirect'));
        $oTestObject->expects($this->any())->method('_fcpoHandleAutrhorizationError')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleAutrhorizationApproved')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleAutrhorizationRedirect')->will($this->returnValue(true));

        $aMockResponse['status'] = 'APPROVED';
        $oMockPaymentGateway = oxNew('oxPaymentGateway');
        $sMockRefNr = $sMockMode = $sMockAuthorizationType = 'someValue';
        $blMockReturnRedirectUrl = false;

        $this->assertEquals(true, $oTestObject->_fcpoHandleAuthorizationResponse($aMockResponse, $oMockPaymentGateway, $sMockRefNr, $sMockMode, $sMockAuthorizationType, $blMockReturnRedirectUrl));
    }

    /**
     * Testing _fcpoHandleAuthorizationRedirect for case that redirect url is in response and shall be returned
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandleAuthorizationRedirect_ReturnRedirect() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoHandleAutrhorizationError', '_fcpoHandleAutrhorizationApproved', '_fcpoHandleAutrhorizationRedirect'));
        $oTestObject->expects($this->any())->method('_fcpoHandleAutrhorizationError')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleAutrhorizationApproved')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoHandleAutrhorizationRedirect')->will($this->returnValue(true));

        $aMockResponse['status'] = 'REDIRECT';
        $oMockPaymentGateway = oxNew('oxPaymentGateway');
        $sMockRefNr = $sMockMode = $sMockAuthorizationType = 'someValue';
        $blMockReturnRedirectUrl = true;

        $this->assertEquals(null, $oTestObject->_fcpoHandleAuthorizationResponse($aMockResponse, $oMockPaymentGateway, $sMockRefNr, $sMockMode, $sMockAuthorizationType, $blMockReturnRedirectUrl));
    }

    /**
     * Testing _fcpoHandleAuthorizationRedirect for case that redirect url is in response and shall be redirected without using the iframe
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandleAuthorizationRedirect_NoReturnNoIframe() 
    {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam', 'getCurrentShopUrl'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oMockConfig->expects($this->any())->method('getCurrentShopUrl')->will($this->returnValue('someShopUrl'));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOneOrder', array('save', 'isPayOneIframePayment'));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPayOneIframePayment')->will($this->returnValue(false));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockRedirectUrl = 'http://www.someRedirect.org';
        $aMockResponse = array('txid' => 'someTxid', 'redirecturl' => $sMockRedirectUrl);
        $sMockRefNr = $sMockMode = $sMockAuthorizationType = 'someValue';
        $blMockReturnRedirectUrl = false;

        $this->assertEquals(null, $oTestObject->_fcpoHandleAuthorizationRedirect($aMockResponse, $sMockRefNr, $sMockAuthorizationType, $sMockMode, $blMockReturnRedirectUrl));
    }

    /**
     * Testing _fcpoHandleAuthorizationRedirect for case that redirect url is in response and shall be redirected using the iframe
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandleAuthorizationRedirect_NoReturnIframe() 
    {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam', 'getCurrentShopUrl'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oMockConfig->expects($this->any())->method('getCurrentShopUrl')->will($this->returnValue('someShopUrl'));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOneOrder', array('save', 'isPayOneIframePayment'));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPayOneIframePayment')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockRedirectUrl = 'http://www.someRedirect.org';
        $aMockResponse = array('txid' => 'someTxid', 'redirecturl' => $sMockRedirectUrl);
        $sMockRefNr = $sMockMode = $sMockAuthorizationType = 'someValue';
        $blMockReturnRedirectUrl = false;

        $this->assertEquals(null, $oTestObject->_fcpoHandleAuthorizationRedirect($aMockResponse, $sMockRefNr, $sMockAuthorizationType, $sMockMode, $blMockReturnRedirectUrl));
    }

    /**
     * Testing _fcpoCheckReduceBefore for coverage
     */
    public function test__fcpoCheckReduceBefore_Coverage() 
    {
        $oMockOrderArticle = $this->getMock('oxOrderArticle', array('updateArticleStock'));
        $oMockOrderArticle->expects($this->any())->method('updateArticleStock')->will($this->returnValue(null));
        $aMockOrderArticles = array($oMockOrderArticle);

        $oTestObject = $this->getMock('fcPayOneOrder', array('getOrderArticles'));
        $oTestObject->expects($this->any())->method('getOrderArticles')->will($this->returnValue($aMockOrderArticles));
        $oTestObject->oxorder__oxpaymenttype = new oxField('fcpopaypal');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->assertEquals(null, $oTestObject->_fcpoCheckReduceBefore());
    }

    /**
     * Testing _fcpoHandleAuthorizationApproved for Barzahlen
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandleAuthorizationApproved_Barzahlen() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoGetOrderNotChecked'));
        $oTestObject->expects($this->any())->method('_fcpoGetOrderNotChecked')->will($this->returnValue(1));
        $oTestObject->oxorder__oxpaymenttype = new oxField('fcpobarzahlen');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockDb = $this->getMock('oxDb', array('Execute'));
        $oMockDb->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $aMockResponse = array('add_paydata[instruction_notes]' => 'someValue', 'txid' => 'someTxid', 'add_paydata[clearing_reference]' => 'someReference');
        $sMockRefNr = $sMockMode = $sMockAuthorizationType = 'someValue';

        $this->assertEquals(null, $oTestObject->_fcpoHandleAuthorizationApproved($aMockResponse, $sMockRefNr, $sMockAuthorizationType, $sMockMode));
    }

    /**
     * Testing _fcpoHandleAuthorizationApproved for payolution payments
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandleAuthorizationApproved_Payolution() 
    {
        $oTestObject = $this->getMock('fcPayOneOrder', array('_fcpoGetOrderNotChecked'));
        $oTestObject->expects($this->any())->method('_fcpoGetOrderNotChecked')->will($this->returnValue(1));
        $oTestObject->oxorder__oxpaymenttype = new oxField('fcpopo_bill');

        $oMockConfig = $this->getMock('OxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockDb = $this->getMock('oxDb', array('Execute'));
        $oMockDb->expects($this->any())->method('Execute')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someWorkerId'));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDb);

        $aMockResponse = array('add_paydata[instruction_notes]' => 'someValue', 'txid' => 'someTxid', 'add_paydata[clearing_reference]' => 'someReference');
        $sMockRefNr = $sMockMode = $sMockAuthorizationType = 'someValue';

        $this->assertEquals(null, $oTestObject->_fcpoHandleAuthorizationApproved($aMockResponse, $sMockRefNr, $sMockAuthorizationType, $sMockMode));
    }

    /**
     * Testing _fcpoHandleAuthorizationError for standard payment
     * 
     * @param void
     * @return void
     */
    public function test__fcpoHandleAuthorizationError_Standard() {
        $oTestObject = $this->getMock('fcPayOneOrder', array(
            'fcpoGetAmazonErrorMessage',
            '_fcpoGetAmazonSuccessCode'
        ));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoGetAmazonErrorMessage')
            ->will($this->returnValue('someAmazonErrorMessage'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetAmazonSuccessCode')
            ->will($this->returnValue('someAmazonSuccessCode'));
        $oTestObject->oxorder__oxpaymenttype = new oxField('somePaymentId');

        $oMockPayGate = $this->getMock('oxPaymentGate', array(
            'fcSetLastErrorNr',
            'fcSetLastError'
        ));
        $oMockPayGate
            ->expects($this->any())
            ->method('fcSetLastErrorNr')
            ->will($this->returnValue(true));
        $oMockPayGate
            ->expects($this->any())
            ->method('fcSetLastError')
            ->will($this->returnValue(true));

        $aMockResponse = array(
            'errorcode' => 'someErrorCode',
            'customermessage' => 'someMessage'
        );

        $this->assertEquals(false, $oTestObject->_fcpoHandleAuthorizationError($aMockResponse, $oMockPayGate));
    }

    /**
     * Testing _fcpoHandleAuthorizationError for amazon payment
     *
     * @param void
     * @return void
     */
    public function test__fcpoHandleAuthorizationError_Amazon() {
        $oTestObject = $this->getMock('fcPayOneOrder', array('fcpoGetAmazonErrorMessage','_fcpoGetAmazonSuccessCode'));
        $oTestObject->expects($this->any())->method('fcpoGetAmazonErrorMessage')->will($this->returnValue('someAmazonErrorMessage'));
        $oTestObject->expects($this->any())->method('_fcpoGetAmazonSuccessCode')->will($this->returnValue('someAmazonSuccessCode'));
        $oTestObject->oxorder__oxpaymenttype = new oxField('fcpoamazonpay');

        $oMockPayGate = $this->getMock('oxPaymentGate', array('fcSetLastErrorNr', 'fcSetLastError'));
        $oMockPayGate->expects($this->any())->method('fcSetLastErrorNr')->will($this->returnValue(true));
        $oMockPayGate->expects($this->any())->method('fcSetLastError')->will($this->returnValue(true));

        $aMockResponse = array('errorcode' => 'someErrorCode', 'customermessage' => 'someMessage');

        $this->assertNull($oTestObject->_fcpoHandleAuthorizationError($aMockResponse, $oMockPayGate));
    }

    /**
     * Testing _fcpoSaveWorkorderId for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoSaveWorkorderId_Coverage() {
        $oTestObject = oxNew('fcPayOneOrder');
        $sMockPaymentId = 'fcpopo_bill';
        $aMockResponse = array(
            'add_paydata[workorderid]' => 'someWorkorderId',
        );

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoSaveWorkorderId($sMockPaymentId, $aMockResponse));
    }

    /**
     * Testing _fcpoSaveClearingReference for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoSaveClearingReference_Coverage() {
        $oTestObject = oxNew('fcPayOneOrder');
        $sMockPaymentId = 'fcpopo_bill';
        $aMockResponse = array(
            'add_paydata[clearing_reference]' => 'someClearingReference',
        );


        $this->assertEquals(null, $oTestObject->_fcpoSaveClearingReference($sMockPaymentId, $aMockResponse));
    }

    /**
     * Testing _fcpoSaveProfileIdent for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoSaveProfileIdent_Coverage() {
        $oTestObject = oxNew('fcPayOneOrder');
        $sMockPaymentId = 'fcporp_bill';
        $aMockResponse = array(
            'userid' => 'someUserId',
        );


        $this->assertEquals(null, $oTestObject->_fcpoSaveProfileIdent($sMockPaymentId, $aMockResponse));
    }

    /**
     * Testing fcpoGetAmazonErrorMessage for coverage
     *
     * @param void
     * @return void
     */
    public function test_fcpoGetAmazonErrorMessage_Coverage() {
        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('someMessage'));

        $oTestObject = $this->getMock('fcPayOneOrder', array('fcpoGetAmazonErrorTranslationString'));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoGetAmazonErrorTranslationString')
            ->will($this->returnValue('someTranslationString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someMessage', $oTestObject->fcpoGetAmazonErrorMessage('someErrorCode'));
    }

    /**
     * Testing _fcpoGetAmazonSuccessCode for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoGetAmazonSuccessCode_Coverage() {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(900, $oTestObject->_fcpoGetAmazonSuccessCode('900M'));
    }

    /**
     * Testing fcpoGetAmazonErrorTranslationString in case of invalid payment method
     *
     * @param void
     * @return void
     */
    public function test_fcpoGetAmazonErrorTranslationString_InvalidPaymentMethod() {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(
            'FCPO_AMAZON_ERROR_INVALID_PAYMENT_METHOD',
            $oTestObject->fcpoGetAmazonErrorTranslationString(981)
        );
    }

    /**
     * Testing fcpoGetAmazonErrorTranslationString in case of amazon rejected
     *
     * @param void
     * @return void
     */
    public function test_fcpoGetAmazonErrorTranslationString_AmazonRejected() {
        $oTestObject = oxNew('fcPayOneOrder');

        $aMockResponse['userid'] = 'someUserId';
        $aMockProfileIds = array('someProfileId');

        $this->invokeSetAttribute($oTestObject, '_aPaymentsProfileIdentSave', $aMockProfileIds);

        $this->assertEquals(null, $oTestObject->_fcpoSaveProfileIdent('someProfileId', $aMockResponse));
    }

    /**
     * Testing fcpoGetAmazonErrorTranslationString in case of processing error
     *
     * @param void
     * @return void
     */
    public function test_fcpoGetAmazonErrorTranslationString_ProcessingError() {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(
            'FCPO_AMAZON_ERROR_PROCESSING_FAILURE',
            $oTestObject->fcpoGetAmazonErrorTranslationString(983)
        );
    }

    /**
     * Testing fcpoGetAmazonErrorTranslationString in case of buyer equals seller
     *
     * @param void
     * @return void
     */
    public function test_fcpoGetAmazonErrorTranslationString_BuyerEqualsSeller() {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(
            'FCPO_AMAZON_ERROR_BUYER_EQUALS_SELLER',
            $oTestObject->fcpoGetAmazonErrorTranslationString(984)
        );
    }

    /**
     * Testing fcpoGetAmazonErrorTranslationString in case of payment not allowed
     *
     * @param void
     * @return void
     */
    public function test_fcpoGetAmazonErrorTranslationString_PaymentNotAllowed() {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(
            'FCPO_AMAZON_ERROR_PAYMENT_NOT_ALLOWED',
            $oTestObject->fcpoGetAmazonErrorTranslationString(985)
        );
    }

    /**
     * Testing fcpoGetAmazonErrorTranslationString in case of payment plan not set
     *
     * @param void
     * @return void
     */
    public function test_fcpoGetAmazonErrorTranslationString_PlanNotSet() {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(
            'FCPO_AMAZON_ERROR_PAYMENT_PLAN_NOT_SET',
            $oTestObject->fcpoGetAmazonErrorTranslationString(986)
        );
    }

    /**
     * Testing fcpoGetAmazonErrorTranslationString in case of shipping not set
     *
     * @param void
     * @return void
     */
    public function test_fcpoGetAmazonErrorTranslationString_ShippingNotSet() {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(
            'FCPO_AMAZON_ERROR_SHIPPING_ADDRESS_NOT_SET',
            $oTestObject->fcpoGetAmazonErrorTranslationString(987)
        );
    }

    /**
     * Testing fcpoGetAmazonErrorTranslationString in case of transaction
     * timed out
     *
     * @param void
     * @return void
     */
    public function test_fcpoGetAmazonErrorTranslationString_TimedOut() {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(
            'FCPO_AMAZON_ERROR_TRANSACTION_TIMED_OUT',
            $oTestObject->fcpoGetAmazonErrorTranslationString(980)
        );
    }

    /**
     * Testing fcpoGetAmazonErrorTranslationString in case of non listed problem
     *
     * @param void
     * @return void
     */
    public function test_fcpoGetAmazonErrorTranslationString_Default() {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(
            'FCPO_AMAZON_ERROR_900',
            $oTestObject->fcpoGetAmazonErrorTranslationString('FantasyIsEverything')
        );
    }

    /**
     * Testing _fcpoIsPayonePaymentType with positive check on standard
     *
     * @param void
     * @return void
     */
    public function test__fcpoIsPayonePaymentType_Standard() {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->assertEquals(true, $oTestObject->_fcpoIsPayonePaymentType('fcpoinvoice'));
    }

    /**
     * Testing _fcpoGetAppointedError for coverage
     */
    public function test__fcpoGetAppointedError_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrder');
        $this->invokeSetAttribute($oTestObject, '_blFcPayoneAppointedError', true);

        $this->assertEquals(true, $oTestObject->_fcpoGetAppointedError());
    }

}
