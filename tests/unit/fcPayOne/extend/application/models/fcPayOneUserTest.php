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
     * Testing _fcpoLogMeIn for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoLogMeIn_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array('getId'));
        $oTestObject
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('someId'));

        $oHelper =
            $this
                ->getMockBuilder('fcpohelper')
                ->disableOriginalConstructor()
                ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoSetSessionVariable')
            ->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoLogMeIn());
    }

    /**
     * Testing fcpoSetAmazonOrderReferenceDetailsResponse for coverage
     *
     * @param void
     * @return void
     */
    public function test_fcpoSetAmazonOrderReferenceDetailsResponse_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoAmazonEmailEncode',
            '_fcpoAddOrUpdateAmazonUser',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoAmazonEmailEncode')
            ->will($this->returnValue('someEncodedEmail'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoAddOrUpdateAmazonUser')
            ->will($this->returnValue(null));

        $aMockResponse['add_paydata[email]'] = 'someMail';

        $this->assertEquals(null, $oTestObject->fcpoSetAmazonOrderReferenceDetailsResponse($aMockResponse));
    }

    /**
     * Testing _fcpoAmazonEmailEncode for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAmazonEmailEncode_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');
        $oMockViewConfig = $this->getMock('oxViewConfig', array('fcpoAmazonEmailEncode'));
        $oMockViewConfig->expects($this->any())->method('fcpoAmazonEmailEncode')->will($this->returnValue('someEncodedEmail'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockViewConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = 'someEncodedEmail';
        $this->assertEquals($sExpect, $oTestObject->_fcpoAmazonEmailEncode('someEmail'));
    }

    /**
     * Testing _fcpoAmazonEmailDecode for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAmazonEmailDecode_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');
        $oMockViewConfig = $this->getMock('oxViewConfig', array('fcpoAmazonEmailDecode'));
        $oMockViewConfig->expects($this->any())->method('fcpoAmazonEmailDecode')->will($this->returnValue('someDecodedEmail'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockViewConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = 'someDecodedEmail';
        $this->assertEquals($sExpect, $oTestObject->_fcpoAmazonEmailDecode('someEmail'));
    }

    /**
     * Testing _fcpoAddOrUpdateAmazonUser for case user exists
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAddOrUpdateAmazonUser_UserExists() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoUserExists',
            '_fcpoUpdateAmazonUser',
            '_fcpoAddAmazonUser',
        ));
        $oTestObject->expects($this->any())->method('_fcpoUserExists')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoUpdateAmazonUser')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoAddAmazonUser')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse['add_paydata[email]'] = 'someEmailAddress';

        $this->assertEquals(null, $oTestObject->_fcpoAddOrUpdateAmazonUser($aMockResponse));
    }

    /**
     * Testing _fcpoAddOrUpdateAmazonUser for case user new
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAddOrUpdateAmazonUser_UserNew() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoUserExists',
            '_fcpoUpdateAmazonUser',
            '_fcpoAddAmazonUser',
        ));
        $oTestObject->expects($this->any())->method('_fcpoUserExists')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoUpdateAmazonUser')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoAddAmazonUser')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'add_paydata[billing_street]'=>'',
            'add_paydata[billing_country]'=>'',
            'add_paydata[email]'=>'',
            'add_paydata[billing_zip]'=>'',
            'add_paydata[billing_telephonenumber]'=>'',
            'add_paydata[billing_firstnam'=>'',
            'add_paydata[billing_lastname]'=>'',
            'add_paydata[billing_city]'=>'',
            'add_paydata[shipping_street]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_firstname]'=>'',
            'add_paydata[shipping_lastname]'=>'',
            'add_paydata[shipping_telephonenumber]'=>'',
            'add_paydata[shipping_city]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_zip]'=>'',
        );

        $this->assertEquals(null, $oTestObject->_fcpoAddOrUpdateAmazonUser($aMockResponse));
    }

    /**
     * Testing _fcpoAddAmazonUser for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAddAmazonUser_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoAddDeliveryAddress',
            '_fcpoGetCountryIdByIso2',
            '_fcpoSplitStreetAndStreetNr',
        ));
        $oTestObject->expects($this->any())->method('_fcpoAddDeliveryAddress')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoGetCountryIdByIso2')->will($this->returnValue('DE'));
        $oTestObject->expects($this->any())->method('_fcpoSplitStreetAndStreetNr')->will($this->returnValue(array('somestreet', '1')));

        $oMockUser = $this->getMock('oxUser', array('save', 'addToGroup', 'getId'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(null));
        $oMockUser->expects($this->any())->method('addToGroup')->will($this->returnValue(null));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someUserId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUser));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'add_paydata[billing_street]'=>'',
            'add_paydata[billing_country]'=>'',
            'add_paydata[email]'=>'',
            'add_paydata[billing_zip]'=>'',
            'add_paydata[billing_telephonenumber]'=>'',
            'add_paydata[billing_firstnam'=>'',
            'add_paydata[billing_lastname]'=>'',
            'add_paydata[billing_city]'=>'',
            'add_paydata[shipping_street]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_firstname]'=>'',
            'add_paydata[shipping_lastname]'=>'',
            'add_paydata[shipping_telephonenumber]'=>'',
            'add_paydata[shipping_city]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_zip]'=>'',
        );


        $this->assertEquals('someUserId', $oTestObject->_fcpoAddAmazonUser($aMockResponse));
    }

    /**
     * Testing _fcpoAddAmazonUser for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoUpdateAmazonUser_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoGetUserOxidByEmail',
            '_fcpoAddDeliveryAddress',
            '_fcpoGetCountryIdByIso2',
            '_fcpoSplitStreetAndStreetNr',
        ));
        $oTestObject->expects($this->any())->method('_fcpoGetUserOxidByEmail')->will($this->returnValue('someUserId'));
        $oTestObject->expects($this->any())->method('_fcpoAddDeliveryAddress')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoGetCountryIdByIso2')->will($this->returnValue('DE'));
        $oTestObject->expects($this->any())->method('_fcpoSplitStreetAndStreetNr')->will($this->returnValue(array('somestreet', '1')));

        $oMockUser = $this->getMock('oxUser', array('save', 'addToGroup', 'getId'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(null));
        $oMockUser->expects($this->any())->method('addToGroup')->will($this->returnValue(null));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someUserId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUser));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'add_paydata[billing_street]'=>'',
            'add_paydata[billing_country]'=>'',
            'add_paydata[email]'=>'',
            'add_paydata[billing_zip]'=>'',
            'add_paydata[billing_telephonenumber]'=>'',
            'add_paydata[billing_firstnam'=>'',
            'add_paydata[billing_lastname]'=>'',
            'add_paydata[billing_city]'=>'',
            'add_paydata[shipping_street]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_firstname]'=>'',
            'add_paydata[shipping_lastname]'=>'',
            'add_paydata[shipping_telephonenumber]'=>'',
            'add_paydata[shipping_city]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_zip]'=>'',
        );

        $this->assertEquals('someUserId', $oTestObject->_fcpoUpdateAmazonUser($aMockResponse));
    }

    /**
     * Testing _fcpoAddDeliveryAddress for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAddDeliveryAddress_NotExists() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoSplitStreetAndStreetNr',
            '_fcpoGetCountryIdByIso2',
            '_fcpoCheckAddressExists',
        ));
        $oTestObject->expects($this->any())->method('_fcpoSplitStreetAndStreetNr')->will($this->returnValue(array('somestreet', '1')));
        $oTestObject->expects($this->any())->method('_fcpoGetCountryIdByIso2')->will($this->returnValue('DE'));
        $oTestObject->expects($this->any())->method('_fcpoCheckAddressExists')->will($this->returnValue(false));

        $oMockAddress = $this->getMock('oxAddress', array(
            'load',
            'setId',
            'save',
            'getEncodedDeliveryAddress',
        ));
        $oMockAddress->expects($this->any())->method('load')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('setId')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('save')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('getEncodedDeliveryAddress')->will($this->returnValue('someEncodedAddress'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'add_paydata[billing_street]'=>'',
            'add_paydata[billing_country]'=>'',
            'add_paydata[email]'=>'',
            'add_paydata[billing_zip]'=>'',
            'add_paydata[billing_telephonenumber]'=>'',
            'add_paydata[billing_firstnam'=>'',
            'add_paydata[billing_lastname]'=>'',
            'add_paydata[billing_city]'=>'',
            'add_paydata[shipping_street]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_firstname]'=>'',
            'add_paydata[shipping_lastname]'=>'',
            'add_paydata[shipping_telephonenumber]'=>'',
            'add_paydata[shipping_city]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_zip]'=>'',
        );

        $this->assertEquals(null, $oTestObject->_fcpoAddDeliveryAddress($aMockResponse, 'someUserId'));
    }

    /**
     * Testing _fcpoAddDeliveryAddress for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAddDeliveryAddress_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoSplitStreetAndStreetNr',
            '_fcpoGetCountryIdByIso2',
            '_fcpoCheckAddressExists',
        ));
        $oTestObject->expects($this->any())->method('_fcpoSplitStreetAndStreetNr')->will($this->returnValue(array('somestreet', '1')));
        $oTestObject->expects($this->any())->method('_fcpoGetCountryIdByIso2')->will($this->returnValue('DE'));
        $oTestObject->expects($this->any())->method('_fcpoCheckAddressExists')->will($this->returnValue(false));

        $oMockAddress = $this->getMock('oxAddress', array(
            'load',
            'setId',
            'save',
            'getEncodedDeliveryAddress',
        ));
        $oMockAddress->expects($this->any())->method('load')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('setId')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('save')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('getEncodedDeliveryAddress')->will($this->returnValue('someEncodedAddress'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'add_paydata[billing_street]'=>'',
            'add_paydata[billing_country]'=>'',
            'add_paydata[email]'=>'',
            'add_paydata[billing_zip]'=>'',
            'add_paydata[billing_telephonenumber]'=>'',
            'add_paydata[billing_firstnam'=>'',
            'add_paydata[billing_lastname]'=>'',
            'add_paydata[billing_city]'=>'',
            'add_paydata[shipping_street]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_firstname]'=>'',
            'add_paydata[shipping_lastname]'=>'',
            'add_paydata[shipping_telephonenumber]'=>'',
            'add_paydata[shipping_city]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_zip]'=>'',
        );

        $this->assertEquals(null, $oTestObject->_fcpoAddDeliveryAddress($aMockResponse, 'someUserId'));
    }

    /**
     * Testing _fcpoCheckAddressExists for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoCheckAddressExists_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');
        $oMockAddress = $this->getMock('oxAddress', array('load'));
        $oMockAddress->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoCheckAddressExists('someEncodedAddress'));
    }

    /**
     * Testing _fcpoSplitStreetAndStreetNr for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoSplitStreetAndStreetNr_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');
        $sMockStreetAndStreetNr = "Some Street 123";
        $aExpect = array('street'=>'Some Street', 'streetnr'=>'123');
        $this->assertEquals($aExpect, $oTestObject->_fcpoSplitStreetAndStreetNr($sMockStreetAndStreetNr));
    }

    /**
     * Testing _fcpoGetCountryIdByIso2 for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoGetCountryIdByIso2_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');

        $oMockCountry = $this->getMock('oxCountry', array('getIdByCode'));
        $oMockCountry->expects($this->any())->method('getIdByCode')->will($this->returnValue('someCountryId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCountry));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someCountryId', $oTestObject->_fcpoGetCountryIdByIso2('someISOCode'));
    }

    /**
     * Testing _fcpoUserExists with option password
     *
     * @param void
     * @return void
     */
    public function test__fcpoUserExists_WithPassword() {
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxpassword = new oxField('somePassword');

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoGetUserOxidByEmail', 'load'));
        $oTestObject->expects($this->any())->method('_fcpoGetUserOxidByEmail')->will($this->returnValue('someUserId'));
        $oTestObject->expects($this->any())->method('load')->will($this->returnValue($oMockUser));

        $this->assertEquals(false, $oTestObject->_fcpoUserExists('someEmailAddress', true));
    }

    /**
     * Testing _fcpoUserExists with option no password
     *
     * @param void
     * @return void
     */
    public function test__fcpoUserExists_NoPassword() {
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxpassword = new oxField('somePassword');

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoGetUserOxidByEmail', 'load'));
        $oTestObject->expects($this->any())->method('_fcpoGetUserOxidByEmail')->will($this->returnValue('someUserId'));
        $oTestObject->expects($this->any())->method('load')->will($this->returnValue($oMockUser));

        $this->assertEquals(true, $oTestObject->_fcpoUserExists('someEmailAddress', false));
    }

    /**
     * Testiong _fcpoGetUserOxidByEmail for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoGetUserOxidByEmail_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');

        $oMockDb = $this->getMock('oxDb', array('GetOne', 'quote'));
        $oMockDb->expects($this->any())->method('GetOne')->will($this->returnValue('someUserId'));
        $oMockDb->expects($this->any())->method('quote')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDb));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someUserId', $oTestObject->_fcpoGetUserOxidByEmail('someEmailAddress'));
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
