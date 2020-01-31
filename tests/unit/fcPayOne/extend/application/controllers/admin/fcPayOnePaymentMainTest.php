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

class Unit_fcPayOne_Extend_Application_Controllers__Admin_fcPayOnePaymentMain extends OxidTestCaseCompatibilityWrapper
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
     * Testing fcpoGetConfBools for coverage
     */
    public function test_fcpoGetConfBools_Coverage()
    {
        $oTestObject = oxNew('fcPayOnePaymentMain');

        $aMockBools = array('someValue');
        $this->invokeSetAttribute($oTestObject, '_aConfBools', $aMockBools);

        $this->assertEquals($aMockBools, $oTestObject->fcpoGetConfBools());
    }

    public function test_save_coverage()
    {
        $aMockBools = array('someValue'=> true);
        $oMockConfig = $this->getMock('oxConfig', array(
            'saveShopConfVar',
            'getShopId',
        ));
        $oMockConfig
            ->expects($this->any())
            ->method('saveShopConfVar')
            ->will($this->returnValue(null));
        $oMockConfig
            ->expects($this->any())
            ->method('getShopId')
            ->will($this->returnValue('someShopId'));

        $oTestObject = $this->getMock('fcPayOnePaymentMain', array(
            '_fcpoLoadConfigs'
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoLoadConfigs')
            ->will($this->returnValue(null));

        $oHelper =
            $this->getMockBuilder('fcpohelper')
                ->disableOriginalConstructor()
                ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetRequestParameter')
            ->will($this->returnValue($aMockBools));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->save());
    }

    /**
     * Testing _fcpoLoadConfigs for coverage
     */
    public function test__fcpoLoadConfigs_Coverage()
    {
        $oTestObject = oxNew('fcPayOnePaymentMain');
        $aMockExportConfig = array('bools'=>array('someValue'=> true));

        $oHelper =
            $this->getMockBuilder('fcpoconfigexport')
                ->disableOriginalConstructor()
                ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($aMockExportConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoConfigExport', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoLoadConfigs('someId'));
    }
}