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
 
class Unit_fcPayOne_Extend_Application_Controllers_fcPayOneThankyouView extends OxidTestCase
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
     * Testing fcpoGetMandatePdfUrl active status
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetMandatePdfUrl_Active() 
    {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam','getShopUrl'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someConfigParam'));
        $oMockConfig->expects($this->any())->method('getShopUrl')->will($this->returnValue('http://www.someshopurl.org/'));
        
        $oMockOrder = $this->getMock('oxOrder', array('getId'));
        $oMockOrder->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockOrder->oxorder__oxpaymenttype = new oxField('fcpodebitnote');
        
        $oMockUser = new stdClass();
        $oMockUser->oxuser__oxpassword = new oxField(false);

        $oTestObject = $this->getMock(
            'fcPayOneThankyouView', array(
                'getConfig',
                'getOrder',
                'getUser',
            )
        );
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getOrder')->will($this->returnValue($oMockOrder));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        
        $oMockPayment = $this->getMock('oxpayment', array('fcpoAddMandateToDb'));
        $oMockPayment->expects($this->any())->method('fcpoAddMandateToDb')->will($this->returnValue(null));
        
        $aMockMandate = array(
            'mandate_identification'=>'someValue',
            'mode' => 'test',
            'mandate_status'=>'active',
        );
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->onConsecutiveCalls($aMockMandate, 'someUserId'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = 'http://www.someshopurl.org/modules/fcPayOne/download.php?id=someId&uid=someUserId';
        
        $this->assertEquals($sExpect, $oTestObject->fcpoGetMandatePdfUrl());
    }
    
    
    /**
     * Testing fcpoGetMandatePdfUrl inactive status
     * 
     * @param  void
     * @return void
     */
    public function test_fcpoGetMandatePdfUrl_Inactive() 
    {
        $oMockDatabase = $this->getMock('oxDb', array('Execute'));
        $oMockDatabase->expects($this->any())->method('Execute')->will($this->returnValue(true));
        
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam','getShopUrl'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someConfigParam'));
        $oMockConfig->expects($this->any())->method('getShopUrl')->will($this->returnValue('http://www.someshopurl.org/'));
        
        $oMockOrder = $this->getMock('oxOrder', array('getId'));
        $oMockOrder->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockOrder->oxorder__oxpaymenttype = new oxField('fcpodebitnote');
        
        $oMockUser = new stdClass();
        $oMockUser->oxuser__oxpassword = new oxField(false);

        $oTestObject = $this->getMock(
            'fcPayOneThankyouView', array(
                'getConfig',
                'getOrder',
                'getUser',
            )
        );
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getOrder')->will($this->returnValue($oMockOrder));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        
        $oMockRequest = $this->getMock('fcporequest', array('sendRequestGetFile'));
        $oMockRequest->expects($this->any())->method('sendRequestGetFile')->will($this->returnValue('http://www.someurl.org/somepdf.pdf'));
        
        $aMockMandate = array(
            'mandate_identification'=>'someValue',
            'mode' => 'test',
            'mandate_status'=>'lazy',
        );
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->onConsecutiveCalls($aMockMandate, 'someUserId'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        
        $sExpect = 'http://www.someurl.org/somepdf.pdf&uid=someUserId';
        
        $this->assertEquals($sExpect, $oTestObject->fcpoGetMandatePdfUrl());
    }
    
    
    /**
     * Testing fcpoIsAppointedError for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcpoIsAppointedError_Coverage() 
    {
        $oMockOrder = $this->getMock('oxOrder', array('isPayOnePaymentType'));
        $oMockOrder->expects($this->any())->method('isPayOnePaymentType')->will($this->returnValue(true));
        $oMockOrder->oxorder__oxfolder      = new oxField('ORDERFOLDER_PROBLEMS');
        $oMockOrder->oxorder__oxtransstatus = new oxField('ERROR');
        
        $oTestObject = $this->getMock('fcPayOneThankyouView', array('getOrder'));
        $oTestObject->expects($this->any())->method('getOrder')->will($this->returnValue($oMockOrder));
        
        $this->assertEquals(true, $oTestObject->fcpoIsAppointedError());
    }

    /**
     * Testing render method for coverage
     *
     * @doesNotPerformAssertions
     * @param  void
     * @return void
     */
    public function test_Render_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('getId'));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        
        $oMockBasket = $this->getMock('oxBasket', array('getProductsCount'));
        $oMockBasket->expects($this->any())->method('getProductsCount')->will($this->returnValue(5));
        
        $oTestObject = $this->getMock('fcPayOneThankyouView', array(
            'getUser',
            '_fcpoHandleAmazonThankyou',
            '_fcpoDeleteSessionVariablesOnOrderFinish',
        ));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_fcpoHandleAmazonThankyou')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoDeleteSessionVariablesOnOrderFinish')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        /**
         * @todo: problems with staic parent call will lead to error.
         */
        //$this->assertEquals('page/checkout/thankyou.tpl', $oTestObject->render());
    }

    /**
     * Testing _fcpoDeleteSessionVariablesOnOrderFinish for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoDeleteSessionVariablesOnOrderFinish_Coverage() {
        $oTestObject = oxNew('fcPayOneThankyouView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoDeleteSessionVariablesOnOrderFinish());
    }

    /**
     * Testing fcpoIsAmazonOrder for coverage
     *
     * @param void
     * @return void
     */
    public function test_fcpoIsAmazonOrder_Coverage() {
        $oTestObject = oxNew('fcPayOneThankyouView');
        $this->invokeSetAttribute($oTestObject, '_blIsAmazonOrder', true);

        $this->assertEquals(true, $oTestObject->fcpoIsAmazonOrder());
    }

    /**
     * Testing _fcpoHandleAmazonThankyou for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoHandleAmazonThankyou_Coverage() {
        $oTestObject = $this->getMock('fcPayOneThankyouView', array(
            '_fcpoDetermineAmazonOrder',
        ));
        $oTestObject->expects($this->any())->method('_fcpoDetermineAmazonOrder')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoHandleAmazonThankyou());
    }

    /**
     * Testing _fcpoDetermineAmazonOrder for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoDetermineAmazonOrder_Coverage() {
        $oTestObject = oxNew('fcPayOneThankyouView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someToken'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoDetermineAmazonOrder());
    }
    
    /**
     * Testing fcpoGetBarzahlenHtml for coverage
     * 
     * @param  void
     * @return void
     */
    public function test_fcpoGetBarzahlenHtml_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneThankyouView');
        $this->invokeSetAttribute($oTestObject, '_sBarzahlenHtml', null);
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someHtml'));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals('someHtml', $oTestObject->fcpoGetBarzahlenHtml());
    }
    
    
}
