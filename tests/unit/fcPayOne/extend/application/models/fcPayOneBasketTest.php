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
 
class Unit_fcPayOne_Extend_Application_Models_fcPayOneBasket extends OxidTestCase
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
     * Testing fcpoIsPayPalExpressActive for coverage
     */
    public function test_fcpoIsPayPalExpressActive_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneBasket');

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue(1));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(true, $oTestObject->fcpoIsPayPalExpressActive());
    }
    
    
    /**
     * Testing fcpoGetPayPalExpressPic for coverage
     */
    public function test_fcpoGetPayPalExpressPic_Coverage() 
    {
        $oTestObject = oxNew('fcPayOneBasket');

        $oMockLang = $this->getMock('oxLang', array('getBaseLanguage'));
        $oMockLang->expects($this->any())->method('getBaseLanguage')->will($this->returnValue('0'));

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('somePic'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDatabase));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals('somePic', $oTestObject->fcpoGetPayPalExpressPic());
    }
}
