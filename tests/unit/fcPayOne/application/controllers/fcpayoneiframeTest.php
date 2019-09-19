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

class Unit_fcPayOne_Application_Controllers_fcpayoneiframe extends OxidTestCase
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

    public function test_Render_Coverage()
    {
        $oTestObject = oxNew('fcpayoneiframe');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4800));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = $sResult = $oTestObject->render();
        $this->assertEquals($sExpect, $sResult);
    }

    public function test_Render_Coverage2()
    {
        $oTestObject = oxNew('fcpayoneiframe');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4600));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = $sResult = $oTestObject->render();
        $this->assertEquals($sExpect, $sResult);
    }

    public function test_getIframeHeight_Coverage()
    {
        $oTestObject = $this->getMock('fcpayoneiframe', array('getPaymentType'));
        $oTestObject->expects($this->any())->method('getPaymentType')->will($this->returnValue('fcpocreditcard_iframe'));

        $sResult = $oTestObject->getIframeHeight();

        $this->assertNotEmpty($sResult);
    }

    public function test_getIframeText_Coverage()
    {
        $oTestObject = $this->getMock('fcpayoneiframe', array('getPaymentType'));
        $oTestObject->expects($this->any())->method('getPaymentType')->will($this->returnValue('fcpocreditcard_iframe'));

        $sResult = $oTestObject->getIframeText();

        $this->assertFalse($sResult);
    }

    public function test_getIframeHeader_Coverage()
    {
        $oTestObject = $this->getMock('fcpayoneiframe', array('getPaymentType'));
        $oTestObject->expects($this->any())->method('getPaymentType')->will($this->returnValue('fcpocreditcard_iframe'));

        $sResult = $oTestObject->getIframeHeader();

        $this->assertNotEmpty($sResult);
    }

    public function test_getIframeStyle_Coverage()
    {
        $oTestObject = $this->getMock('fcpayoneiframe', array('getPaymentType'));
        $oTestObject->expects($this->any())->method('getPaymentType')->will($this->returnValue('fcpocreditcard_iframe'));

        $sResult = $oTestObject->getIframeStyle();

        $this->assertNotEmpty($sResult);
    }

    public function test_getIframeWidth_Coverage()
    {
        $oTestObject = $this->getMock('fcpayoneiframe', array('getPaymentType'));
        $oTestObject->expects($this->any())->method('getPaymentType')->will($this->returnValue('fcpocreditcard_iframe'));

        $sResult = $oTestObject->getIframeWidth();

        $this->assertNotEmpty($sResult);
    }

    public function test_getFactoryObject_Coverage()
    {
        $oTestObject = oxNew('fcpayoneiframe');

        $sResult = $oTestObject->getFactoryObject('oxOrder');

        $this->assertInstanceOf('oxOrder', $sResult);
    }
}