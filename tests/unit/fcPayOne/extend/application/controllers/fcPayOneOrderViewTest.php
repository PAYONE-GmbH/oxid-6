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

class Unit_fcPayOne_Extend_Application_Controllers_fcPayOneOrderView extends OxidTestCaseCompatibilityWrapper
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
     * Testing execute if mandate feature will be used
     * 
     * @param  void
     * @return void
     */
    public function test_execute_Mandate() 
    {
        $oTestObject = $this->getMock('fcPayOneOrderView', array('_fcpoMandateAcceptanceNeeded'));
        $oTestObject->expects($this->any())->method('_fcpoMandateAcceptanceNeeded')->will($this->returnValue(true));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('false'));
        
        //        $this->invokeSetAttribute($oTestObject, '_sPayPalExpressPic', null);
        //        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $mResponse = $mExpect = $oTestObject->execute();
        
        $this->assertEquals($mExpect, $mResponse);
    }
    
    /**
     * Testing execute if parent call will be used
     * 
     * @param  void
     * @return void
     */
    public function test_execute_Parent() 
    {
        $oTestObject = $this->getMock('fcPayOneOrderView', array('_fcpoMandateAcceptanceNeeded'));
        $oTestObject->expects($this->any())->method('_fcpoMandateAcceptanceNeeded')->will($this->returnValue(false));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('true'));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $mResponse = $mExpect = $oTestObject->execute();
        
        $this->assertEquals($mExpect, $mResponse);
    }
    
    /**
     * Testing fcpoHandlePayPalExpress for PositiveCall
     *  
     * @param  void
     * @return void
     */
    public function test_fcpoHandlePayPalExpress_PositiveCall() 
    {
        $oTestObject = $this->getMock('fcPayOneOrderView', array('_handlePayPalExpressCall'));
        $oTestObject->expects($this->any())->method('_handlePayPalExpressCall')->will($this->returnValue(true));
        
        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(null));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(null, $oTestObject->fcpoHandlePayPalExpress());
    }
    
    
    /**
     * Testing fcpoHandlePayPalExpress for Exception
     * 
     * @param  void
     * @return void
     */
    public function test_fcpoHandlePayPalExpress_Exception() 
    {
        $oMockException = new oxException;
        
        $oTestObject = $this->getMock('fcPayOneOrderView', array('_handlePayPalExpressCall'));
        $oTestObject->expects($this->any())->method('_handlePayPalExpressCall')->will($this->throwException($oMockException));
        
        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(null));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sExpect = 'basket';
        
        $this->assertEquals($sExpect, $oTestObject->fcpoHandlePayPalExpress());
    }

    /**
     * Testing _getNextStep for coverage
     */
    public function test__getNextStep_Coverage() {
        $iMockSuccess = 1;
        $sMockRedirectAction = 'someRedirectAction';

        $oTestObject = $this->getMock('fcPayOneOrderView', array(
            '_fcpoGetRedirectAction'
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetRedirectAction')
            ->will($this->returnValue($sMockRedirectAction));

        $this->assertEquals($sMockRedirectAction, $oTestObject->_getNextStep($iMockSuccess));
    }

    /**
     * Testing _fcpoAmazonLogout for coverage
     */
    public function test__fcpoAmazonLogout_Coverage() {
        $oTestObject = $this->getMock('fcPayOneOrderView', array(
            '_fcpoDeleteCurrentUser'
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoDeleteCurrentUser')
            ->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(null));

        $this->assertEquals(null, $oTestObject->_fcpoAmazonLogout());
    }

    /**
     * Testing _fcpoDeleteCurrentUser for coverage
     */
    public function test__fcpoDeleteCurrentUser_Coverage() {
        $oTestObject = oxNew('fcPayOneOrderView');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someUserId'));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(null));

        $this->assertEquals(null, $oTestObject->_fcpoDeleteCurrentUser());
    }

    /**
     * Testing _fcpoGetRedirectAction for case plan not set
     */
    public function test__fcpoGetRedirectAction_PlanNotSet() {
        $oMockOrder = $this->getMock('oxOrder', array(
            'fcpoGetAmazonErrorMessage'
        ));
        $oMockOrder
            ->expects($this->any())
            ->method('fcpoGetAmazonErrorMessage')
            ->will($this->returnValue('someErrorMessage'));

        $oTestObject = $this->getMock('fcPayOneOrderView', array('_fcpoAmazonLogout'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoAmazonLogout')
            ->will($this->returnValue(null));
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('basket?fcpoerror=someErrorMessage', $oTestObject->_fcpoGetRedirectAction(986));
    }

    /**
     * Testing _fcpoGetRedirectAction for case 900 error
     */
    public function test__fcpoGetRedirectAction_900() {
        $oMockOrder = $this->getMock('oxOrder', array(
            'fcpoGetAmazonErrorMessage'
        ));
        $oMockOrder
            ->expects($this->any())
            ->method('fcpoGetAmazonErrorMessage')
            ->will($this->returnValue('someErrorMessage'));

        $oTestObject = $this->getMock('fcPayOneOrderView', array('_fcpoAmazonLogout'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoAmazonLogout')
            ->will($this->returnValue(null));
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->returnValue($oMockOrder));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = 'basket?fcpoerror=someErrorMessage';

        $this->assertEquals($sExpect, $oTestObject->_fcpoGetRedirectAction(900));
    }

    /**
     * Testing _fcpoGetRedirectAction for case no shipping address given
     */
    public function test__fcpoGetRedirectAction_NoShippingAddress() {
        $oMockOrder = $this->getMock('oxOrder', array(
            'fcpoGetAmazonErrorMessage'
        ));
        $oMockOrder
            ->expects($this->any())
            ->method('fcpoGetAmazonErrorMessage')
            ->will($this->returnValue('someErrorMessage'));

        $oTestObject = $this->getMock('fcPayOneOrderView', array('_fcpoAmazonLogout'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoAmazonLogout')
            ->will($this->returnValue(null));
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = 'user?fcpoerror=someErrorMessage';

        $this->assertEquals($sExpect, $oTestObject->_fcpoGetRedirectAction(987));
    }

    /**
     * Testing _fcpoDoesUserAlreadyExist for Coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoDoesPaypalUserAlreadyExist_Coverage()
    {
        $oTestObject = oxNew('fcPayOneOrderView');
        
        $oMockOrder = $this->getMock('oxOrder', array('fcpoDoesUserAlreadyExist'));
        $oMockOrder->expects($this->any())->method('fcpoDoesUserAlreadyExist')->will($this->returnValue('someUserId'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals('someUserId', $this->invokeMethod($oTestObject, '_fcpoDoesPaypalUserAlreadyExist', array('someEmail')));
    }


    /**
     * Testing _fcpoGetIdByUserName for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoGetIdByUserName_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrderView');
        
        $oMockOrder = $this->getMock('oxOrder', array('fcpoGetIdByUserName'));
        $oMockOrder->expects($this->any())->method('fcpoGetIdByUserName')->will($this->returnValue('someUserId'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals('someUserId', $this->invokeMethod($oTestObject, '_fcpoGetIdByUserName', array('someUserName')));
    }
    
    
    /**
     * Testing _fcpoGetIdByCode for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoGetIdByCode_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrderView');

        $oMockOrder = $this->getMock('oxOrder', array('fcpoGetIdByCode'));
        $oMockOrder->expects($this->any())->method('fcpoGetIdByCode')->will($this->returnValue('someId'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals('someId', $this->invokeMethod($oTestObject, '_fcpoGetIdByCode', array('someCode')));
    }
    
    
    /**
     * Testing _fcpoGetSal MR as response
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoGetSal_MR() 
    {
        $oTestObject = oxNew('fcPayOneOrderView');

        $oMockOrder = $this->getMock('oxOrder', array('fcpoGetSalByFirstName'));
        $oMockOrder->expects($this->any())->method('fcpoGetSalByFirstName')->will($this->returnValue('Herr'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals('MR', $this->invokeMethod($oTestObject, '_fcpoGetSal', array('someFirstName')));
    }
    
    /**
     * Testing _fcpoGetSal MRS as response
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoGetSal_MRS() 
    {
        $oTestObject = oxNew('fcPayOneOrderView');

        $oMockOrder = $this->getMock('oxOrder', array('fcpoGetSalByFirstName'));
        $oMockOrder->expects($this->any())->method('fcpoGetSalByFirstName')->will($this->returnValue('Frau'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals('MRS', $this->invokeMethod($oTestObject, '_fcpoGetSal', array('someFirstName')));
    }
    
    
    /**
     * Testing _fcpoCreatePayPalUser
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoCreatePayPalUser_Coverage() 
    {
        $oTestObject = $this->getMock(
            'fcPayOneOrderView', array(
                '_fcpoGetIdByUserName', 
                '_fcpoSplitAddress', 
                '_fcpoGetSal', 
                '_fcpoGetIdByCode'
            )
        );
        $oTestObject->expects($this->any())->method('_fcpoGetIdByUserName')->will($this->returnValue('someId'));
        $oTestObject->expects($this->any())->method('_fcpoSplitAddress')->will($this->returnValue(array('someStreet', 'someStreetNr')));
        $oTestObject->expects($this->any())->method('_fcpoGetSal')->will($this->returnValue('MR'));
        $oTestObject->expects($this->any())->method('_fcpoGetIdByCode')->will($this->returnValue('someId'));
        
        $oMockUser = $this->getMock('oxUser', array('load','save','addToGroup','fcpoUnsetGroups'));
        $oMockUser->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));
        $oMockUser->expects($this->any())->method('addToGroup')->will($this->returnValue(true));
        $oMockUser->expects($this->any())->method('fcpoUnsetGroups')->will($this->returnValue(true));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUser));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        
        $aParams = array(
            'add_paydata[shipping_street]'              => 'someStreet someStreetNr',
            'add_paydata[shipping_addressaddition]'     => 'someAddition',
            'add_paydata[email]'                        => 'someUserMail',
            'add_paydata[shipping_firstname]'           => 'someFirstName',
            'add_paydata[shipping_lastname]'            => 'someLastName',
            'add_paydata[shipping_city]'                => 'someCity',
            'add_paydata[shipping_zip]'                 => 'someZip',
            'add_paydata[shipping_country]'             => 'someCountry',
        );

        $mResponse = $mExpect = $this->invokeMethod($oTestObject, '_fcpoCreatePayPalUser', array($aParams));
        
        $this->assertEquals($mExpect, $mResponse);
    }
    
    
    /**
     * Testing _fcpoIsSamePayPalUser for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoIsSamePayPalUser_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrderView');
        
        $aReponseParam = array(
            'add_paydata[shipping_firstname]'   => 'someFirstName',
            'add_paydata[shipping_lastname]'    => 'someLastName',
            'add_paydata[shipping_city]'        => 'someCity',
            'add_paydata[shipping_street]'      => 'someStreet',
        );
        
        $oMockUserObjectParam = new stdClass();
        $oMockUserObjectParam->oxuser__oxfname  = new oxField('someOtherFirstName');
        $oMockUserObjectParam->oxuser__oxlname  = new oxField('someOtherLastName');
        $oMockUserObjectParam->oxuser__oxcity   = new oxField('someOtherCity');
        $oMockUserObjectParam->oxuser__oxstreet = new oxField('someStreet');
        
        
        $this->assertEquals(true, $oTestObject->_fcpoIsSamePayPalUser($oMockUserObjectParam, $aReponseParam));
    }
    
    
    /**
     * Testing _fcpoHandleUser on case RemoveAddressFromSession
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandlePaypalExpressUser_RemoveAddressFromSession()
    {
        $oMockUserObject = $this->getMock('oxUser', array('getId','load'));
        $oMockUserObject->expects($this->any())->method('getId')->will($this->returnValue(true));
        $oMockUserObject->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockUserObject->oxuser__oxusername = new oxField('someEmail');

        $oTestObject = $this->getMock(
            'fcPayOneOrderView', array(
                'getUser', 
                '_fcpoDoesExpressUserAlreadyExist',
                '_fcpoIsSameExpressUser',
                '_fcpoCreatePayPalDelAddress',
                '_fcpoCreatePayPalUser',
                '_fcpoThrowException',
            )
        );
        $oTestObject->expects($this->any())->method('getUser')
            ->will($this->returnValue($oMockUserObject));
        $oTestObject->expects($this->any())->method('_fcpoDoesExpressUserAlreadyExist')
            ->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoIsSameExpressUser')
            ->will($this->onConsecutiveCalls(true, true));
        $oTestObject->expects($this->any())->method('_fcpoCreatePayPalDelAddress')
            ->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCreatePayPalUser')
            ->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoThrowException')
            ->will($this->returnValue(true));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUserObject));
        
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aParams = array(
            'add_paydata[shipping_street]'              => 'someStreet someStreetNr',
            'add_paydata[shipping_addressaddition]'     => 'someAddition',
            'add_paydata[email]'                        => 'someUserMail',
            'add_paydata[shipping_firstname]'           => 'someFirstName',
            'add_paydata[shipping_lastname]'            => 'someLastName',
            'add_paydata[shipping_city]'                => 'someCity',
            'add_paydata[shipping_zip]'                 => 'someZip',
            'add_paydata[shipping_country]'             => 'someCountry',
        );
        
        $mResponse = $mExpect = $this->invokeMethod($oTestObject, '_fcpoHandleExpressUser', array($aParams));
        
        $this->assertEquals($mExpect, $mResponse);
    }
    
    
    /**
     * Testing _fcpoHandleUser on case CreatePaypalDelAddress
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandlePaypalExpressUser_CreatePaypalDelAddress()
    {
        $oMockUserObject = $this->getMock('oxUser', array('getId','load'));
        $oMockUserObject->expects($this->any())->method('getId')->will($this->returnValue(true));
        $oMockUserObject->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockUserObject->oxuser__oxusername = new oxField('someEmail');

        $oTestObject = $this->getMock(
            'fcPayOneOrderView', array(
                'getUser', 
                '_fcpoDoesExpressUserAlreadyExist',
                '_fcpoIsSameExpressUser',
                '_fcpoCreateExpressDelAddress',
                '_fcpoThrowException',
            )
        );
        $oTestObject->expects($this->any())->method('getUser')
            ->will($this->returnValue($oMockUserObject));
        $oTestObject->expects($this->any())->method('_fcpoDoesExpressUserAlreadyExist')
            ->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoIsSameExpressUser')
            ->will($this->onConsecutiveCalls(false, false));
        $oTestObject->expects($this->any())->method('_fcpoCreateExpressDelAddress')
            ->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoThrowException')
            ->will($this->returnValue(true));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUserObject));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aParams = array(
            'add_paydata[shipping_street]'              => 'someStreet someStreetNr',
            'add_paydata[shipping_addressaddition]'     => 'someAddition',
            'add_paydata[email]'                        => 'someUserMail',
            'add_paydata[shipping_firstname]'           => 'someFirstName',
            'add_paydata[shipping_lastname]'            => 'someLastName',
            'add_paydata[shipping_city]'                => 'someCity',
            'add_paydata[shipping_zip]'                 => 'someZip',
            'add_paydata[shipping_country]'             => 'someCountry',
        );

        $mResponse = $mExpect = $this->invokeMethod($oTestObject, '_fcpoHandleExpressUser', array($aParams));
        
        $this->assertEquals($mExpect, $mResponse);
    }
    
    
    /**
     * Testing _fcpoHandleUser on case CreatePaypalDelAddress
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandlePaypalExpressUser_ThrowException()
    {
        $oMockUserObject = $this->getMock('oxUser', array('getId','load'));
        $oMockUserObject->expects($this->any())->method('getId')->will($this->returnValue(true));
        $oMockUserObject->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockUserObject->oxuser__oxusername = new oxField('someEmail');

        $oTestObject = $this->getMock(
            'fcPayOneOrderView', array(
                'getUser', 
                '_fcpoDoesExpressUserAlreadyExist',
                '_fcpoIsSameExpressUser',
                '_fcpoCreateExpressDelAddress',
                '_fcpoThrowException',
            )
        );
        $oTestObject->expects($this->any())->method('getUser')
            ->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoDoesExpressUserAlreadyExist')
            ->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoIsSameExpressUser')
            ->will($this->onConsecutiveCalls(false, false));
        $oTestObject->expects($this->any())->method('_fcpoCreateExpressDelAddress')
            ->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoThrowException')
            ->will($this->returnValue(true));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUserObject));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aParams = array(
            'add_paydata[shipping_street]'              => 'someStreet someStreetNr',
            'add_paydata[shipping_addressaddition]'     => 'someAddition',
            'add_paydata[email]'                        => 'someUserMail',
            'add_paydata[shipping_firstname]'           => 'someFirstName',
            'add_paydata[shipping_lastname]'            => 'someLastName',
            'add_paydata[shipping_city]'                => 'someCity',
            'add_paydata[shipping_zip]'                 => 'someZip',
            'add_paydata[shipping_country]'             => 'someCountry',
        );
        
        $mResponse = $mExpect = $this->invokeMethod($oTestObject, '_fcpoHandleExpressUser', array($aParams));
        
        $this->assertEquals($mExpect, $mResponse);
    }
    
    
    /**
     * Testing _fcpoHandleUser on case CreatePaypalAddress
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoHandlePaypalExpressUser_CreatePaypalAddress()
    {
        $oMockUserObject = $this->getMock('oxUser', array('getId','load'));
        $oMockUserObject->expects($this->any())->method('getId')->will($this->returnValue(true));
        $oMockUserObject->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockUserObject->oxuser__oxusername = new oxField('someEmail');

        $oTestObject = $this->getMock(
            'fcPayOneOrderView', array(
                'getUser', 
                '_fcpoDoesExpressUserAlreadyExist',
                '_fcpoIsSameExpressUser',
                '_fcpoCreateExpressDelAddress',
                '_fcpoCreateExpressUser',
                '_fcpoThrowException',
                '_fcpoGetIdByUserName',
                '_fcpoGetSal',
                '_fcpoGetIdByCode'
            )
        );
        $oTestObject->expects($this->any())->method('getUser')
            ->will($this->returnValue($oMockUserObject));
        $oTestObject->expects($this->any())->method('_fcpoDoesExpressUserAlreadyExist')
            ->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoIsSameExpressUser')
            ->will($this->onConsecutiveCalls(true, false));
        $oTestObject->expects($this->any())->method('_fcpoCreateExpressDelAddress')
            ->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCreateExpressUser')
            ->will($this->returnValue($oMockUserObject));
        $oTestObject->expects($this->any())->method('_fcpoThrowException')
            ->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetIdByUserName')
            ->will($this->returnValue(1));
        $oTestObject->expects($this->any())->method('_fcpoGetSal')
            ->will($this->returnValue('MR'));
        $oTestObject->expects($this->any())->method('_fcpoGetIdByCode')
            ->will($this->returnValue('TEST'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUserObject));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aParams = array(
            'add_paydata[shipping_street]'              => 'someStreet someStreetNr',
            'add_paydata[shipping_addressaddition]'     => 'someAddition',
            'add_paydata[email]'                        => 'someUserMail',
            'add_paydata[shipping_firstname]'           => 'someFirstName',
            'add_paydata[shipping_lastname]'            => 'someLastName',
            'add_paydata[shipping_city]'                => 'someCity',
            'add_paydata[shipping_zip]'                 => 'someZip',
            'add_paydata[shipping_country]'             => 'someCountry',
        );
        
        $mResponse = $mExpect = $this->invokeMethod($oTestObject, '_fcpoHandleExpressUser', array($aParams));
        
        $this->assertEquals($mExpect, $mResponse);
    }
    
    
    /**
     * Testing _fcpoThrowException for coverage
     */
    public function test__fcpoThrowException_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrderView');
        $this->wrapExpectException('oxException');
        $this->assertEquals('', $oTestObject->_fcpoThrowException('someMessage'));
    }
    
    
    /**
     * Testing _handlePayPalExpressCall for coverage
     * 
     * @param  void
     * @return void
     */
    public function test__handlePayPalExpressCall_Coverage() 
    {
        $oMockBasket = $this->getMock(
            'oxBasket', array(
                'setBasketUser',
                'setPayment',
                'setShipping',
                'onUpdate', 
                'calculateBasket',
            )
        );
        $oMockBasket->expects($this->any())->method('setBasketUser')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('setPayment')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('setShipping')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('onUpdate')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('calculateBasket')->will($this->returnValue(true));
        
        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $aDeliverySetData = array('1','1',array());
        $oMockOxDeliverySet = $this->getMock('oxDeliverySet', array('getDeliverySetData'));
        $oMockOxDeliverySet->expects($this->any())->method('getDeliverySetData')->will($this->returnValue($aDeliverySetData));
        
        $oMockUserObject = $this->getMockBuilder('oxUser')->disableOriginalConstructor()->getMock();
        
        $oTestObject = $this->getMock('fcPayOneOrderView', array('_fcpoHandleUser'));
        $oTestObject->expects($this->any())->method('_fcpoHandleUser')->will($this->returnValue($oMockUserObject));
        
        $aMockOutput = array();
        
        $oMockRequest = $this->getMock('fcporequest', array('sendRequestGenericPayment'));
        $oMockRequest->expects($this->any())->method('sendRequestGenericPayment')->will($this->returnValue($aMockOutput));

        $oMockOrder = $this->getMock('oxOrder', array('load', 'fcpoGetIdByUserName'));
        $oMockOrder->expects($this->any())->method('fcpoGetIdByUserName')->will($this->returnValue('someUserId'));
        $oMockOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMock('fcpohelper', array('fcpoGetSessionVariable', 'fcpoDeleteSessionVariable', 'fcpoGetSession'));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('someValue'));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $mResponse = $mExpect = $this->invokeMethod($oTestObject, '_handlePayPalExpressCall');
        
        $this->assertEquals($mExpect, $mResponse);
    }
    
    
    /**
     * Testing _fcpoMandateAcceptanceNeeded for case that accestance needed
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoMandateAcceptanceNeeded_Yes() 
    {
        $oTestObject    = oxNew('fcPayOneOrderView');
        $aMockMandate   = array(
            'mandate_status'    => 'pending',
            'mandate_text'      => 'someText',
        );
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($aMockMandate));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(true, $this->invokeMethod($oTestObject, '_fcpoMandateAcceptanceNeeded'));
    }

   
    /**
     * Testing _fcpoMandateAcceptanceNeeded for case that acceptance is not needed
     * 
     * @param  void
     * @return void
     */
    public function test__fcpoMandateAcceptanceNeeded_No() 
    {
        $oTestObject    = oxNew('fcPayOneOrderView');
        
        $aMockMandate   = array(
            'someblabla'        => 'falseValue',
            'mandate_next'      => 'moreCrap',
        );
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($aMockMandate));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(false, $this->invokeMethod($oTestObject, '_fcpoMandateAcceptanceNeeded'));
    }
   
   
    /**
    * Testing fcpoIsMandateError for coverage
    * 
    * @param  void
    * @return void
    */
    public function test_fcpoIsMandateError_Coverage() 
    {
        $oTestObject    = oxNew('fcPayOneOrderView');
        
        $this->invokeSetAttribute($oTestObject, '_blFcpoConfirmMandateError', false);
        
        $this->assertEquals(false, $this->invokeMethod($oTestObject, 'fcpoIsMandateError'));
    }
   
   
    /**
    * Testing _validateTermsAndConditions for Coverage
    * 
    * @param  void
    * @retutn void
    */
    public function test__validateTermsAndConditions_Coverage() 
    {
        $oMockBasket = $this->getMock('oxBasket', array('hasArticlesWithDownloadableAgreement'));
        $oMockBasket->expects($this->any())->method('hasArticlesWithDownloadableAgreement')->will($this->returnValue(true));
        
        $oTestObject = $this->getMock('fcPayOneOrderView', array('getBasket'));
        $oTestObject->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam', 'getRequestParameter'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oMockConfig->expects($this->any())->method('getRequestParameter')->will($this->returnValue(false));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(true));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $blResponse = $this->invokeMethod($oTestObject, '_validateTermsAndConditions');
        
        $this->assertEquals(true, $blResponse);
    }
   
   
    /**
    * Testing _fcpoSplitAddress for coverage
    * 
    * @param  void
    * @return void
    */
    public function test__fcpoSplitAddress_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneOrderView');
       
        $sInput = "MyStreet 123";
       
        $aExpect = array('MyStreet','123');
       
        $aResult = $this->invokeMethod($oTestObject, '_fcpoSplitAddress', array($sInput));
       
        $this->assertEquals($aExpect, $aResult);
    }
   
   
    /**
    * Testing _fcpoCreatePayPalDelAddress for coverage
    * 
    * @param  void
    * @return void
    */
    public function test__fcpoCreatePayPalDelAddress_HasAddressId() 
    {
        $oTestObject = $this->getMock('fcPayOneOrderView', array('_fcpoGetExistingPayPalAddressId', '_fcpoSplitAddress'));
        $oTestObject->expects($this->any())->method('_fcpoGetExistingPayPalAddressId')->will($this->returnValue('someAddressId'));
        $oTestObject->expects($this->any())->method('_fcpoSplitAddress')->will($this->returnValue(array('MySreet', '123')));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $aMockResponse = array('add_paydata[shipping_addressaddition]' => 'someAddition');
        
        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoCreateExpressDelAddress', array($aMockResponse, 'someUserId')));
    }
   
    /**
    * Testing _fcpoCreatePayPalDelAddress for coverage
    * 
    * @param  void
    * @return void
    */
    public function test__fcpoCreatePayPalDelAddress_NoAddressId() 
    {
        $oTestObject = $this->getMock('fcPayOneOrderView', array('_fcpoGetExistingPayPalAddressId', '_fcpoSplitAddress', '_fcpoGetIdByCode','_fcpoGetSal'));
        $oTestObject->expects($this->any())->method('_fcpoGetExistingPayPalAddressId')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoSplitAddress')->will($this->returnValue(array('MySreet', '123')));
        $oTestObject->expects($this->any())->method('_fcpoGetIdByCode')->will($this->returnValue('someCountryId'));
        $oTestObject->expects($this->any())->method('_fcpoGetSal')->will($this->returnValue('someSalutation'));
        
        
        $oMockAddress = $this->getMock('oxAddress', array('save'));
        $oMockAddress->expects($this->any())->method('save')->will($this->returnValue(true));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $aMockResponse = array('add_paydata[shipping_addressaddition]' => 'someAddition');
        
        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoCreateExpressDelAddress', array($aMockResponse, 'someUserId')));
    }

   
   
    /**
    * Testing _fcpoGetExistingPayPalAddressId for coverage
    * 
    * @param  void
    * @return void
    */
    public function test__fcpoGetExistingPayPalAddressId_Success() 
    {
        $oTestObject = $this->getMock('fcPayOneOrderView', array('_fcpoGetIdByCode'));
        
        $oMockOrder = $this->getMock('oxOrder', array('fcpoGetAddressIdByResponse'));
        $oMockOrder->expects($this->any())->method('fcpoGetAddressIdByResponse')->will($this->returnValue('someAddressId'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
       
        $aMockResponse = array('add_paydata[shipping_street]' => 'MyStreet 123');

        $this->assertEquals('someAddressId', $oTestObject->_fcpoGetExistingPayPalAddressId($aMockResponse));
    }
   
}
