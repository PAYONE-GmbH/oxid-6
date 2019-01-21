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

class Unit_fcPayOne_Extend_Application_Controllers_fcPayOneUserView extends OxidTestCase {

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array()) {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }


    /**
     * Set protected/private attribute value
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $propertyName property that shall be set
     * @param array  $value value to be set
     *
     * @return mixed Method return.
     */
    public function invokeSetAttribute(&$object, $propertyName, $value) {
        $reflection = new \ReflectionClass(get_class($object));
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

    /**
     * Testing fcpoGetBasketErrorMessage for coverage
     */
    public function test_fcpoGetUserErrorMessage_Coverage() {
        $oTestObject = oxNew('fcPayOneUserView');

        $oHelper = $this->getMock('fcpohelper',
            array(
                'fcpoGetRequestParameter',
                'fcpoDeleteSessionVariable'
            )
        );
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetRequestParameter')
            ->will($this->returnValue('someMessage'));
        $oHelper
            ->expects($this->any())
            ->method('fcpoDeleteSessionVariable')
            ->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someMessage', $oTestObject->fcpoGetUserErrorMessage());
    }


    /**
     * Testing fcpoAmazonLoginReturn with needed data
     *
     * @param void
     * @return void
     */
    public function test_fcpoAmazonLoginReturn_NeededDataAvailable() {
        $oMockBasket = $this->getMock('oxBasket', array('setPayment'));
        $oMockBasket->expects($this->any())->method('setPayment')->will($this->returnValue(null));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oTestObject = $this->getMock('fcPayOneUserView', array(
            'getSession',
            '_fcpoHandleAmazonNoTokenFound',
            'render',
        ));
        $oTestObject->expects($this->any())->method('getSession')->will($this->returnValue($oMockSession));
        $oTestObject->expects($this->any())->method('_fcpoHandleAmazonNoTokenFound')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('render')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(null));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('someToken'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);


        $this->assertNull($oTestObject->fcpoAmazonLoginReturn());
    }

    /**
     * Testing fcpoAmazonLoginReturn without needed data
     *
     * @param void
     * @return void
     */
    public function test_fcpoAmazonLoginReturn_NeededDataUnavailable() {
        $oMockBasket = $this->getMock('oxBasket', array('setPayment'));
        $oMockBasket->expects($this->any())->method('setPayment')->will($this->returnValue(null));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oTestObject = $this->getMock('fcPayOneUserView', array(
            'getSession',
            '_fcpoHandleAmazonNoTokenFound',
            'render',
        ));
        $oTestObject->expects($this->any())->method('getSession')->will($this->returnValue($oMockSession));
        $oTestObject->expects($this->any())->method('_fcpoHandleAmazonNoTokenFound')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('render')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(null));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);


        $this->assertNull($oTestObject->fcpoAmazonLoginReturn());
    }

    /**
     * Testing _fcpoHandleAmazonNoTokenFound for case that double redirect is allowed
     *
     * @param void
     * @return void
     */
    public function test__fcpoHandleAmazonNoTokenFound_Allowed() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam', 'getShopUrl'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('redirect'));
        $oMockConfig->expects($this->any())->method('getShopUrl')->will($this->returnValue('https://someurl.com/'));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(null));

        $oTestObject = $this->getMock('fcPayOneUserView', array(
            'getConfig',
            'render',
        ));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('render')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertNull($oTestObject->_fcpoHandleAmazonNoTokenFound());
    }

    /**
     * Testing _fcpoHandleAmazonNoTokenFound for case that double redirect is not allowed
     *
     * @param void
     * @return void
     */
    public function test__fcpoHandleAmazonNoTokenFound_NotAllowed() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam', 'getShopUrl'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('popup'));
        $oMockConfig->expects($this->any())->method('getShopUrl')->will($this->returnValue('https://someurl.com/'));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(null));

        $oTestObject = $this->getMock('fcPayOneUserView', array(
            'getConfig',
            'render',
        ));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('render')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertNull($oTestObject->_fcpoHandleAmazonNoTokenFound());
    }
}
