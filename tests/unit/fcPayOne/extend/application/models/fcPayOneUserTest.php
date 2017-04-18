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
  
class Unit_fcPayOne_Extend_Application_Models_fcPayOneUserTest extends OxidTestCase
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
     * Testing fcpoSetBoni with scorevalue
     */
    public function test_fcpoSetBoni_ScoreValue() 
    {
        $aMockResponse = array(
            'scorevalue' => 5000,
            'score' => 'R',
        );

        $oTestObject = oxNew('fcPayOneUser');
        $oTestObject->oxuser__oxboni = new oxField('');
        $oTestObject->oxuser__fcpobonicheckdate = new oxField('');

        $this->assertEquals(null, $oTestObject->fcpoSetBoni($aMockResponse));
    }

    /**
     * Testing fcpoSetBoni with score tag only
     */
    public function test_fcpoSetBoni_Score() 
    {
        $aMockResponse = array(
            'score' => 'R',
        );

        $oTestObject = oxNew('fcPayOneUser');
        $oTestObject->oxuser__oxboni = new oxField('');
        $oTestObject->oxuser__fcpobonicheckdate = new oxField('');

        $this->assertEquals(null, $oTestObject->fcpoSetBoni($aMockResponse));
    }

    /**
     * Testing isNewBonicheckNeeded for coverage
     */
    public function test_isNewBonicheckNeeded_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneUser');
        $oTestObject->oxuser__fcpobonicheckdate = new oxField('2016-05-01');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(7));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->isNewBonicheckNeeded());
    }

    /**
     * Testing isBonicheckNeededForBasket for coverage
     */
    public function test_isBonicheckNeededForBasket_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneUser');

        $oMockPrice = $this->getMock('oxPrice', array('getBruttoPrice'));
        $oMockPrice->expects($this->any())->method('getBruttoPrice')->will($this->returnValue(10));

        $oMockBasket = $this->getMock('oxBasket', array('getPrice'));
        $oMockBasket->expects($this->any())->method('getPrice')->will($this->returnValue($oMockPrice));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(50));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->isBonicheckNeededForBasket());
    }

    /**
     * Testing isBonicheckNeeded for coverage
     */
    public function test_isBonicheckNeeded_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneUser', array('getBoni', 'isNewBonicheckNeeded', 'isBonicheckNeededForBasket'));
        $oTestObject->expects($this->any())->method('getBoni')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isNewBonicheckNeeded')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isBonicheckNeededForBasket')->will($this->returnValue(true));

        $this->assertEquals(true, $oTestObject->isBonicheckNeeded());
    }

    /**
     * Testing checkAddressAndScore in expection that the early return will be triggered with true
     */
    public function test_checkAddressAndScore_ExpectEarlyTrue() 
    {
        $oTestObject = $this->getMock('fcPayOneUser', array('isBonicheckNeeded', 'fcpoSetBoni', 'fcpoIsValidAddress'));
        $oTestObject->expects($this->any())->method('isBonicheckNeeded')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoSetBoni')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoIsValidAddress')->will($this->returnValue(true));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestConsumerscore', 'sendRequestAddresscheck'));
        $oMockRequest->expects($this->any())->method('sendRequestConsumerscore')->will($this->returnValue(true));
        $oMockRequest->expects($this->any())->method('sendRequestAddresscheck')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->onConsecutiveCalls(true, true, '-1', true, true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->checkAddressAndScore(true, true));
    }

    /**
     * Testing checkAddressAndScore covering the rest of code
     */
    public function test_checkAddressAndScore_Coverage() 
    {
        $oTestObject = $this->getMock('fcPayOneUser', array('isBonicheckNeeded', 'fcpoSetBoni', 'fcpoIsValidAddress'));
        $oTestObject->expects($this->any())->method('isBonicheckNeeded')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoSetBoni')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoIsValidAddress')->will($this->returnValue(true));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestConsumerscore', 'sendRequestAddresscheck'));
        $oMockRequest->expects($this->any())->method('sendRequestConsumerscore')->will($this->returnValue(true));
        $oMockRequest->expects($this->any())->method('sendRequestAddresscheck')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->checkAddressAndScore(true, true));
    }

    /**
     * Testing checkAddressAndScore covering the rest of code
     */
    public function test_checkAddressAndScore_Coverage_2() 
    {
        $oTestObject = $this->getMock('fcPayOneUser', array('isBonicheckNeeded', 'fcpoSetBoni', 'fcpoIsValidAddress'));
        $oTestObject->expects($this->any())->method('isBonicheckNeeded')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoSetBoni')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoIsValidAddress')->will($this->returnValue(true));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestConsumerscore', 'sendRequestAddresscheck'));
        $oMockRequest->expects($this->any())->method('sendRequestConsumerscore')->will($this->returnValue(true));
        $oMockRequest->expects($this->any())->method('sendRequestAddresscheck')->will($this->returnValue(array('someResponse')));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->checkAddressAndScore(true, false));
    }

    /**
     * Testing getBoni from payone overwriting
     */
    public function test_getBoni_DefaultBoni() 
    {
        $oTestObject = oxNew('fcPayOneUser');
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(5000));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(5000, $oTestObject->getBoni());
    }

    /**
     * Testing getBoni from parent call
     */
    public function test_getBoni_Parent() 
    {
        $oTestObject = oxNew('fcPayOneUser');
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(1000, $oTestObject->getBoni());
    }

    /**
     * Testing fcpoIsValidAddress 
     */
    public function test_fcpoIsValidAddress_EarlyTrue() 
    {
        $aMockResponse = array(
            'fcWrongCountry' => true,
        );

        $oTestObject = oxNew('fcPayOneUser');

        $this->assertEquals(true, $oTestObject->fcpoIsValidAddress($aMockResponse, true));
    }

    /**
     * Testing fcpoIsValidAddress for case that address check fails
     */
    public function test_fcpoIsValidAddress_AddressCheckFails() 
    {
        $aMockResponse = array(
            'status' => 'VALID',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateResponse')->will($this->returnValue(false));

        $this->assertEquals(false, $oTestObject->fcpoIsValidAddress($aMockResponse, true));
    }

    /**
     * Testing fcpoIsValidAddress for case that everything is ok
     */
    public function test_fcpoIsValidAddress_EverythingOk() 
    {
        $aMockResponse = array(
            'status' => 'VALID',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
            'fcWrongCountry' => true,
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateResponse')->will($this->returnValue(true));

        $this->assertEquals(true, $oTestObject->fcpoIsValidAddress($aMockResponse, true));
    }

    /**
     * Testing _fcpoValidateResponse for case that addresscheck response is valid
     */
    public function test__fcpoValidateResponse_Valid() 
    {
        $aMockResponse = array(
            'status' => 'VALID',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateUserDataByResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateUserDataByResponse')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoValidateResponse($aMockResponse, true));
    }

    /**
     * Testing _fcpoValidateResponse for case that addresscheck response is invalid
     */
    public function test__fcpoValidateResponse_Invalid() 
    {
        $aMockResponse = array(
            'status' => 'INVALID',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateUserDataByResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateUserDataByResponse')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoValidateResponse($aMockResponse, true));
    }

    /**
     * Testing _fcpoValidateResponse for case that addresscheck response has error message
     */
    public function test__fcpoValidateResponse_Error() 
    {
        $aMockResponse = array(
            'status' => 'ERROR',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateUserDataByResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateUserDataByResponse')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoValidateResponse($aMockResponse, true));
    }
    
    /**
     * Testing _fcpoValidateResponse for case that addresscheck response has no response
     */
    public function test__fcpoValidateResponse_None() 
    {
        $aMockResponse = array(
            'status' => 'SOMENONEXISTINGVALUE',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateUserDataByResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateUserDataByResponse')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoValidateResponse($aMockResponse, true));
    }
    
    /**
     * Testing _fcpoValidateUserDataByResponse for case that addresscheck response has error message
     */
    public function test__fcpoValidateUserDataByResponse_Error() 
    {
        $aMockResponse = array(
            'status' => 'ERROR',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOneUser', array('save'));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoValidateUserDataByResponse($aMockResponse, true));
    }
    
    /**
     * Testing _fcpoValidateUserDataByResponse for case that addresscheck response has error message
     */
    public function test__fcpoValidateUserDataByResponse_Success() 
    {
        $aMockResponse = array(
            'status' => 'VALID',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('save'));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoValidateUserDataByResponse($aMockResponse, true));
    }
    
    

    /**
     * Testing fcpoUnsetGroups for coverage
     */
    public function test_fcpoUnsetGroups_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneUser');
        $this->assertEquals(null, $oTestObject->fcpoUnsetGroups());
    }

}
