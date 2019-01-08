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

class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_adminview extends OxidTestCase {

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     * @throws exception
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
     * @throws exception
     * @return mixed Method return.
     */
    public function invokeSetAttribute(&$object, $propertyName, $value) {
        $reflection = new \ReflectionClass(get_class($object));
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

    /**
     * Testing fcGetAdminSeperator for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test_fcGetAdminSeperator_Coverage() {
        $oTestObject = oxNew('fcpayone_adminview');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4200));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = '?';
        $this->assertEquals($sExpect, $oTestObject->fcGetAdminSeperator());
    }

    /**
     * Testing getViewId for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test_getViewId_Coverage() {
        $oTestObject = oxNew('fcpayone_adminview');
        $this->assertEquals('dyn_fcpayone', $oTestObject->getViewId());
    }

    /**
     * Testing fcpoGetVersion for coverage
     *
     * @param void
     * @return void
     * @throws
     */
    public function test_fcpoGetVersion_Coverage() {
        $oTestObject = oxNew('fcpayone_adminview');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetModuleVersion')->will($this->returnValue('12.0'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = '12.0';
        $this->assertEquals($sExpect, $oTestObject->fcpoGetVersion());
    }


    /**
     * Testing fcpoGetMerchantId for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test_fcpoGetMerchantId_Coverage() {
        $oTestObject = oxNew('fcpayone_adminview');

        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('12345'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = '12345';
        $this->assertEquals($sExpect, $oTestObject->fcpoGetMerchantId());
    }


    /**
     * Testing fcpoGetIntegratorId for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test_fcpoGetIntegratorId_Coverage() {
        $oTestObject = oxNew('fcpayone_adminview');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntegratorId')->will($this->returnValue('someValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = 'someValue';
        $this->assertEquals($sExpect, $oTestObject->fcpoGetIntegratorId());
    }
}
